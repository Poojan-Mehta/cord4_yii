<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\filters\AccessControl;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $user_email;
    public $user_password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['user_email', 'user_password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['user_password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            // Check if user is null
            if ($user === null) {
                $this->addError($attribute, 'User not found.');
                return;
            }

            // Validate the password against the stored hash
            $hashedPassword = Yii::$app->security->generatePasswordHash($this->user_password);
            //echo '<pre>'; print_r($hashedPassword); exit;
            if (!Yii::$app->security->validatePassword($this->user_password, $user->user_password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }


    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        // Validate the form inputs first
        if ($this->validate()) {
            // If validation passes, log in the user
            //echo '<pre>'; print_r($this->getUser()); exit;
            
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne(['user_email' => $this->user_email]);
        }

        return $this->_user;
    }
}
