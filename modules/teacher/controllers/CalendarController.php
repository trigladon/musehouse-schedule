<?php

/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 5:06
 */
namespace app\modules\teacher\controllers;

use app\modules\master\models\Instrument;
use app\modules\master\models\Statusschedule;
use app\modules\teacher\forms\AddLessonForm;
use yii\bootstrap\ActiveForm;
use yii\web\Controller;
use Yii;
use app\modules\teacher\helpers\Calendar;
use app\modules\master\models\Userschedule;
use yii\web\Response;


class CalendarController extends Controller
{

    public function actionIndex()
    {

        $modelAddLesson = new AddLessonForm();
        $listUserLessons = Instrument::lessonListUser();
        $status_list = Statusschedule::statusList();

        if (Yii::$app->request->isAjax){

            $request = Yii::$app->getRequest();
            if ($request->isPost && $modelAddLesson->load(Yii::$app->request->post())) {
                $ajaxValidate = ActiveForm::validate($modelAddLesson);
                Yii::$app->response->format = Response::FORMAT_JSON;
                if(count($ajaxValidate)>0){
                    return['success' => 0, 'validate'=>$ajaxValidate];
                }
                return ['success' => 1, 'validate'=>$ajaxValidate, 'result'=> $modelAddLesson->regLesson()];
            }

            $data = Yii::$app->request->post();
//            var_dump($data);
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

//            var_dump($monthToShow);

            return Yii::$app->controller->renderAjax('_calendar', [
                'calendarArray' => $calendarArray,
                'monthToShow' => $monthToShow,
                'whtsh' => $whtsh,
                'weekDaysToShow' => $weekDaysToShow,
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
        ]);
    }
}