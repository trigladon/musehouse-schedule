<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 09.04.17
 * Time: 16:06
 */

namespace app\modules\teacher\controllers;


use yii\web\Controller;
use Yii;

class ProfileController extends Controller
{
    public function actionIndex()
    {

        return $this->render('index');
    }
}