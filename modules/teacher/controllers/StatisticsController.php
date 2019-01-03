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
use app\models\User;
use kartik\mpdf\Pdf;

class StatisticsController extends Controller
{
    public function actionIndex(){

        $statisticsData = Statistics::userStatistics();
        $monthsToShow = Statistics::monthsToShow();
        $lessonPerTeacher = Statistics::lessonsPerTeacher();
        $teachersList = User::teachersListFull();
        $studentsList = User::studentsListFull();

        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();

            $toShow = explode(':', $data['currentDate']);
            $toChange = explode(':', $data['changes']);
            $toShow = $toShow[0];
            $toChange = $toChange[0];

            $statisticsData = Statistics::userStatistics($toShow, $toChange);
            $monthsToShow = Statistics::monthsToShow($toShow, $toChange);
            $lessonPerTeacher = Statistics::lessonsPerTeacher($toShow, $toChange);

            return Yii::$app->controller->renderAjax('_statistics', [
                'statisticsData' => $statisticsData,
                'monthsToShow' => $monthsToShow,
                'lessonPerTeacher' => $lessonPerTeacher,
                'teachersList' => $teachersList,
                'studentsList' => $studentsList,
            ]);
        }

        return $this->render('index', [
            'statisticsData' => $statisticsData,
            'monthsToShow' => $monthsToShow,
            'lessonPerTeacher' => $lessonPerTeacher,
            'teachersList' => $teachersList,
            'studentsList' => $studentsList,
        ]);
    }

    public function actionStatopdf(){

        $toShow = Yii::$app->request->get('toShow');
        $toChange = '';

        $statisticsData = Statistics::userStatistics($toShow, $toChange);
        $monthsToShow = Statistics::monthsToShow($toShow, $toChange);
        $lessonPerTeacher = Statistics::lessonsPerTeacher($toShow, $toChange);

        $teachersList = User::teachersListFull();
        $studentsList = User::studentsListFull();
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_statistics', [
            'statisticsData' => $statisticsData,
            'monthsToShow' => $monthsToShow,
            'lessonPerTeacher' => $lessonPerTeacher,
            'teachersList' => $teachersList,
            'studentsList' => $studentsList,
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
//            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => 'css/site.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['MuseHouse Statistics'],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

}