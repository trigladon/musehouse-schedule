<?php
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var \app\modules\master\forms\PricingForm $pricingForm */
/* @var array $studentList */
/* @var array $teacherList */
/* @var array $priorityList */
/* @var array $lessonList */

$this->title = 'Price Management';
$escape = new JsExpression("function(m) { return m; }");
?>
<h1><?=$this->title ?></h1>
<?php $form = ActiveForm::begin([
    'id' => 'pricing_form',
    'class' => 'form-inline',
]); ?>

<?= Html::activeHiddenInput($pricingForm,'id', [
    'value' => '',
]);?>
<div class="row">
    <div class="col-md-3 col-xs-12">
        <?= $form->field($pricingForm, 'studentId')->widget(Select2::className(), [
            'data' => $studentList,
            'theme' => Select2::THEME_BOOTSTRAP,
            'hideSearch' => true,
            'options' => ['placeholder' => 'Choose the Student'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    </div>
    <div class="col-md-3 col-xs-12">
        <?= $form->field($pricingForm, 'teacherId')->widget(Select2::className(), [
            'data' => $teacherList,
            'theme' => Select2::THEME_BOOTSTRAP,
            'hideSearch' => true,
            'options' => ['placeholder' => 'Choose the Teacher'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    </div>
    <div class="col-md-3 col-xs-12">
        <?= $form->field($pricingForm, 'price')->input('double') ?>
    </div>
    <div class="col-md-3 col-xs-6">
        <?php $pricingForm->priority = 1 ?>
        <?= $form->field($pricingForm, 'priority')->widget(Select2::className(), [
            'data' => $priorityList,
            'theme' => Select2::THEME_BOOTSTRAP,
            'hideSearch' => true,
            'options' => ['placeholder' => 'Choose the Priority'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    </div>
<!--</div>-->
<!--<div class="row">-->
    <div class="col-md-6 col-xs-6">
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
    <div class="col-md-6 col-xs-12">
        <?= $form->field($pricingForm, 'dateRange', [
            'addon' => ['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
            'options' => ['class'=>'drp-container form-group']
        ])->widget(DateRangePicker::classname(), [
            'useWithAddon' => true,
            'startInputOptions' => ['value' => ''],
            'endInputOptions' => ['value' => ''],
            'pluginOptions' => [
                'locale' => [
                    'separator' => ' to ',
                    'format'=>'Y-MM-DD'
                ],
            ]
        ]);?>
    </div>
</div>
<div class="form-group">
    <div>
        <?= Html::submitButton('SetUp Price', ['class' => 'btn btn-primary', 'id' => 'setupPriceButton'])?>
    </div>
</div>
<?php ActiveForm::end() ?>
