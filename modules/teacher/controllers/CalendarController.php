<?php

/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 5:06
 */
namespace app\modules\teacher\controllers;

use yii\web\Controller;
use Yii;
use app\modules\teacher\models\Calendar;

class CalendarController extends Controller
{

    public function actionIndex()
    {
        if (Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $toShow = explode(':', $data['currentDate']);
            $toChange = explode(':', $data['changes']);
            $toShow = $toShow[0];
            $toChange = $toChange[0];

            $monthToShow = Calendar::monthToShow($toShow, $toChange);
            $calendarArray = Calendar::calendarArray($monthToShow['toShow']);

            return Yii::$app->controller->renderPartial('_calendar', [
                'calendarArray' => $calendarArray,
                'monthToShow' => $monthToShow,
            ]);

        }else{
            $toShow = '';
            $toChange = '';
        }

        $monthToShow = Calendar::monthToShow($toShow, $toChange);
        $calendarArray = Calendar::calendarArray($monthToShow['toShow']);

        return $this->render('index', [
            'calendarArray' => $calendarArray,
            'monthToShow' => $monthToShow,
        ]);
    }
}