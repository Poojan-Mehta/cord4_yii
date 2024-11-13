<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Account Activation';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>Please enter the activation code sent to your email or mobile.</p>

<div class="user-activation">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'otp')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Activate Account', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
