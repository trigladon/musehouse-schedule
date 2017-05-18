<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 22.04.17
 * Time: 18:09
 */

namespace app\modules\teacher\helpers;

ini_set('xdebug.var_display_max_depth', 15);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

use app\models\User;
use yii\db\Query;
use Yii;
use DateTime;
use app\modules\master\models\Userschedule;

class Statistics
{
    public static function userStatistics($currentMonth = '', $changes = ''){

        $user = User::findIdentity(Yii::$app->user->id);
        $role = $user->userRole();

        if (!$currentMonth){
            $dateTime = new DateTime("now");
        }else{
            $dateTime = new DateTime($currentMonth);
        }

        $dateTime->modify('first day of this month');
        $dateTime->modify('+2 weeks'.$changes);

        $startMonth = $dateTime->format('U'); // first month to show of 3, for array arranging

        $dateTime->modify('first day of this month');
        $dateTime->modify('midnight');

        $startDay = $dateTime->format('U')-1; // start searching date in timestamp

        $dateTime->modify('+2 weeks');
        $dateTime->modify('last day of this month');
        $dateTime->modify('+1 day');

        $endDay = $dateTime->format('U')-1; // end searching date in timestamp

        // START receiving all statuses
        $queryStatuses = (new Query())
            ->select(['id', 'color', 'name'])
            ->from('statusschedule')
            ->all();

        $statuses = [];
        foreach ($queryStatuses as $value){
            $curentStatus = [
                $value['id'] => [
                    'id' => $value['id'],
                    'color' => $value['color'],
                    'name' => $value['name'],
                    'qnt_lessons' => 0,
                ]
            ];

            $statuses = array_replace_recursive($statuses, $curentStatus);
        }
        // END receiving all statuses

        $monthArray[date('Y', $startMonth)][date('F', $startMonth)]['results'] = $statuses;
        // START receiving all Lessons
        $lessonsQuery = Userschedule::find()
            ->select(['user_id', 'student_id', 'instricon_id', 'statusschedule_id', 'COUNT(*) as summ'])
            ->andWhere(['between', 'lesson_start', $startDay, $endDay]);
        if ($role != 'Master'){
            $lessonsQuery = $lessonsQuery->andWhere(['=', 'user_id', $user->id]);
        }
        $lessonsQuery = $lessonsQuery->groupBy(['user_id', 'student_id', 'instricon_id', 'statusschedule_id'])
            ->orderBy('instricon_id')
            ->asArray()
            ->all();

        $lessonList = [];
        $lessonPerTeacher = [];
        foreach ($lessonsQuery as $lessons){
            $lessonPerTeacher[$lessons['user_id']][$lessons['instricon_id']] = $statuses;
        }

        foreach ($lessonsQuery as $lessons){
            $lessonList[$lessons['user_id']]['students'][$lessons['student_id']] = $lessonPerTeacher[$lessons['user_id']];
        }
        foreach ($lessonsQuery as $lessons){
            $lessonList[$lessons['user_id']]['students'][$lessons['student_id']][$lessons['instricon_id']][$lessons['statusschedule_id']]['qnt_lessons'] = $lessons['summ'];
        }
        // END receiving all Lessons
        // ----------------------------------
        $totalMonthQuery = Userschedule::find()
            ->select(['user_id', 'statusschedule_id', 'COUNT(*) as summ'])
            ->andWhere(['between', 'lesson_start', $startDay, $endDay]);

        if ($role != 'Master'){
            $totalMonthQuery = $totalMonthQuery->andWhere(['=', 'user_id', $user->id]);
        }

        $totalMonthQuery = $totalMonthQuery->groupBy(['user_id', 'statusschedule_id'])
            ->orderBy('statusschedule_id')
            ->asArray()
            ->all();

        $totalMonthRes = [];
        foreach ($totalMonthQuery as $qnt){
            $totalMonthRes[$qnt['user_id']] = $statuses;
        }
        foreach ($totalMonthQuery as $qnt){
            $totalMonthRes[$qnt['user_id']][$qnt['statusschedule_id']]['qnt_lessons'] = $qnt['summ'];
        }
        foreach ($totalMonthQuery as $qnt){
            $lessonList[$qnt['user_id']]['monthResult'] = $totalMonthRes[$qnt['user_id']];
        }
        // ----------------------------------

        $totalPerLessonQ = Userschedule::find()
            ->select(['user_id', 'instricon_id', 'statusschedule_id', 'COUNT(*) as summ'])
            ->andWhere(['between', 'lesson_start', $startDay, $endDay]);

        if ($role != 'Master'){
            $totalPerLessonQ = $totalPerLessonQ->andWhere(['=', 'user_id', $user->id]);
        }

        $totalPerLessonQ = $totalPerLessonQ->groupBy(['user_id', 'instricon_id', 'statusschedule_id'])
            ->orderBy('instricon_id')
            ->asArray()
            ->all();

        $totalPerLesson = [];
        foreach ($totalPerLessonQ as $lessons){
            $totalPerLesson[$lessons['user_id']][$lessons['instricon_id']] = $statuses;
        }

        foreach ($totalPerLessonQ as $lessons){
            $totalPerLesson[$lessons['user_id']][$lessons['instricon_id']][$lessons['statusschedule_id']]['qnt_lessons'] = $lessons['summ'];
        }

        foreach ($totalPerLessonQ as $qnt){
            $lessonList[$qnt['user_id']]['monthResultPerLesson'] = $totalPerLesson[$qnt['user_id']];
        }

        // ----------------------------------

        return $lessonList;
    }

