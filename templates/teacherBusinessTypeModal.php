<?php

use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

/** @var $businessTypeForm \app\modules\master\forms\TeacherBusinessTypeForm */
/** @var $businessTypes array */

?>

<?php Modal::begin([
    'header' => '<h4 class="text-info">Set Business Type</h4>',
    'id'     => 'modalTeacherBT',
    'size' => 'modal-sm',
//    'footer' => Html::a('Delete', '', ['class' => 'btn btn-danger', 'id' => 'delete-confirm']),
]); ?>

<?php $form = ActiveForm::begin([
    'id' => 'modalTeacherBTForm',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'fieldConfig' => [
        'template' => "<div>{label}</div><div class=\"col-lg-12\">{input}</div>{error}",
        'labelOptions' => ['class' => 'col-lg-12 control-label', 'style' => 'text-align: left'],
        'inputOptions' => ['class' => 'form-control'],
    ],
]); ?>

<?= $form->field($businessTypeForm, 'business_type')->widget(Select2::className(), [
    'data' => $businessTypes,
    'theme' => Select2::THEME_BOOTSTRAP,
    'hideSearch' => true,
    'options' => ['placeholder' => 'Choose business type'],
    'pluginOptions' => [
        'allowClear' => true,
    ],
]);?>

<?= $form->field($businessTypeForm, 'date_from')->textInput([
    'id' => 'datetimepicker5',
    'placeholder' => 'Date',
])?>

<?= Html::activeHiddenInput($businessTypeForm,'teacher_id', [
    'value' => '',
]);?>

<div class="form-group">
    <div class="col-lg-2">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id' => 'saveBusinessType'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
