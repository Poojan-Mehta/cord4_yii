<?php

namespace app\controllers;

use Yii;

use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['view', 'index'], // Specify actions that require authentication
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'], // '@' means authenticated users
                        ],
                        [
                            'allow' => false,
                            'roles' => ['?'], // '?' means guests (unauthenticated users)
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id User ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                // Set account to inactive and generate activation token
                $model->user_status = 0; // Default inactive
                $model->otp = rand(1000, 9999); // Generate a 4-digit code

                // Check for validation errors
                if (!$model->validate()) {
                    // Convert the errors to a readable string format
                    $errors = '';
                    foreach ($model->errors as $attribute => $messages) {
                        $errors .= implode(', ', $messages) . "\n";
                    }

                    // Set the flash message to display in the view
                    Yii::$app->session->setFlash('error', $errors);
                    return $this->redirect(['create']); // Redirect to the activation form
                }

                if ($model->save()) {
                    // Send activation code (displayed in demo message here)
                    $this->sendActivationCode($model);
                    Yii::$app->session->setFlash('success', 'An activation code has been sent. Please check your email or mobile to activate your account.');
                    return $this->redirect(['activate']); // Redirect to the activation form
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    protected function sendActivationCode($user)
    {
        $code = $user->otp;

        // Demo: display the code
        Yii::$app->session->setFlash('info', "Your activation code: $code");
    }

    public function actionActivate()
    {
        $model = new User();

        if ($this->request->isPost) {
            // Find the user with the provided activation code
            $user = User::findOne(['otp' => $this->request->post('User')['otp'], 'user_status' => 0]);

            if ($user) {
                // Activate the account
                $user->user_status = 1;
                $user->otp = null; // Clear the code to prevent reuse

                if ($user->save(false)) {
                    Yii::$app->session->setFlash('success', 'Your account has been activated. You can now log in.');
                    return $this->redirect(['login']);
                } else {
                    Yii::$app->session->setFlash('error', 'Activation failed. Please try again.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Invalid activation code.');
            }
        }

        return $this->render('activation', [
            'model' => $model,
        ]);
    }
    
    public function actionValidateOtp($id)
    {
        $user = User::findOne($id);
        if (!$user || Yii::$app->request->post('otp') !== '1234') {
            Yii::$app->session->setFlash('error', 'Invalid OTP.');
            return $this->redirect(['login']);
        }
        Yii::$app->session->setFlash('success', 'OTP validated. You can now log in.');
        return $this->redirect(['login']);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id User ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {   
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                Yii::$app->session->setFlash('success', 'Profile updated successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        }
        
        //Yii::$app->session->setFlash('error', 'Error updating profile.');
        return $this->render('update', ['model' => $model, 'action' => 'update']);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id User ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id User ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }    

    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            
            if ($user->user_type !== Yii::$app->params['roleAdmin'] && in_array($action->id, ['delete', 'create', 'block', 'unblock'])) {
                Yii::$app->session->setFlash('error', 'You are not authorized to perform this action.');
                return $this->redirect(['view', 'id' => $user->id]);
            }
            
            if ($user->user_type === Yii::$app->params['roleUser'] && !in_array($action->id, ['view', 'update'])) {
                Yii::$app->session->setFlash('error', 'You can only view or update your own profile.');
                return $this->redirect(['view', 'id' => $user->id]);
            }
        }
        
        return parent::beforeAction($action);
    }

    public function actionLoginOld()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        if ($model && $model->user_status == 2) {
            Yii::$app->session->setFlash('error', 'This user is blocked by admin.');
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        //echo '<pre>'; print_r($model); exit;
        // Check if the form was submitted
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Find user by email
            $user = User::findOne(['user_email' => $model->user_email]);

            // Check if user exists
            if ($user === null) {
                Yii::$app->session->setFlash('error', 'User not found.');
                return $this->render('login', ['model' => $model]);
            }

            // Check if the user is blocked (user_status = 2)
            if ($user->user_status == 2) {                
                Yii::$app->user->logout(); // Log out the user in case they are already logged in4
                Yii::$app->session->setFlash('error', 'This user is blocked by admin.');
                return $this->render('login', ['model' => $model]);
            }

            // Everything is good, redirect to the previous page
            return $this->goBack();
        }

        // Reset password field
        $model->user_password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome(); // Redirect to home page or another page after logout
    }

}
