<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\AuthItemChild;
use app\components\ActivationListWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\forms\InviteUserForm */
/* @var $form ActiveForm */

$this->title = 'User Management';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-inviteUser">
    <h1><?= Html::encode($this->title) ?></h1>
    <div style="float: left; width: 250px;">
        <?php $form = ActiveForm::begin([
            'id' => 'inviteUser-form',
            'layout' => 'horizontal',
            'class' => 'form-inline',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'email')->label(false)->input('email', [
            'placeholder' => 'Email',
        ]) ?>


        <?= $form->field($model, 'role')->label(false)->dropDownList(AuthItemChild::role_list()); ?>


        <div class="form-group">
            <div class="col-lg-11">
                <?= Html::submitButton('Invite', ['class' => 'btn btn-success'])?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div style="float: left; width: 350px; padding: 0 15px;">
        <?php if (Yii::$app->session->hasFlash('error_host_connection')): ?>
            <div class="alert alert-warning alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-exclamation-triangle"></i> Warning!</h4>
                <?= Yii::$app->session->getFlash('error_host_connection') ?>
            </div>
        <?php elseif (Yii::$app->session->hasFlash('email_was_sent')): ?>
            <div class="alert alert-success alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-hand-peace-o"></i> Success!</h4>
                <?= Yii::$app->session->getFlash('email_was_sent') ?>
            </div>
        <?php elseif (Yii::$app->session->hasFlash('error_user_save')): ?>
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-close"></i> Error!</h4>
                <?= Yii::$app->session->getFlash('error_user_save') ?>
            </div>
        <?php endif;?>
    </div>
    <div style="float: right; width: 500px; padding: 0 15px">
        sdfdfgdf
    </div>

    <div>
        <?php
            echo ActivationListWidget::widget(['user_list' => $user_list]);
        ?>
    </div>

</div><!-- site-inviteUser -->
