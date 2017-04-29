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
use yii\filters\AccessControl;

class UsersController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['master'],
                    ],
                ],
            ],
        ];
    }

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

        if (null !== Yii::$app->request->get('deleteId')){
            User::deleteUserById(Yii::$app->request->get('deleteId'));
            return $this->redirect('/master/users');
        }

        if (null !== Yii::$app->request->get('resendUserLetter')){
            InviteUserForm::resendInvitation(Yii::$app->request->get('resendUserLetter'));
            return $this->redirect('/master/users');
        }

        return $this->render('index', [
            'model' => $model,
            'user_list' => $user_list,
        ]);
    }
}