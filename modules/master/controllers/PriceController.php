<?php

namespace app\modules\master\controllers;

use app\modules\master\models\Instrument;
use app\modules\master\models\StudentTeacherPricing;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\master\forms\PricingForm;
use app\models\User;

class PriceController extends Controller
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
        $priceForm = new PricingForm();
        $studentList = User::studentsListFull();
        $teacherList = User::teachersListFull();
        $priorityList = [];
        $lessonList = Instrument::lessonListProfile();

        return $this->render('index', [
            'pricingForm' => $priceForm,
            'studentList' => $studentList,
            'teacherList' => $teacherList,
            'priorityList' => $priorityList,
            'lessonList' => $lessonList
        ]);
    }

}