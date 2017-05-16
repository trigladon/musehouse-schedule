<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 4:13
 */

namespace app\modules\master\controllers;

use app\modules\master\forms\UserUpdateForm;
use yii\web\Controller;
use app\modules\master\forms\InviteUserForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use app\modules\master\models\Instrument;

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
                        'roles' => ['Master'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new InviteUserForm();
        $userUpdate = new UserUpdateForm();

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

        if(Yii::$app->request->isPost && $userUpdate->load(Yii::$app->request->post()) && $userUpdate->validate()){
            if ($userUpdate->reg()){
                Yii::$app->session->setFlash('Success', 'The changes were successfully applied!');
            }else{
                Yii::$app->session->setFlash('Error', 'Something went wrong, please, contact you Administrator!');
            }
            return $this->refresh();
//            var_dump(Yii::$app->request->post());
        }

        $user_list = User::find()->all();
        $listUserLessons = Instrument::lessonListProfile();


        return $this->render('index', [
            'model' => $model,
            'user_list' => $user_list,
            'userUpdate' => $userUpdate,
            'listUserLessons' => $listUserLessons,
        ]);
    }
}