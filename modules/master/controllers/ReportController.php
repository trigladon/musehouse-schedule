<?php

namespace app\modules\master\controllers;

use app\modules\master\models\Statusschedule;
use app\modules\master\models\Userschedule;
use yii\web\Controller;
use yii\filters\AccessControl;

class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Master'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $reportData = Userschedule::getReportData();
        $lessonStatuses = Statusschedule::getLessonStatuses();

        return $this->render('index', [
            'reportData' => $reportData,
            'lessonStatuses' => $lessonStatuses
        ]);
    }
}