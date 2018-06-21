<?php

namespace app\modules\master\controllers;

use app\modules\master\models\Instrument;
use app\modules\master\models\StudentTeacherPricing;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\master\forms\PricingForm;
use app\models\User;
use Yii;
use yii\web\Response;

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
        $lessonList = Instrument::lessonListProfile();
        $unsetPriceLessons = StudentTeacherPricing::getUnsetPriceLessons();

        if ($priceForm->load(Yii::$app->request->post()) && $priceForm->validate()) {
            if ($priceForm->savePrices()){
                Yii::$app->session->setFlash('Success', 'Data saved.');
            }else{
                Yii::$app->session->setFlash('Error', 'Something went wrong!');
            }
            return $this->refresh();
        }

        $itemsPerPage = 10;

        $provider = new ArrayDataProvider([
            'allModels' => StudentTeacherPricing::getPricesFilter(),
            'pagination' => [
                'pageSize' => $itemsPerPage,
            ],
            'sort' => [
                'attributes' => ['id'],
            ]
        ]);

        $pages = new Pagination([
            'totalCount' => $provider->getTotalCount(),
            'pageSize' => $itemsPerPage,
            'route' => '/pricing'
        ]);

        return $this->render('index', [
            'pricingForm' => $priceForm,
            'studentList' => $studentList,
            'teacherList' => $teacherList,
            'lessonList' => $lessonList,
            'unsetPriceLessons' => $unsetPriceLessons,
            'allSetPrices' => $provider->getModels(),
            'pages' => $pages
        ]);
    }

    public function actionDelPrice()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->getRequest();
            if ($request->isPost) {
                $post = Yii::$app->request->post();
                $result = StudentTeacherPricing::findOne($post['id']);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => $result->delete()
                ];
            }
        }
    }

    public function actionEditPrice()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->getRequest();
            if ($request->isPost) {
                $post = Yii::$app->request->post();
                $result = StudentTeacherPricing::findOne($post['id']);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'result' => $result,
                    'date_from' => $result->getDateFrom()
                ];
            }
        }
    }

}