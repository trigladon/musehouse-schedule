<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\AuthItemChild;
use app\components\ActivationListWidget;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\master\forms\InviteUserForm */
/* @var $studentAddForm app\modules\master\forms\StudentAddForm */
/* @var $form ActiveForm */

$this->title = 'User Management';
?>
<div class="site-inviteUser">
    <h1><?= Html::encode($this->title) ?></h1>

    <div>
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
    <div>
        <?php
            echo ActivationListWidget::widget([
                'user_list' => $user_list,
                'userUpdate' => $userUpdate,
                'listUserLessons' => $listUserLessons,
                'teacherList' => $teacherList,
            ]);
        ?>
    </div>
    <div class="row" style="padding: 0 15px">
        <div class="col-md-3">
            <h4 class="text-info">Add new User</h4>
            <?= Select2::widget([
                'name' => 'roleFormFilter',
                'data' => $role_list,
                'theme' => Select2::THEME_BOOTSTRAP,
                'hideSearch' => true,
                'options' => ['placeholder' => 'Choose the Role'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])?>
        </div>
        <div id="inviteForm" style="display: none; margin-top: 39px" class="col-md-3">
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

            <div style="display: none">
                <?= $form->field($model, 'role')->label(false)->widget(Select2::className(), [
                    'data' => $role_list,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => ['placeholder' => 'Choose the Role'],
                ]); ?>
            </div>



            <div class="form-group">
                <div class="col-lg-11">
                    <?= Html::submitButton('Invite', ['class' => 'btn btn-success', 'id' => 'inviteTeacher'])?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

        <div id="addStudentForm" style="display: none; margin-top: 39px" class="col-md-3">
            <?php $form = ActiveForm::begin([
                'id' => 'studentAdd-form',
                'layout' => 'horizontal',
                'class' => 'form-inline',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
            ]); ?>

            <?= $form->field($studentAddForm, 'first_name')->label(false)->textInput([
                'placeholder' => 'First Name',
            ]); ?>

            <?= $form->field($studentAddForm, 'last_name')->label(false)->textInput([
                'placeholder' => 'Last Name',
            ]); ?>

            <?= $form->field($studentAddForm, 'teacher')->label(false)->widget(Select2::className(), [
                'data' => $teacherList,
                'theme' => Select2::THEME_BOOTSTRAP,
                'hideSearch' => true,
                'options' => ['placeholder' => 'Teachers', 'multiple' => true],
                'pluginOptions' => [
                    'closeOnSelect' =>false,
                ],
            ]); ?>

            <div style="display: none">
                <?= $form->field($studentAddForm, 'role')->label(false)->widget(Select2::className(), [
                    'data' => $role_list,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => ['placeholder' => 'Choose the Role'],
                ]); ?>
            </div>



            <div class="form-group">
                <div class="col-lg-11">
                    <?= Html::submitButton('Add Student', ['class' => 'btn btn-success', 'id' => 'addStudent'])?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

        <div class="col-md-6 pull-right" style="margin-top: 39px">
            <table class="table">
                <tr>
                    <td class="text-center col-md-2">
                        <i class="fa fa-check fa-lg text-danger" style="margin-right:11px" aria-hidden="true"></i>
                        <i class="fa fa-share text-warning" aria-hidden="true"></i>
                        <i class="fa fa-envelope text-warning" aria-hidden="true"></i>
                    </td>
                    <td class="text-left">
                        <em> - the registration letter <em class="text-danger">wasn't sent</em>. You can resend it by clicking on <i class="fa fa-share text-warning" aria-hidden="true"></i>
                            <i class="fa fa-envelope text-warning" aria-hidden="true"></i> in the table.</em>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <i class="fa fa-check fa-lg text-success" style="margin-right:11px" aria-hidden="true"></i>
                        <i class="fa fa-share text-warning" aria-hidden="true"></i>
                        <i class="fa fa-envelope text-warning" aria-hidden="true"></i>
                    </td>
                    <td class="text-left">
                        <em> - the registration letter <em class="text-success">was sent</em>.</em>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <i class="fa fa-user fa-lg text-success" aria-hidden="true"></i>
                        <em style="padding: 0 5px">or</em>
                        <i class="fa fa-user fa-lg text-danger" aria-hidden="true"></i>
                    </td>
                    <td class="text-left">
                        <em> - the User is <em class="text-success">registered</em> or <em class="text-danger">not</em> after email receiving.</em>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</div><!-- site-inviteUser -->
