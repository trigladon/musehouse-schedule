<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 28.03.17
 * Time: 20:43
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\web\View;
use app\models\User;

ini_set('xdebug.var_display_max_depth', 10);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

$now = new DateTime();
$cur = $now->format('U');
$curMY =  $now->format('m-Y');
$curM =  $now->format('m');
$curY =  $now->format('Y');
$now->modify('first day of this month midnight');
$now->modify(Yii::$app->params['lessonEditing']);
$till = $now->format('U');


?>
<!-- ------------------------------------------------------------------------------------ -->

<div id="calendar-wrap">
    <header style="margin-bottom: 5px; height: 35px; width: 100%" class="text-center">
        <div style="width:15%; float: left">
            <div class="btn btn-info" onclick="onClickMonth('<?=$monthToShow['currentDate']?>', ' -1 <?=$whtsh?>', '<?=$whtsh?>')">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </div>
        </div>
        <div style="width:70%; float: left">
            <div id="infoDiv" class="monthName" onclick="onClickMonth('<?=$monthToShow['toShow']?>', '', 'month')" currentDate="<?=$monthToShow['currentDate']?>" whtsh="<?=$whtsh?>">
                <?=$monthToShow['month'].' '.$monthToShow['year']?>
            </div>
        </div>
        <div style="width:15%; float: left">
            <div class="btn btn-info" onclick="onClickMonth('<?=$monthToShow['currentDate']?>', ' +1 <?=$whtsh?>', '<?=$whtsh?>')">
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </div>
        </div>
    </header>
    <div id="calendar">
        <ul class="weekHeader">
            <li class="weekNumber">Week</li>
            <?php foreach ($weekDaysToShow as $value):?>
                <li style="<?=$whtsh=='day'? 'width: calc(100% - 54px);':''?>" class="weekName <?= $value == 'Saturday'||$value == 'Sunday'?'holiday':'';?>"><?=$value?></li>
            <?php endforeach;?>
        </ul>
        <?php
        $_year = '';        $_week = '';        $_month = '';        $_day = '';        $_day_of_the_week = '';

        switch ($whtsh){
            case 'week':
                $dayStyle = 'min-height: 901px;';
                $weekStyle = 'min-height: 901px;';
                break;
            case 'day':
                $dayStyle = 'min-height: 901px; width: calc(100% - 54px);';
                $weekStyle = 'min-height: 901px;';
                break; //1085px width
            default:
                $dayStyle = '';
                $weekStyle = '';
                break;
        };

        foreach ($calendarArray as $year):

            global $_year, $_week, $_month, $_day, $_day_of_the_week;

            if (is_string($year)){
                $_year = $year;
            }else{
                foreach ($year['week'] as $week):
                    if (is_string($week)){
                        $_week = $week;
                        if ($_day_of_the_week == '7' || !$_day_of_the_week):?>
                            <ul><li class="weekCell" style="vertical-align:middle;text-align:center;line-height:<?=$whtsh=='month'?'139':'891'?>px;<?=$weekStyle?>" onclick="onClickMonth(<?=$_week?>, '', 'week')" week="<?=$_week?>"><span class="weekstyle"><?=$_week?></span></li>
                        <?php endif;
                    }else{
                        foreach ($week['month'] as $month):
                            if (is_string($month)) {
                                $_month = $month;
                            }else{
                                foreach ($month['day'] as $day):
                                    if (is_string($day)) {
                                        $_day = $day;
                                    }else {
                                        $_day_of_the_week = $day['day_of_the_week'];

                                        $holiday = $_day_of_the_week >= '6' ? ' holiday' : '';
                                        if (date('n j Y', mktime(0, 0, 0, $_month, $_day, $_year)) == date('n j Y')) {
                                            $today = ' today';
                                        } else {
                                            $today = '';
                                        }
                                        if ($whtsh !== 'week') {
                                            if (date('F', mktime(0, 0, 0, $_month, $_day, $_year)) == $monthToShow['month']) {
                                                $currentMonth = '';
                                            } else {
                                                $currentMonth = ' notCurrentMonth';
                                            }
                                        } else {
                                            $currentMonth = '';
                                        }

                                        if ($whtsh == 'day') {
                                            $mothToDate = "<span class='monthToDate'>" . $monthToShow['month'] . " " . $monthToShow['year'] . "</span>";
                                            $dayStyleToDate = 'style="width: 150px"';
                                        } else {
                                            $mothToDate = '';
                                            $dayStyleToDate = '';
                                        }
                                        ?>
<!--  ---------------------------------------------------------------------------------------------------------------  -->

<li style="padding: 0; <?=$dayStyle?>" class="dayCell <?="$holiday $today $currentMonth"?>" week="<?=$_week?>">
<!-- START DAY LINE BAR -->
    <div class="dayLineBar">
        <div class="dayBar" <?=$dayStyleToDate?> onclick="onClickMonth('<?=$_year?>-<?=$_month?>-<?=$_day?>', '', 'day')">
            <?=$_day?><?=$mothToDate?>
        </div>

        <div class="addAction">
        <?php
        $chMY = date( "m-Y", strtotime( $_year."-".$_month."-".$_day));
        $chM = date( "m", strtotime( $_year."-".$_month."-".$_day));
        $chY = date( "Y", strtotime( $_year."-".$_month."-".$_day));
        if ($chMY == $curMY || $cur<$till && $chY == $curY && ($curM-$chM)==1 || ($curM-$chM)<0 || User::isMaster()):?>
            <?=
            Html::tag('span', '<i class="fa fa-calendar-plus-o" aria-hidden="true"></i>', [
                'class'       => 'popup-addLesson linkaction',
                'data-day' => $_day,
                'data-month' => $_month,
                'data-year' => $_year,
                'data-week' => $_week,
                'id'          => 'popup-addLesson',
            ])
            ?>
        <?php else:?>
            <i class="fa fa-calendar-plus-o text-muted" aria-hidden="true"></i>
        <?php endif;?>
        </div>
        <div class="infoDayLine">
            <?php
            $finished = 0;
            $planned = 0;
            $free = 0;
            ?>
            <?php if(isset($day['actionList'])):
                foreach ($day['actionList'] as $actions):
                    switch ($actions['name']){
                        case 'Free Time': $free+=1; break;
                        case 'Planned': $planned+=1;break;
                        case 'Finished': $finished+=1;break;
                    }
                endforeach;
            endif;?>
            <div class="dayInfo" style="color: #66bb6a;">
                <strong><?=$finished?></strong>
            </div>
            <div class="dayInfo" style="color: #42a5f5;">
                <strong><?=$planned?></strong>
            </div>
            <div class="dayInfo" style="color: #78909c;">
                <strong><?=$free?></strong>
            </div>
        </div>
    </div>
<!-- END DAY LINE BAR -->
<!-- START LESSON -->
    <?php if(isset($day['actionList'])):?>
        <?php
        $count = 0;
        $qnt = count($day['actionList']);
        ?>
        <?php foreach ($day['actionList'] as $actions):
            $count++;
            $dayActionStatus = ' style="background-color: '.$actions['color'].'"';
            ?>
            <?php if ($count === 4 && $whtsh == 'month' && $qnt > 3):?>
            <div id="showMoreActions<?=$actions['lesson_id']?>" class="showMoreActions" onclick="showLayer('<?=$actions['lesson_id']?>', '<?=151+4+($qnt-3)*38?>', '<?=$_week?>')">
                <i id="iconChange<?=$actions['lesson_id']?>" class="fa fa-caret-down iconShowHide" aria-hidden="true"></i>
            </div>
            <?php endif;?>
            <?php if($count == 4 && $whtsh == 'month'): ?>
                <div class="hidenDiv" id = "<?=$actions['lesson_id']?>" style="display: none">
            <?php endif; ?>
                <div class="dropdown">
                    <div class="dayAction img-rounded dropdown-toggle" data-toggle="dropdown" <?=$dayActionStatus?>>
                        <div class="row" style="margin: 0">
                            <div class="timeField">
                                <?=date('H:i', $actions['lesson_start'])?>
                                <?=date('H:i', $actions['lesson_finish'])?>
                            </div>
                            <div class="lessonIconAction">
                                <img src="/images/icons/<?=$actions['icon']?>" class="icon_reg_action img-thumbnail" alt="<?=$actions['instr_name']?>" title="<?=$actions['instr_name']?>">
                            </div>
                            <div class="nameTeacherAction">
                                <?=$actions['first_name'].' '.$actions['last_name']?>
                            </div>
                            <?php if ($whtsh == 'day'):?>
                                <div class="divComment">
                                    <?=$actions['comment']?>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="row" style="margin: 0;font-size: 10px;line-height: 11px;border-top: solid 1px white;">
                            <div style="width: 50px;padding-right: 5px" class="text-right pull-left"><?=$actions['length']?> min.</div>
                            <div style="max-width: 94px; min-width: 63px; width: calc(100%-50px);text-overflow: ellipsis;overflow: hidden;" class="pull-left"><?=$actions['student_id']?$students_listFull[$actions['student_id']]:'No student'?></div>
                        </div>
                    </div>
                    <?php
                    if ($chMY == $curMY || $cur<$till && $chY == $curY && ($curM-$chM)==1 || ($curM-$chM)<0 || User::isMaster()):?>
                        <ul class="dropdown-menu editIcons">
                            <li><?= Html::a('<i class="fa fa-pencil-square-o fa-lg text-warning" aria-hidden="true"></i>', '#', ['lessonId' => $actions['lesson_id'], 'user_id' => $actions['id'], 'id' => 'lesson-edit'])?></li>
                            <li><?= Html::a('<i class="fa fa-trash-o fa-lg text-danger" aria-hidden="true"></i>', '#', [
                                    'lessonId' => $actions['lesson_id'],
                                    'lessonDate' => date('d-m-Y', $actions['lesson_start']),
                                    'lessonTime' => date('H:i', $actions['lesson_start']).' - '.date('H:i', $actions['lesson_finish']),
                                    'lessonTeacher' => $actions['first_name'].' '.$actions['last_name'],
                                    'lessonType' => $actions['instr_name'],
                                    'lessonIcon' => $actions['icon'],
                                    'id' => 'lesson-delete'
                                ])?></li>
                            <li><button aria-hidden="true" data-dismiss="alert" class="close" type="button" style="line-height: 26px;">×</button></li>
                        </ul>
                    <?php endif;?>
                </div>
            <?php if($count == $qnt && $count > 3 && $whtsh == 'month'): ?>
                </div>
            <?php endif; ?>
        <?php endforeach;?>
    <?php endif;?>
<!-- END LESSON -->
</li>

<!--  ---------------------------------------------------------------------------------------------------------------  -->
                                        <?php
                                        if ($_day_of_the_week == 7) {
                                            echo '</ul>';
                                        }
                                    }
                                endforeach;
                            }
                        endforeach;
                    }
                endforeach;
            }
        endforeach;
        ?>
    </div>
    <div class="filterCalendar">
        <?php
        $format = <<< SCRIPT
