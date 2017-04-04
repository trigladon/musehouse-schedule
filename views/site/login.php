<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'email')->label(false)->input('email', [
            'autofocus' => true,
            'placeholder' => 'Email',
        ]) ?>

        <?= $form->field($model, 'password')->label(false)->passwordInput([
            'placeholder' => 'Password'
        ]) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

        <div class="form-group">
            <div class="col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    <?= Html::a('Click if you forgot password!', ['/site/send-recovery-email'])?>

    <?php if (Yii::$app->session->hasFlash('reg_succ')): ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-close"></i></h4>
            <?= Yii::$app->session->getFlash('error_time_expired') ?>
        </div>
    <?php elseif (Yii::$app->session->hasFlash('reg_error')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-close"></i></h4>
            <?= Yii::$app->session->getFlash('error_key_not_found') ?>
        </div>
    <?php endif;?>
</div>
