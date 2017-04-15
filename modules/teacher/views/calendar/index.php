<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 5:08
 */

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\CalendarWidget;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;

$this->title = 'Calendar';
?>

<div class="site-calendar">
    <div id="mainCalendar">
        <?=CalendarWidget::widget([
            'calendarArray' => $calendarArray,
            'monthToShow' => $monthToShow,
            'whtsh' => $whtsh,
            'weekDaysToShow' => $weekDaysToShow,
        ])?>
    </div>

<?php Modal::begin([
    'header' => '<h4 class="text-muted"><i class="fa fa-calendar-plus-o"></i> Add a new Lesson!</h4><div class="text-muted actionDateInput">Data</div>',
    'id'     => 'modal-addLesson',
    'size' => 'modal-sm',
//        'footer' => Html::a('Update', '', ['class' => 'btn btn-danger', 'id' => 'update-confirm']),
//        'footer' => false,
]);

$form = ActiveForm::begin([
    'id' => 'addLesson-form',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'action' => 'calendar',
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>

<!--    public $statusschedule_id;-->
    <?= $form->field($modelAddLesson, 'lesson_start')->label(false)->textInput([
        'id' => 'datetimepicker6',
        'placeholder' => 'Start Time',
    ])?>

    <?= $form->field($modelAddLesson, 'lesson_finish')->label(false)->textInput([
        'id' => 'datetimepicker7',
        'placeholder' => 'Finish Time',
    ])?>

    <?= $form->field($modelAddLesson, 'instricon_id')->label(false)->radioList($listUserLessons)?>

    <?= $form->field($modelAddLesson, 'statusschedule_id')->label(false)->radioList($status_list)?>

    <?= $form->field($modelAddLesson, 'comment')->label(false)->textarea([
        'placeholder' => 'Comments',
    ])?>

    <?= Html::activeHiddenInput($modelAddLesson,'action_date', [
        'class' => 'action_date_form',
    ]);?>

    <?= Html::HiddenInput('whtshModel', $whtsh);?>
    <?= Html::HiddenInput('currentDateModel', $monthToShow['currentDate']);?>
<div class="form-group">
    <div class="col-lg-11">
        <?= Html::submitButton('Add Lesson', ['class' => 'btn btn-success', 'id' => 'addLesson-confirm'])?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
</div>