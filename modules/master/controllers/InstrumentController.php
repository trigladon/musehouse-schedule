<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 4:13
 */

namespace app\modules\master\controllers;

use app\modules\master\forms\LessonForm;
use app\modules\master\forms\LessonUpdateForm;
use app\modules\master\models\Instrument;
use yii\web\UploadedFile;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;


class InstrumentController extends Controller
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

        $model = new LessonForm();
        $modelUpdate = new LessonUpdateForm();


        if ($modelUpdate->load(Yii::$app->request->post()) && $modelUpdate->validate()){
            $modelUpdate->icon = UploadedFile::getInstance($modelUpdate, 'icon');
            $modelUpdate->updateLesson();
            return $this->refresh();
//            var_dump(Yii::$app->request->post());
//            var_dump($modelUpdate);
        }

        $lessonsList = Instrument::lessonList();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->icon = UploadedFile::getInstance($model, 'icon');
            $model->upload();

            return $this->refresh();
        }

        if (null !== Yii::$app->request->get('deleteId')){
            Instrument::deleteLessonById(Yii::$app->request->get('deleteId'));
            return $this->redirect('/master/instrument');
        }



        return $this->render('index', [
            'model' => $model,
            'lessonsList' => $lessonsList,
            'modelUpdate' => $modelUpdate,
        ]);
    }

}