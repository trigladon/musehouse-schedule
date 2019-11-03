<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 09.04.17
 * Time: 16:06
 */

namespace app\modules\teacher\controllers;


use Yii;

use app\models\User;
use app\controllers\BaseController;
use app\modules\master\models\Instrument;
use app\modules\master\forms\UserUpdateForm;
use app\modules\master\forms\PasswordUpdateForm;


class ProfileController extends BaseController
{
    public function actionIndex()
    {

        $userUpdateForm = new UserUpdateForm();
        $passwordUpdateForm = new PasswordUpdateForm();

        $user = User::findOne(Yii::$app->user->id);
        $listUserLessons = Instrument::lessonListProfile();


        if($this->getRequest()->isPost && $userUpdateForm->load($this->getRequest()->post()) && $userUpdateForm->validate()){
            if ($userUpdateForm->reg()){
                $this->setSuccessFlash('The changes were successfully applied!');
            }else{
                $this->setErrorFlash('Something went wrong, please, contact you Administrator!');
            }
            return $this->refresh();
        }

        if($this->getRequest()->isPost && $passwordUpdateForm->load($this->getRequest()->post()) && $passwordUpdateForm->validate()){
            if ($passwordUpdateForm->reg()){
                $this->setSuccessFlash('Password was successfully changed!');
            }else{
                $this->setErrorFlash('Something went wrong, please, check the passwords you entered and try one more time!');
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'user' => $user,
            'userUpdateForm' => $userUpdateForm,
            'listUserLessons' => $listUserLessons,
            'passwordUpdateForm' => $passwordUpdateForm,
        ]);
    }
}