<?php
ini_set('xdebug.var_display_max_depth', 15);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

use app\components\StatisticsWidget;
?>

<div id="mainStatistics">

    <?php echo StatisticsWidget::widget([
        'statisticsData' => $statisticsData,
        'monthsToShow' => $monthsToShow,
    ])?>

</div>