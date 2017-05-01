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
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\web\View;

$this->title = 'Calendar';
?>

<div class="site-calendar">
    <div id="mainCalendar">
        <?=CalendarWidget::widget([
            'calendarArray' => $calendarArray,
            'monthToShow' => $monthToShow,
            'whtsh' => $whtsh,
            'weekDaysToShow' => $weekDaysToShow,
            'filterForm' => $filterForm,
            'status_list' => $status_list,
            'lesson_list' => $lesson_list,
            'user_list' => $user_list,
        ])?>
    </div>

<?php

$escape2 = new JsExpression("function(m) { return m; }");
Modal::begin([
    'header' => '<h4 class="text-muted"><i class="fa fa-calendar"></i> <span class="headerLessonCalendarForm">Add/Update a new Lesson!</span></h4>',
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

    <?= $form->field($modelAddLesson, 'action_date')->label(false)->textInput([
        'id' => 'datetimepicker5',
        'placeholder' => 'Date',
    ])?>


    <?= $form->field($modelAddLesson, 'lesson_start')->label(false)->textInput([
        'id' => 'datetimepicker6',
        'placeholder' => 'Start Time',
    ])?>

    <?= $form->field($modelAddLesson, 'lesson_finish')->label(false)->textInput([
        'id' => 'datetimepicker7',
        'placeholder' => 'Finish Time',
    ])?>

<!--    --><?php //$modelAddLesson->instricon_id = 3;?>
    <?= $form->field($modelAddLesson, 'instricon_id')->widget(Select2::className(), [
        'data' => $listUserLessons,
        'theme' => Select2::THEME_BOOTSTRAP,
        'hideSearch' => true,
        'options' => ['placeholder' => 'Type of the Lesson'],
        'pluginOptions' => [
            'escapeMarkup' => $escape2,
            'allowClear' => true,
        ],
    ])->label(false);?>

    <?= $form->field($modelAddLesson, 'statusschedule_id')->widget(Select2::className(), [
        'data' => $status_list,
        'theme' => Select2::THEME_BOOTSTRAP,
        'hideSearch' => true,
        'options' => ['placeholder' => 'Status'],
        'pluginOptions' => [
            'escapeMarkup' => $escape2,
            'allowClear' => true,
        ],
    ])->label(false);?>

    <?= $form->field($modelAddLesson, 'comment')->label(false)->textarea([
        'placeholder' => 'Comments',
        'id' => 'comment',
    ])?>

    <?= Html::activeHiddenInput($modelAddLesson,'id', [
        'class' => 'action_date_form',
        'id' => 'lessonIdToUpdate',
    ]);?>

<div class="form-group">
    <div class="col-lg-11">
        <?= Html::submitButton('Add Lesson', ['class' => 'btn', 'id' => 'addLesson-confirm'])?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>

<?php Modal::begin([
    'header' => '<h3 class="text-warning"><i class="icon fa fa-exclamation-triangle"></i> Warning!</h3>',
    'id'     => 'modal-deleteLesson',
    'size' => 'modal-sm',
    'footer' => Html::a('Delete', '', ['class' => 'btn btn-danger', 'id' => 'delete-confirm']),
]); ?>

<p class="modal-message">Do you really want to delete this lesson?</p>
<div style="width: 60px; margin-right: 3px" class="text-right pull-left"><strong>Date:</strong></div><div id="lessonDate"></div>
<div style="width: 60px; margin-right: 3px" class="text-right pull-left"><strong>Time:</strong></div><div id="lessonTime"></div>
<div style="width: 60px; margin-right: 3px" class="text-right pull-left"><strong>Teacher:</strong></div><div id="lessonTeacher"></div>
<div style="width: 60px; margin-right: 3px" class="text-right pull-left"><strong>Lesson:</strong></div><div id="lessonType"></div>

<?php Modal::end(); ?>
</div>