<?php

use yii\bootstrap\Modal;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var \app\modules\master\forms\PricingForm $pricingForm */
/* @var array $studentList */
/* @var array $teacherList */
/* @var array $lessonList */

$escape = new JsExpression("function(m) { return m; }");
?>

<?php Modal::begin([
    'header' => '<h4 class="text-info">Payments details / targets</h4>',
    'id'     => 'modalPriceManagement',
    'size' => 'modal-lg'
]); ?>
<?php $form = ActiveForm::begin([
    'id' => 'pricing_form',
    'class' => 'form-inline',
]); ?>

<?= Html::activeHiddenInput($pricingForm,'id', [
    'value' => '',
]);?>

<div class="row">
    <div class="col-md-4 col-xs-12">
        <?= $form->field($pricingForm, 'teacherId')->widget(Select2::className(), [
            'data' => $teacherList,
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['placeholder' => 'Choose the Teacher'],
            'pluginOptions' => [
                'escapeMarkup' => $escape,
                'allowClear' => true
            ],
        ]) ?>
    </div>
    <div class="col-md-4 col-xs-12">
        <?= $form->field($pricingForm, 'studentId')->widget(Select2::className(), [
            'data' => $studentList,
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['placeholder' => 'Choose the Student'],
            'pluginOptions' => [
                'escapeMarkup' => $escape,
                'allowClear' => true
            ],
        ]) ?>
    </div>
    <div class="col-md-4 col-xs-12">
        <?= $form->field($pricingForm, 'instrumentId')->widget(Select2::className(), [
            'data' => $lessonList,
            'theme' => Select2::THEME_BOOTSTRAP,
            'hideSearch' => true,
            'options' => ['placeholder' => 'Choose the Instrument'],
            'pluginOptions' => [
                'escapeMarkup' => $escape,
                'allowClear' => true
            ],
        ]) ?>
    </div>
    <div class="col-md-offset-3 col-md-6 col-xs-12">
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <?= $form->field($pricingForm, 'target')->input('number', [
                    'placeholder' => 'Set target (qnt/month)'
                ]) ?>
            </div>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($pricingForm, 'date_from')->textInput([
                    'id' => 'datetimepicker5',
                    'placeholder' => 'Valid from date...',
                ])?>
            </div>
        </div>

    </div>
    <div class="text-muted col-xs-12 text-center">
        Prices according to lessons length (S - short, M - middle, L - long;
        <strong class="text-info">clean</strong> - clean rate you pay to the teacher,
        <strong class="text-info">tax</strong> - tax you have to pay for the teacher;
        <strong class="text-info">clean + tax</strong> - full payment will be calculated and saved to database during saving data)</div>
    <div class="col-md-2 col-xs-6">
        <?= $form->field($pricingForm, 's_clean')->input('double') ?>
    </div>
    <div class="col-md-2 col-xs-6">
        <?= $form->field($pricingForm, 's_tax')->input('double') ?>
    </div>
    <div class="col-md-2 col-xs-6">
        <?= $form->field($pricingForm, 'm_clean')->input('double') ?>
    </div>
    <div class="col-md-2 col-xs-6">
        <?= $form->field($pricingForm, 'm_tax')->input('double') ?>
    </div>
    <div class="col-md-2 col-xs-6">
        <?= $form->field($pricingForm, 'l_clean')->input('double') ?>
    </div>
    <div class="col-md-2 col-xs-6">
        <?= $form->field($pricingForm, 'l_tax')->input('double') ?>
    </div>
</div>
<div class="form-group">
    <div>
        <?= Html::submitButton('SetUp Prices', ['class' => 'btn btn-primary', 'id' => 'setupPriceButton'])?>
    </div>
</div>
<?php ActiveForm::end() ?>

<?php Modal::end(); ?>
