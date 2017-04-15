<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 28.03.17
 * Time: 20:42
 */

namespace app\components;


use yii\base\Widget;


class CalendarWidget extends Widget
{
    public $calendarArray;
    public $monthToShow;
    public $modelAddLesson;
    public $listUserLessons;
    public $status_list;
    public $whtsh;
    public $weekDaysToShow;

    public function init()
    {


    }

    public function run()
    {
        return $this->render('calendar', [
            'calendarArray' => $this->calendarArray,
            'monthToShow' => $this->monthToShow,
            'whtsh' => $this->whtsh,
            'weekDaysToShow' => $this->weekDaysToShow,
        ]);
    }
}