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


?>

<div class="table-responsive calendar_act">
    <table class="table table-bordered">
        <caption>
            <div class="col-md-1">
                <div class="btn btn-info" onclick="onClickMonth('<?=$monthToShow['currentDate']?>', ' -1 <?=$whtsh?>', '<?=$whtsh?>')">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </div>
            </div>
            <div class="col-md-10">
                <div class="col-md-4"></div>
                <div class="col-md-4 monthName" onclick="onClickMonth('<?=$monthToShow['toShow']?>', '', 'month')">
                    <?=$monthToShow['month'].' '.$monthToShow['year']?>
                </div>
                <div class="col-md-4" id="infoDiv" currentDate="<?=$monthToShow['currentDate']?>" whtsh="<?=$whtsh?>"></div>
            </div>
            <div class="col-md-1">
                <div class="btn btn-info" onclick="onClickMonth('<?=$monthToShow['currentDate']?>', ' +1 <?=$whtsh?>', '<?=$whtsh?>')">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </div>
            </div>

        </caption>
        <thead>
            <tr>
                <th>Week</th>
                <?php foreach ($weekDaysToShow as $value):?>
                    <th class="weekName <?= $value == 'Saturday'||$value == 'Sunday'?'holiday':'';?>"><?=$value?></th>
                <?php endforeach;?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="8">
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
                        <div class="col-md-3">
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
                        <div class="<?= User::isMaster() ? 'col-md-3' : 'col-md-6'?>">
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
                        <div class="col-md-3">
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
                        <div class="form-group col-md-1">
                            <?= Html::submitButton('Apply', ['class' => 'btn btn-success', 'id' => 'filter-confirm'])?>
                        </div>
                        <div class="col-md-1">
                            <?= Html::a('Clear', 'profile', ['class' => 'btn btn-warning', 'id' => 'filter-clear'])?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            $_year = '';
            $_week = '';
            $_month = '';
            $_day = '';
            $_day_of_the_week = '';



            switch ($whtsh){
                case 'week':
                    $dayStyle = 'height: 690px;'; break;
                case 'day':
                    $dayStyle = 'height: 690px; width: 1085px;'; break; //1085px width
                default:
                    $dayStyle = ''; break;
            };


            foreach ($calendarArray as $year){

                global $_year, $_week, $_month, $_day, $_day_of_the_week;

                if (is_string($year)){
                    $_year = $year;
                }else{
                    foreach ($year['week'] as $week){
                        if (is_string($week)){
                            $_week = $week;
                            if ($_day_of_the_week == '7' || !$_day_of_the_week){
                                echo '<tr><td class="weekCell" style="vertical-align: middle;" onclick="onClickMonth('.$_week.', \'\', \'week\')"><span class="weekstyle">'.$_week.'</span></td>';
                            }
                        }else{
                            foreach ($week['month'] as $month) {
                                if (is_string($month)) {
                                    $_month = $month;
                                }else{
                                    foreach ($month['day'] as $day) {
                                        if (is_string($day)) {
                                            $_day = $day;
                                        }else{
                                            $_day_of_the_week = $day['day_of_the_week'];

                                            $holiday = $_day_of_the_week >= '6'?' holiday':'';
                                            if (date('n j Y', mktime(0, 0, 0, $_month, $_day, $_year)) == date('n j Y')){
                                                $today = ' today';
                                            }else{
                                                $today = '';
                                            }
                                            if ($whtsh !== 'week'){
                                                if  (date('F', mktime(0, 0, 0, $_month, $_day, $_year)) == $monthToShow['month']){
                                                    $currentMonth = '';
                                                }else{
                                                    $currentMonth = ' notCurrentMonth';
                                                }
                                            }else{
                                                $currentMonth = '';
                                            }

                                            if ($whtsh == 'day'){
                                                $mothToDate = "<span class='monthToDate'>".$monthToShow['month']." ".$monthToShow['year']."</span>";
                                                $dayStyleToDate = 'style="width: 150px"';
                                            }else{
                                                $mothToDate = '';
                                                $dayStyleToDate='';
                                            }
                                        ?>

<td style="padding: 0; <?=$dayStyle?>" class="dayCell <?="$holiday $today $currentMonth"?>">
    <div style="width: 100%; height: 100%">
        <div class="dayLineBar">
            <div class="dayBar" <?=$dayStyleToDate?> onclick="onClickMonth('<?=$_year?>-<?=$_month?>-<?=$_day?>', '', 'day')">
                <strong class="weekstyle"><?=$_day?></strong><?=$mothToDate?>
            </div>
            <div style="margin-right: 5px; float: right">
                <div class="addAction">
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
                </div>
            </div>
            <div style="margin-right: 30px">
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
        <div>
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
                <div id="showMoreActions<?=$actions['lesson_id']?>" class="showMoreActions" onclick="showLayer('<?=$actions['lesson_id']?>')">
                    <i id="iconChange<?=$actions['lesson_id']?>" class="fa fa-caret-down iconShowHide" aria-hidden="true"></i>
                </div>
            <?php endif;?>
            <?php if($count == 4 && $whtsh == 'month'): ?>
            <div class="hidenDiv" id = "<?=$actions['lesson_id']?>">
            <?php endif; ?>
                <div class="dropdown">
                    <div class="dayAction img-rounded dropdown-toggle" data-toggle="dropdown" <?=$dayActionStatus?>>
                        <div class="timeField">
                            <div class="timeAction">
                                <?=date('H:i', $actions['lesson_start'])?>
                            </div>
                            <div class="timeAction">
                                <?=date('H:i', $actions['lesson_finish'])?>
                            </div>
                        </div>
                        <div class="lessonIconAction">
                            <?php if ($actions['icon'] == NULL):?>
                            <i class="fa fa-question fa-lg icon_reg_action img-thumbnail" aria-hidden="true" style="width: 22px;height: 22px;vertical-align: middle;color: #78909c;padding-top: 3px"></i>
                            <?php else:?>
                            <img src="/images/icons/<?=$actions['icon']?>" class="icon_reg_action img-thumbnail" alt="<?=$actions['instr_name']?>" title="<?=$actions['instr_name']?>">
                            <?php endif;?>
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
                    <ul class="dropdown-menu editIcons">
                        <li><?= Html::a('<i class="fa fa-pencil-square-o fa-lg text-warning" aria-hidden="true"></i>', '#', ['lessonId' => $actions['lesson_id'], 'id' => 'lesson-edit'])?></li>
                        <li><?= Html::a('<i class="fa fa-trash-o fa-lg text-danger" aria-hidden="true"></i>', '#', ['lessonId' => $actions['lesson_id'], 'id' => 'lesson-delete'])?></li>
                        <li><button aria-hidden="true" data-dismiss="alert" class="close" type="button" style="line-height: 26px;margin-left: 4px;">×</button></li>
                    </ul>
                </div>
            <?php if($count == $qnt): ?>
            </div>
            <?php endif; ?>

            <?php endforeach;?>
        <?php endif;?>
        </div>
    </div>
</td>
<!--                                                    --><?php
                                                    if ($_day_of_the_week == 7){
                                                        echo '</tr>';
                                                    }
//                                                }
//                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            ?>
        </tbody>
    </table>
</div>

<div class="results"></div>

<div>


</div>