function format(lesson) {
return lesson.text;
}
SCRIPT;
        $escape = new JsExpression("function(m) { return m; }");
        $this->registerJs($format, View::POS_HEAD);

        $filter = ActiveForm::begin([
            'id' => 'filter-form',
//                        'layout' => 'horizontal',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'action' => 'calendar',
            'fieldConfig' => [
//                            'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
//                            'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]);?>
        <div>
            <div class="col-sm-3">
                <?php $filterForm->statusFilter = Yii::$app->session->get('statusFilter');?>
                <?= $filter->field($filterForm, 'statusFilter')->widget(Select2::className(), [
                    'name' => 'status_filter',
                    'data' => $status_list,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => ['placeholder' => 'Select a Status ...', 'multiple' => true],
                    'pluginOptions' => [
                        'templateResult' => new JsExpression('format'),
                        'templateSelection' => new JsExpression('format'),
                        'escapeMarkup' => $escape,
                        'allowClear' => true,
                        'closeOnSelect' =>false,
                    ],
                ])->label(false);?>
            </div>
            <div class="<?= User::isMaster() ? 'col-sm-3' : 'col-sm-6'?>">
                <?php $filterForm->lessonFilter = Yii::$app->session->get('lessonFilter');?>
                <?= $filter->field($filterForm, 'lessonFilter')->widget(Select2::className(), [
                    'name' => 'lesson_filter',
                    'data' => $lesson_list,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => ['placeholder' => 'Select a Lesson ...', 'multiple' => true],
                    'pluginOptions' => [
                        'templateResult' => new JsExpression('format'),
                        'templateSelection' => new JsExpression('format'),
                        'escapeMarkup' => $escape,
                        'allowClear' => true,
                        'closeOnSelect' =>false,
                    ],
                ])->label(false);?>
            </div>
            <?php if (User::isMaster()):?>
                <div class="col-sm-3">
                    <?php $filterForm->teacherFilter = Yii::$app->session->get('teacherFilter');?>
                    <?= $filter->field($filterForm, 'teacherFilter')->widget(Select2::className(), [
                        'name' => 'teacherFilter',
                        'data' => $user_list,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => true,
                        'options' => ['placeholder' => 'Select a User ...', 'multiple' => true],
                        'pluginOptions' => [
                            'templateResult' => new JsExpression('format'),
                            'templateSelection' => new JsExpression('format'),
                            'escapeMarkup' => $escape,
                            'allowClear' => true,
                            'closeOnSelect' =>false,
                        ],
                    ])->label(false);?>
                </div>
            <?php endif;?>
            <div class="form-group col-sm-1">
                <?= Html::submitButton('Apply', ['class' => 'btn btn-success', 'id' => 'filter-confirm'])?>
            </div>
            <div class="col-sm-1">
                <?= Html::a('Clear', 'profile', ['class' => 'btn btn-warning', 'id' => 'filter-clear'])?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
