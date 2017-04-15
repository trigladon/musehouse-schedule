<?php

use app\components\CalendarWidget;
?>

<div id="mainCalendar">
    <?=CalendarWidget::widget([
        'calendarArray' => $calendarArray,
        'monthToShow' => $monthToShow,
        'whtsh' => $whtsh,
        'weekDaysToShow' => $weekDaysToShow,
    ])?>
</div>