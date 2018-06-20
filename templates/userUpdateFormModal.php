<?php

use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Html;

/** @var $userUpdate \app\modules\master\forms\UserUpdateForm */
/** @var $listUserLessons array */
/** @var $teacherList array */

?>

<?php Modal::begin([
    'header' => '<h4 class="text-info">User Information</h4>',
    'id'     => 'modalUserEdit',
    'size' => 'modal-sm',
//    'footer' => Html::a('Delete', '', ['class' => 'btn btn-danger', 'id' => 'delete-confirm']),
]); ?>

<?php $form = ActiveForm::begin([
    'id' => 'userUpdateForm_Management',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'fieldConfig' => [
        'template' => "<div>{label}</div><div class=\"col-lg-12\">{input}</div>{error}",
        'labelOptions' => ['class' => 'col-lg-12 control-label', 'style' => 'text-align: left'],
        'inputOptions' => ['class' => 'form-control'],
    ],
]); ?>

<?= $form->field($userUpdate, 'first_name')->textInput([
    'id' => 'first_name',
])?>

<?= $form->field($userUpdate, 'last_name')->textInput([
    'id' => 'last_name',
])?>

<?= $form->field($userUpdate, 'phone')->textInput([
    'id' => 'phone',
])?>

<div id='upFormLessons' style="display: none">
    <?php
    $escape2 = new JsExpression("function(m) { return m; }");
    echo $form->field($userUpdate, 'lessons')->widget(Select2::className(), [
        'id' => 'lessons',
        'data' => $listUserLessons,
        'theme' => Select2::THEME_BOOTSTRAP,
        'hideSearch' => true,
        'options' => ['placeholder' => 'Type of the Lesson', 'multiple' => true],
        'pluginOptions' => [
            'escapeMarkup' => $escape2,
            'allowClear' => true,
            'closeOnSelect' =>false,
        ],
    ])->label('Lessons');?>
</div>

<div id='upFormTeachers' style="display: none">
    <?= $form->field($userUpdate, 'teachers')->label('Teachers')->widget(Select2::className(), [
        'data' => $teacherList,
        'theme' => Select2::THEME_BOOTSTRAP,
        'hideSearch' => true,
        'options' => ['placeholder' => 'Choose the Teacher', 'multiple' => true],
    ]); ?>
</div>

<?= Html::activeHiddenInput($userUpdate,'user_id', [
    'id' => 'user_idInput',
]);?>
<?= Html::activeHiddenInput($userUpdate,'role', [
    'id' => 'user_role',
]);?>

<div class="form-group">
    <div class="col-lg-2">
        <?= Html::submitButton('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update Information', ['class' => 'btn btn-warning', 'id' => 'editUserButton'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
