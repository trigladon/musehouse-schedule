<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 4:13
 */

namespace app\modules\master\controllers;

use yii\web\Controller;
use app\modules\master\forms\InviteUserForm;
use app\models\User;
use Yii;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $model = new InviteUserForm();
        $user_list = User::userActivationList();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->sendInvitation();
                return $this->refresh();
            }
        }

        if (isset($_GET['deleteUser'])){
            User::deleteUserById($_GET['deleteUser']);
            return $this->redirect('/master/users');
        }

        if (isset($_GET['resendUserLetter'])){
            InviteUserForm::resendInvitation($_GET['resendUserLetter']);
            return $this->redirect('/master/users');
        }

        return $this->render('index', [
            'model' => $model,
            'user_list' => $user_list,
        ]);
    }
}