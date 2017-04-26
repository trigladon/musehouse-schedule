<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 09.04.17
 * Time: 16:06
 */

namespace app\modules\teacher\controllers;


use app\modules\master\models\Instrument;
use yii\web\Controller;
use Yii;
use app\modules\teacher\helpers\Profile;

class ProfileController extends Controller
{
    public function actionIndex()
    {
        $userInfo = Profile::userInfo();

        return $this->render('index', [
            'userInfo' => $userInfo,
        ]);
    }
}