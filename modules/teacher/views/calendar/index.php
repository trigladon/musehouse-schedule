<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 5:08
 */

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\CalendarWidget;

?>

<div class="site-calendar">
    <h1><?= Html::encode($this->title) ?></h1>

    <div id="mainCalendar">
        <?=CalendarWidget::widget([
            'calendarArray' => $calendarArray,
            'monthToShow' => $monthToShow,
        ])?>
    </div>

</div>
