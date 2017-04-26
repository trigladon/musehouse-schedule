<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 21.04.17
 * Time: 0:21
 */

ini_set('xdebug.var_display_max_depth', 15);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

use yii\helpers\Html;
use app\components\StatisticsWidget;

$this->title = 'Statistics';

?>

<div class="teacher-statistics">
    <h1><?= Html::encode($this->title) ?></h1>

    <div id="mainStatistics">

        <?php echo StatisticsWidget::widget([
            'statisticsData' => $statisticsData,
            'monthsToShow' => $monthsToShow,
        ])?>

    </div>

</div>
