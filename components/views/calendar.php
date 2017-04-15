<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 28.03.17
 * Time: 20:43
 */

use yii\helpers\Html;

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
        <div class="dayLine">
            <div class="day" <?=$dayStyleToDate?> onclick="onClickMonth('<?=$_year?>-<?=$_month?>-<?=$_day?>', '', 'day')">
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
            <?php foreach ($day['actionList'] as $actions):
                $dayActionStatus = ' style="background-color: '.$actions['color'].'"';
            ?>
            <div class="dayAction img-rounded" <?=$dayActionStatus?>>
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
            </div>
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

    <?php

    var_dump($calendarArray);
//    $month = new DateTime();
//    $cur = $month->format('W');
//    $ch = 19-$cur;
//    $month->modify('+'.$ch.'week');
//    if ($month->format('N') !== 1){
//        $month->modify('last Monday');
//    }
//    $startDay = $month->format('U');
//    echo $month->format('Y-m-d H:i');
//    $month->modify('+7 days');
//    echo $month->format('Y-m-d H:i');
//    var_dump($endDay = $month->format('U'));
//    var_dump($endDay-1);
    ?>


</div>