<?php

use app\models\User;
use app\modules\master\models\Instrument;

/** @var array $reportData */
/** @var array $lessonStatuses */

ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

$this->title = 'Report';
?>

<h1><?=$this->title ?></h1>

<?php foreach ($reportData as $teacherId => $student): ?>
<div>
    <div class="text-center text-muted">
        <h3><?=User::getUsernameById($teacherId) ?></h3>
    </div>
    <?php foreach ($student as $studentId => $lessonInstr): ?>
    <div>
        <h4 class="text-center text-info"><?=User::getUsernameById($studentId) ?></h4>
        <div class="row">
            <?php foreach ($lessonInstr as $lessonInstrId => $lesson): ?>
            <div class="col-md-6 lessonLevelReport">
                <div class="text-center" style="font-size: larger; margin-bottom: 5px;"><?=Instrument::getLessonById($lessonInstrId) ?></div>
                <?php
                $finished = 0;
                $failed = 0;
                $planed = 0;
                $freeTime = 0;
                $targeting = 0;
                $lessonsList = '';
                ?>
                <?php foreach ($lesson as $lessonData):

                $moneyColorReport_s = ' text-muted';
                $moneyColorReport_m = ' text-muted';
                $moneyColorReport_l = ' text-muted';

                switch ($lessonData['lessonLength']) {
                    case '45': $impLetter = 's';
                        break;
                    case '60': $impLetter = 'm';
                        break;
                    case '90': $impLetter = 'l';
                        break;
                }

                switch ($lessonData['lessonStatus']) {
                    case 1: $freeTime += 1;break;
                    case 2: $planed += 1;break;
                    case 3:
                        $finished += 1;
                        $targeting += $lessonData['targetQntInMonth'];
                        switch ($impLetter) {
                            case 's': $moneyColorReport_s = ' text-success finishedMoneyRep';break;
                            case 'm': $moneyColorReport_m = ' text-success finishedMoneyRep';break;
                            case 'l': $moneyColorReport_l = ' text-success finishedMoneyRep';break;
                        }
                        break;
                    case 4: $failed += 1;break;
                }

                $finished ? $target = number_format($targeting/$finished, 2, '.', ' ') : $target = 0;

                $lessonsList .= '<div class="row lessonListReport" style="color: '.$lessonStatuses[$lessonData['lessonStatus']]['color'].';">';
                $lessonsList .= '<div class="col-md-2 col-xs-6 text-center">'.date('d-m-Y', strtotime($lessonData['lessonStartTime'])).'</div>';
                $lessonsList .= '<div class="col-md-2 col-xs-6 text-center">'.$lessonData['lessonLength'].' minutes</div>';
                $lessonsList .= '<div class="col-md-1 col-xs-6 text-center">'.$lessonData['businessType'].'</div>';
                $lessonsList .= '<div class="col-md-7 col-xs-6">';
                    $lessonsList .= '<div class="col-md-3 col-xs-6 text-center'.$moneyColorReport_s.'">'.$lessonData[$impLetter.'C'].' <span class="currencyRepLessonList">CZK</span></div>';
                    $lessonsList .= '<div class="col-md-3 col-xs-6 text-center'.$moneyColorReport_m.'">'.$lessonData[$impLetter.'T'].' <span class="currencyRepLessonList">CZK</span></div>';
                    $lessonsList .= '<div class="col-md-3 col-xs-6 text-center'.$moneyColorReport_l.'">'.$lessonData[$impLetter.'F'].' <span class="currencyRepLessonList">CZK</span></div>';
                    $lessonsList .= '<div class="col-md-3 col-xs-6 text-center">'.$lessonData[$impLetter.'F'].'</div>';
                $lessonsList .= '</div>';
                $lessonsList .= '</div>';
                endforeach; ?>
                <div class="col-md-3 col-xs-6">
                    <?=$lessonStatuses[3]['name'] ?>
                    <span class="badge" style="background-color: <?=$lessonStatuses[3]['color'] ?>"><?=$finished ?></span>
                </div>
                <div class="col-md-3 col-xs-6">
                    <?=$lessonStatuses[4]['name'] ?>
                    <span class="badge" style="background-color: <?=$lessonStatuses[4]['color'] ?>"><?=$failed ?></span>
                </div>
                <div class="col-md-3 col-xs-6">
                    <?=$lessonStatuses[2]['name'] ?>
                    <span class="badge" style="background-color: <?=$lessonStatuses[2]['color'] ?>"><?=$planed ?></span>
                </div>
                <div class="col-md-3 col-xs-6">
                    <?=$lessonStatuses[1]['name'] ?>
                    <span class="badge" style="background-color: <?=$lessonStatuses[1]['color'] ?>"><?=$freeTime ?></span>
                </div>
                <?php $target ? $percentOfTarget = number_format(($finished/$target)*100, 2, '.', ' ') : $percentOfTarget = 0 ?>

                <div class="progress col-xs-12" style="margin: 10px 0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 4em; width: <?=$percentOfTarget ?>%;">
                        <?=$percentOfTarget ?>%
                    </div>
                </div>
                <?=$lessonsList ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <hr>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>
<?php var_dump($reportData) ?>