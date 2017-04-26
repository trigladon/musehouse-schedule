<?php

use app\components\CalendarWidget;
?>

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