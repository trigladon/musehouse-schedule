<?php

namespace app\modules\master\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\web\Response;

use app\models\User;
use app\modules\master\Constants;
use app\controllers\BaseController;
use app\modules\master\forms\PricingForm;
use app\modules\master\models\Instrument;
use app\modules\master\models\StudentTeacherPricing;


class PriceController extends BaseController
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
                $this->setSuccessFlash('Data saved.');
            }else{
                $this->setErrorFlash('Something went wrong!');
            }
            return $this->refresh();
        }

        $provider = new ArrayDataProvider([
            'allModels' => StudentTeacherPricing::getPricesFilter(),
            'pagination' => [
                'pageSize' => Constants::ITEMS_PER_PAGE,
            ],
            'sort' => [
                'attributes' => ['id'],
            ]
        ]);

        $pages = new Pagination([
            'totalCount' => $provider->getTotalCount(),
            'pageSize' => Constants::ITEMS_PER_PAGE,
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
        if ($this->getRequest()->isAjax && $this->getRequest()->isPost) {
            $post = $this->getRequest()->post();
            $result = StudentTeacherPricing::findOne($post['id']);
            $this->getResponse()->format = Response::FORMAT_JSON;
            return [
                'success' => $result->delete()
            ];
        }

        $this->goTo404();
    }

    public function actionEditPrice()
    {
        if ($this->getRequest()->isAjax && $this->getRequest()->isPost) {
            $post = $this->getRequest()->post();
            $result = StudentTeacherPricing::findOne($post['id']);
            $this->getResponse()->format = Response::FORMAT_JSON;
            return [
                'result' => $result,
                'date_from' => $result->getDateFrom()
            ];
        }

        $this->goTo404();
    }

}