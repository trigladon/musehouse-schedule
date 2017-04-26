<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 21.04.17
 * Time: 0:18
 */

namespace app\modules\teacher\controllers;


use app\modules\teacher\helpers\Statistics;
use yii\web\Controller;
use Yii;

class StatisticsController extends Controller
{
    public function actionIndex(){

        $statisticsData = Statistics::userStatistics();
        $monthsToShow = Statistics::monthsToShow();
//        '2017-01-01'
        if (Yii::$app->request->isAjax) {


//            var_dump(Yii::$app->request->post());
            $data = Yii::$app->request->post();
//            var_dump($data);
            $toShow = explode(':', $data['currentDate']);
            $toChange = explode(':', $data['changes']);
            $toShow = $toShow[0];
            $toChange = $toChange[0];

            $statisticsData = Statistics::userStatistics($toShow, $toChange);
            $monthsToShow = Statistics::monthsToShow($toShow, $toChange);

//            var_dump($statisticsData);
//            var_dump($monthsToShow);

            return Yii::$app->controller->renderAjax('_statistics', [
                'statisticsData' => $statisticsData,
                'monthsToShow' => $monthsToShow,
            ]);
        }

        return $this->render('index', [
            'statisticsData' => $statisticsData,
            'monthsToShow' => $monthsToShow,
        ]);
    }

}