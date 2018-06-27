<?php

use app\models\User;
use app\modules\master\models\Instrument;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;

/** @var array $reportData */
/** @var array $lessonStatuses */

ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

$this->title = 'Report';

$get = Yii::$app->request->get();
$escape = new JsExpression("function(m) { return m; }");
$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;
?>

<h1><?=$this->title ?></h1>

<div class="row">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => Url::toRoute('/master/report'),
    ]); ?>
    <div class="col-md-2 col-xs-12">
        <?= Html::input('text', 'filterDate', Yii::$app->request->get('filterDate')?:'', [
            'id' => 'filterDate',
            'placeholder' => 'Chose month',
            'class' => 'form-control'
        ])?>
    </div>
    <div class="col-md-2 col-xs-12">
        <?= Select2::widget([
            'name' => 'filterTeacher',
            'data' => $teacherList,
            'value' => Yii::$app->request->get('filterTeacher')?:'',
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['placeholder' => 'Choose the Teacher'],
            'pluginOptions' => [
                'allowClear' => true
            ]
        ]) ?>
    </div>
    <div class="col-md-2 col-xs-12">
        <?= Select2::widget([
            'name' => 'filterStudent',
            'data' => $studentList,
            'theme' => Select2::THEME_BOOTSTRAP,
            'value' => Yii::$app->request->get('filterStudent')?:'',
            'options' => ['placeholder' => 'Choose the Student'],
            'pluginOptions' => [
                'allowClear' => true
            ]
        ]) ?>
    </div>
    <div class="col-md-2 col-xs-12">
        <?= Select2::widget([
            'name' => 'filterLesson',
            'data' => $lessonList,
            'value' => Yii::$app->request->get('filterLesson')?:'',
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['placeholder' => 'Choose the Instrument'],
            'pluginOptions' => [
                'escapeMarkup' => $escape,
                'allowClear' => true
            ]
        ]) ?>
    </div>
    <div class="col-md-3 text-left">
        <?= Html::submitButton('Apply filter <i class="fa fa-search"></i>', ['id' => 'button-search', 'class' => 'btn btn-success']) ?>
        <a href="/master/report" class="btn btn-primary" role="button">Clear filter</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php if ($reportData): ?>
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
                $target = $lesson['target']['qnt'];
                ?>
                <?php foreach ($lesson as $key => $lessonData):
                    if ($key != 'target'):

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

                    $lessonsList .= '<div class="row lessonListReport" style="color: '.$lessonStatuses[$lessonData['lessonStatus']]['color'].';">';
                    $lessonsList .= '<div class="col-md-2 col-xs-4 text-center">'.date('d-m-Y', strtotime($lessonData['lessonStartTime'])).'</div>';
                    $lessonsList .= '<div class="col-md-2 col-xs-4 text-center">'.$lessonData['lessonLength'].' minutes</div>';
                    $lessonsList .= '<div class="col-md-1 col-xs-4 text-center">'.$lessonData['businessType'].'</div>';
                    $lessonsList .= '<div class="col-md-7 col-xs-12">';
                        $lessonsList .= '<div class="col-md-3 col-xs-3 text-center'.$moneyColorReport_s.'">'.$lessonData[$impLetter.'C'].' <span class="currencyRepLessonList">CZK</span></div>';
                        $lessonsList .= '<div class="col-md-3 col-xs-3 text-center'.$moneyColorReport_m.'">'.$lessonData[$impLetter.'T'].' <span class="currencyRepLessonList">CZK</span></div>';
                        $lessonsList .= '<div class="col-md-3 col-xs-3 text-center'.$moneyColorReport_l.'">'.$lessonData[$impLetter.'F'].' <span class="currencyRepLessonList">CZK</span></div>';
                        $lessonsList .= '<div class="col-md-3 col-xs-3 text-center">'.$lessonData[$impLetter.'F'].'</div>';
                    $lessonsList .= '</div>';
                    $lessonsList .= '</div>';
                    $lessonsList .= '<hr style="margin: 5px 0;">';
                    endif;
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
                <?php $percentOfTarget = number_format(($finished/$target)*100, 2, '.', ' ') ?>

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
<?php else: ?>
    <h3>No data found...</h3>
<?php endif; ?>