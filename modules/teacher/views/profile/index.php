<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 09.04.17
 * Time: 16:06
 */

/* @var $this yii\web\View */
/* @var $listUserLessons app\modules\master\models\Instrument */
/* @var $userUpdateForm app\modules\master\forms\UserUpdateForm */
/* @var $passwordUpdateForm app\modules\master\forms\PasswordUpdateForm */
/* @var $user app\models\User */

ini_set('xdebug.var_display_max_depth', 15);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\web\View;
use yii\bootstrap\ActiveForm;

$this->title = 'Profile';
?>

<div class="teacher-profile">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('Error')): ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-exclamation-triangle"></i> Warning!</h4>
            <?= Yii::$app->session->getFlash('Error') ?>
        </div>
    <?php elseif (Yii::$app->session->hasFlash('Success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-hand-peace-o"></i> Success!</h4>
            <?= Yii::$app->session->getFlash('Success') ?>
        </div>
    <?php endif;?>

    <?php $form = ActiveForm::begin([
        'id' => 'userUpdateForm',
        'layout' => 'horizontal',
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'fieldConfig' => [
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <h4 class="text-info" style="margin-top: 30px">User Information</h4>
    <div style="margin: 30px 0; width: 100%">
        <div>

            <?= $form->field($userUpdateForm, 'first_name')->label('First Name', ['style'=>'width: 150px'])->textInput([
                'value' => $user->getFirstName(),
            ])?>

            <?= $form->field($userUpdateForm, 'last_name')->label('Last Name', ['style'=>'width: 150px'])->textInput([
                'value' => $user->getLastName(),
            ])?>

            <?php foreach ($user->getUserLessons() as $lessons):;
                   $userInstr[] = $lessons['instricon']['id'];
                endforeach;
                $userInstr ? $userUpdateForm->lessons = $userInstr : $userInstr ='';
                $escape2 = new JsExpression("function(m) { return m; }");
                echo $form->field($userUpdateForm, 'lessons')->widget(Select2::className(), [
                    'data' => $listUserLessons,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => ['placeholder' => 'Type of the Lesson', 'multiple' => true],
                    'pluginOptions' => [
                        'escapeMarkup' => $escape2,
                        'allowClear' => true,
                        'closeOnSelect' =>false,
                    ],
                ])->label('Lessons', ['style'=>'width: 150px']);?>

            <?= Html::activeHiddenInput($userUpdateForm,'user_id', [
                'value' => $user->getId(),
            ]);?>

            <div class="form-group">
                <div class="col-lg-2">
                    <?= Html::submitButton('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update Information', ['class' => 'btn btn-warning', 'id' => 'editProfile'])?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>

<hr>
    <?php $form = ActiveForm::begin([
        'id' => 'passwordUpdateForm',
        'layout' => 'horizontal',
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'fieldConfig' => [
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    <h4 class="text-info" style="margin-top: 30px">Password Update</h4>
    <div style="margin: 30px 0; width: 100%">
        <div>
            <?= $form->field($passwordUpdateForm, 'oldPass')->label('Old Password', ['style'=>'width: 150px'])->passwordInput([
                'placeholder' => 'Old Password',
            ]);
            ?>

            <?= $form->field($passwordUpdateForm, 'newPass')->label('New Password', ['style'=>'width: 150px'])->passwordInput([
                'placeholder' => 'New Password',
            ]);
            ?>

            <?= $form->field($passwordUpdateForm, 'newPass_repeat')->label('Repeat Password', ['style'=>'width: 150px'])->passwordInput([
                'placeholder' => 'Repeat the New Password',
            ]);
            ?>

            <?= Html::activeHiddenInput($passwordUpdateForm,'user_id', [
                'value' => $user->getId(),
            ]);?>

            <div class="form-group">
                <div class="col-lg-2">
                    <?= Html::submitButton('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update Password', ['class' => 'btn btn-warning', 'id' => 'changePass', 'style' => 'width:166px'])?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>