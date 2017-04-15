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


class InstrumentController extends Controller
{
    public function actionIndex()
    {

        $model = new LessonForm();
        $modelUpdate = new LessonUpdateForm();

        $list = Instrument::lessonListUser();
        $list2 = Instrument::lessonListUser2();


        if ($modelUpdate->load(Yii::$app->request->post()) && $modelUpdate->validate()){
            $modelUpdate->updateLesson();
            return $this->refresh();
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
            'list' => $list,
            'list2' => $list2,
        ]);
    }

}