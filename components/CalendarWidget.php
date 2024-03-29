<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 28.03.17
 * Time: 20:42
 */

namespace app\components;


use app\models\User;
use yii\base\Widget;


class CalendarWidget extends Widget
{
    public $calendarArray;
    public $monthToShow;
    public $modelAddLesson;
    public $userList;
    public $status_list;
    public $whtsh;
    public $weekDaysToShow;
    public $filterForm;
    public $lesson_list;
    public $user_list;
    public $students_listFull;

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
            'filterForm' => $this->filterForm,
            'status_list' => $this->status_list,
            'lesson_list' => $this->lesson_list,
            'user_list' => $this->user_list,
            'students_listFull' => $this->students_listFull = User::studentsListFull(),
        ]);
    }
}