    public static function lessonsPerTeacher($currentMonth = '', $changes = ''){

        $user = User::findIdentity(Yii::$app->user->id);
        $role = $user->userRole();

        if (!$currentMonth){
            $dateTime = new DateTime("now");
        }else{
            $dateTime = new DateTime($currentMonth);
        }

        $dateTime->modify('first day of this month');
        $dateTime->modify('+2 weeks'.$changes);

        $dateTime->modify('first day of this month');
        $dateTime->modify('midnight');

        $startDay = $dateTime->format('U')-1; // start searching date in timestamp

        $dateTime->modify('+2 weeks');
        $dateTime->modify('last day of this month');
        $dateTime->modify('+1 day');

        $endDay = $dateTime->format('U')-1; // end searching date in timestamp

        $lessonsQuery = Userschedule::find()
            ->select(['user_id', 'instricon_id', 'instricon.icon as icon', 'instricon.instr_name as name'])
            ->leftJoin('instricon', 'instricon.id = instricon_id')
            ->andWhere(['between', 'lesson_start', $startDay, $endDay]);

        if ($role != 'Master'){
            $lessonsQuery = $lessonsQuery->andWhere(['=', 'user_id', $user->id]);
        }

        $lessonsQuery = $lessonsQuery->groupBy(['user_id', 'instricon_id', 'instricon.icon', 'instricon.instr_name'])
            ->asArray()
            ->all();

        $lessonPerTeacher = [];
        foreach ($lessonsQuery as $lessons){
            $lessonPerTeacher[$lessons['user_id']][$lessons['instricon_id']]['name'] = $lessons['name'];
            $lessonPerTeacher[$lessons['user_id']][$lessons['instricon_id']]['icon'] = $lessons['icon'];
        }
        return $lessonPerTeacher;
    }

    public static function monthsToShow($currentMonth = '', $changes = ''){

        if (!$currentMonth){
            $dateTime = new DateTime("now");
        }else{
            $dateTime = new DateTime($currentMonth);
        }

        $dateTime->modify('first day of this month');
        $dateTime->modify('+2 weeks'.$changes);
        $info = $dateTime->format('F Y');
        $currentMonth = $dateTime->format('Y-m-d');


        $monthsArray['currentMonth'] = $currentMonth;
        $monthsArray['info'] = $info;


        return $monthsArray;
    }

}