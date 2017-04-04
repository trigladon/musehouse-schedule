<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 4:13
 */

namespace app\modules\master\controllers;

use yii\web\Controller;


class InstrumentController extends Controller
{
    public function actionIndex()
    {

        return $this->render('index');
    }
}