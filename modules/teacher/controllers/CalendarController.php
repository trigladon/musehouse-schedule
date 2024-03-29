<?php

/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 5:06
 */
namespace app\modules\teacher\controllers;

use app\models\User;
use app\modules\master\forms\CalendarFilterForm;
use app\modules\master\models\Instrument;
use app\modules\master\models\Statusschedule;
use app\modules\teacher\forms\AddLessonForm;
use yii\web\Controller;
use Yii;
use app\modules\teacher\helpers\Calendar;
use app\modules\master\models\Userschedule;
use yii\web\Response;
use yii\widgets\ActiveForm;


class CalendarController extends Controller
{

    public function actionIndex()
    {
        $modelAddLesson = new AddLessonForm();
        $filterForm = new CalendarFilterForm();
        $user_id = '';
        $listUserLessons = Instrument::lessonListUser($user_id);
        $studentsList = User::studentsList();
        $status_list = Statusschedule::statusList();
        $lesson_list = Instrument::lessonListDropBox();
        $user_list = User::userListDropBox();
        $lessonLength = Userschedule::getLessonsTypeArray();

        if (Yii::$app->request->isAjax){

            $request = Yii::$app->getRequest();
            if ($request->isPost && $modelAddLesson->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => (int)$modelAddLesson->validate(),
                    'validate' => ActiveForm::validate($modelAddLesson),
                    'result' => $modelAddLesson->errors ? null : $modelAddLesson->regLesson()
                ];
            }

            if ($request->isPost && $request->post('clearFilter')){
                Yii::$app->response->format = Response::FORMAT_JSON;
                CalendarFilterForm::clearSessionData();
                return [
                    'success' => 1,
                ];
            }

            if ($request->isPost && $request->post('deleteLesson')){
                Yii::$app->response->format = Response::FORMAT_JSON;
                Userschedule::deleteLessonById(Yii::$app->request->post('deleteLesson'));
                return [
                    'success' => 1,
                ];
            }

            if ($request->isPost && $request->post('updateLesson')){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $lessonToUpdate = Userschedule::lessonToUpdate(Yii::$app->request->post('updateLesson'));
                $listUserLessons = Instrument::lessonListUserAjax(Yii::$app->request->post('user_id'));
                $studentsListAjax = User::studentsListAjax(Yii::$app->request->post('user_id'));
                $lessonToUpdate['lessonList'] = $listUserLessons;
                $lessonToUpdate['studentsList'] = $studentsListAjax;
                $lessonToUpdate['lessonLength'] = Userschedule::getLessonsTypeArray(true);
                return $lessonToUpdate;
            }

            if ($request->isPost && $filterForm->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => (int)$filterForm->validate(),
                    'validate' => $filterForm->errors,
                    'result' => $filterForm->errors ? null : $filterForm->addSessionData(),
                ];
            }

            $data = Yii::$app->request->post();

            $toShow = explode(':', $data['currentDate']);
            $toChange = explode(':', $data['changes']);
            $whtsh = explode(':', $data['whtsh']);
            $toShow = $toShow[0];
            $toChange = $toChange[0];
            $whtsh = $whtsh[0];

            $monthToShow = Calendar::monthToShow($toShow, $toChange, $whtsh);

            $calendarArray = Calendar::calendarArray($monthToShow['toShow'], $monthToShow['daysToShow'], $whtsh);
            $actionList = Userschedule::getScheduleList($monthToShow['toShow'], $monthToShow['daysToShow'], $whtsh);
            $calendarArray = array_replace_recursive($calendarArray, $actionList);
            $weekDaysToShow = Calendar::weekDaysToShow($monthToShow['toShow'], $whtsh);

            return Yii::$app->controller->renderAjax('_calendar', [
                'calendarArray' => $calendarArray,
                'monthToShow' => $monthToShow,
                'whtsh' => $whtsh,
                'weekDaysToShow' => $weekDaysToShow,
                'filterForm' => $filterForm,
                'status_list' => $status_list,
                'lesson_list' => $lesson_list,
                'user_list' => $user_list,
                'studentsList' => $studentsList,
                'lessonLength' => $lessonLength
            ]);

        }else{
            $toShow = '';
            $toChange = '';
            $whtsh = 'month';
        }

        $monthToShow = Calendar::monthToShow($toShow, $toChange, $whtsh);
        $calendarArray = Calendar::calendarArray($monthToShow['toShow'], $monthToShow['daysToShow'], $whtsh);
        $actionList = Userschedule::getScheduleList($monthToShow['toShow'], $monthToShow['daysToShow'], $whtsh);
        $calendarArray = array_replace_recursive($calendarArray, $actionList);
        $weekDaysToShow = Calendar::weekDaysToShow($monthToShow['toShow'], $whtsh);

        return $this->render('index', [
            'calendarArray' => $calendarArray,
            'monthToShow' => $monthToShow,
            'modelAddLesson' => $modelAddLesson,
            'listUserLessons' => $listUserLessons,
            'status_list' => $status_list,
            'whtsh' => $whtsh,
            'weekDaysToShow' => $weekDaysToShow,
            'filterForm' => $filterForm,
            'lesson_list' => $lesson_list,
            'user_list' => $user_list,
            'studentsList' => $studentsList,
            'lessonLength' => $lessonLength
        ]);
    }
}