<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 09.04.17
 * Time: 16:06
 */

namespace app\modules\teacher\controllers;


use app\modules\master\forms\PasswordUpdateForm;
use app\modules\master\forms\UserUpdateForm;
use app\modules\master\models\Instrument;
use yii\web\Controller;
use Yii;
use app\models\User;

class ProfileController extends Controller
{
    public function actionIndex()
    {

        $userUpdateForm = new UserUpdateForm();
        $passwordUpdateForm = new PasswordUpdateForm();

        $user = User::findOne(Yii::$app->user->id);
        $listUserLessons = Instrument::lessonListProfile();


        if(Yii::$app->request->isPost && $userUpdateForm->load(Yii::$app->request->post()) && $userUpdateForm->validate()){
            if ($userUpdateForm->reg()){
                Yii::$app->session->setFlash('Success', 'The changes were successfully applied!');
            }else{
                Yii::$app->session->setFlash('Error', 'Something went wrong, please, contact you Administrator!');
            }
            return $this->refresh();
//            var_dump(Yii::$app->request->post());
        }

        if(Yii::$app->request->isPost && $passwordUpdateForm->load(Yii::$app->request->post()) && $passwordUpdateForm->validate()){
            if ($passwordUpdateForm->reg()){
                Yii::$app->session->setFlash('Success', 'Password was successfully changed!');
            }else{
                Yii::$app->session->setFlash('Error', 'Something went wrong, please, check the passwords you entered and try one more time!');
            }
            return $this->refresh();
//            var_dump(Yii::$app->request->post());
        }

        return $this->render('index', [
            'user' => $user,
            'userUpdateForm' => $userUpdateForm,
            'listUserLessons' => $listUserLessons,
            'passwordUpdateForm' => $passwordUpdateForm,
        ]);
    }
}