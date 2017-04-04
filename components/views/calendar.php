<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 28.03.17
 * Time: 20:43
 */


ini_set('xdebug.var_display_max_depth', 10);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

//$dataShow = <<< SCRIPT
//
//
//SCRIPT;



?>

<div class="table-responsive calendar_act">
    <table class="table table-bordered">
        <caption>
            <div class="col-md-1">
                <div class="btn btn-info" onclick="onClickMonth(' -1 month')">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </div>
            </div>
            <div class="col-md-10 monthName" id="currentDate" monthToShow="<?=$monthToShow['toShow']?>">
                <?=$monthToShow['month'].' '.$monthToShow['year']?>
            </div>
            <div class="col-md-1">
                <div class="btn btn-info" onclick="onClickMonth(' +1 month')">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </div>
            </div>

        </caption>
        <thead>
            <tr>
                <th>Week</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th class="holiday">Saturday</th>
                <th class="holiday">Sunday</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $_year = '';
            $_week = '';
            $_month = '';
            $_day = '';
            $_day_of_the_week = '';

            $holiday = $_day_of_the_week >= '6'?' active':'';

            foreach ($calendarArray as $year){

                global $_year, $_week, $_month, $_day, $_day_of_the_week;

                if (is_string($year)){
                    $_year = $year;
                }else{
                    foreach ($year['week'] as $week){
                        if (is_string($week)){
                            $_week = $week;
                            if ($_day_of_the_week == '7' || !$_day_of_the_week){
                                echo '<tr><td class="weekCell">'.$_week.'</td>';
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
                                            foreach ($day as $day_of_the_week) {
                                                if (is_string($day_of_the_week)) {
                                                    $_day_of_the_week = $day_of_the_week;
                                                    $holiday = $_day_of_the_week >= '6'?' holiday':'';
                                                    if (date('n j Y', mktime(0, 0, 0, $_month, $_day, $_year)) == date('n j Y')){
                                                        $today = ' today';
                                                    }else{
                                                        $today = '';
                                                    }
                                                    if (date('F', mktime(0, 0, 0, $_month, $_day, $_year)) == $monthToShow['month']){
                                                        $currentMonth = '';
                                                    }else{
                                                        $currentMonth = ' notCurrentMonth';
                                                    }
                                                    echo '<td class="dayCell'."$holiday $today $currentMonth".'">'."$_year $_month $_day $_day_of_the_week $_week".'</td>';
                                                    if ($_day_of_the_week == 7){
                                                        echo '</tr>';
                                                    }
                                                }
                                            }
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

    echo date("F j, Y, g:i a");
    var_dump($monthToShow);
    var_dump($calendarArray);
    ?>

</div>
