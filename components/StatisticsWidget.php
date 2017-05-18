<?php

/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 25.04.17
 * Time: 18:57
 */

namespace app\components;


use yii\base\Widget;

class StatisticsWidget extends Widget
{
    public $statisticsData;
    public $monthsToShow;
    public $lessonPerTeacher;
    public $teachersList;
    public $studentsList;


    public function init()
    {


    }

    public function run()
    {
        return $this->render('statistics', [
            'statisticsData' => $this->statisticsData,
            'monthsToShow' => $this->monthsToShow,
            'lessonPerTeacher' => $this->lessonPerTeacher,
            'teachersList' => $this->teachersList,
            'studentsList' => $this->studentsList,
        ]);
    }
}