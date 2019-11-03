<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 4:13
 */

namespace app\modules\master\controllers;

use Yii;
use yii\web\Response;
use yii\filters\AccessControl;

use app\models\User;
use app\models\AuthItem;
use app\controllers\BaseController;
use app\modules\master\forms\UserForm;
use app\modules\master\forms\StudentAddForm;
use app\modules\master\forms\TeacherBusinessTypeForm;
use app\modules\master\forms\UserUpdateForm;
use app\modules\master\models\TeacherBusinessType;
use app\modules\master\models\Instrument;


class UsersController extends BaseController
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
        $userForm = new UserForm();
        $userUpdate = new UserUpdateForm();
        $studentAddForm = new StudentAddForm();
        $businessTypeForm = new TeacherBusinessTypeForm();
        $roleUserIds = AuthItem::getGroupRolesAndUserIds();

        # Save user by role
        if ($userForm->load($this->getRequest()->post())) {
            if ($userForm->validate()) {
                $user = $userForm->save();
                try{
                    $userForm->sendInvitation($user);
                    $this->setSuccessFlash('Email was sent!');
                } catch (\Swift_TransportException $e) {
                    $this->setErrorFlash('Email wasn\'t sent. Please try one more time in a few seconds from the table of Users !');
                    $this->getResponse()->redirect($this->redirect('/master/users'))->send();
                }

                return $this->refresh();
            }
        }

        # Add new student and add him to teachers
        if ($studentAddForm->load($this->getRequest()->post()) && $studentAddForm->validate()){
            if ($studentAddForm->reg()){
                $this->setSuccessFlash('Student Added');
            }else{
                $this->setErrorFlash($this->getErrorMessage('error'));
            }
            return $this->refresh();
        }

        if ($businessTypeForm->load($this->getRequest()->post()) && $businessTypeForm->validate()){

            if ($businessTypeForm->saveBT()){
                $this->setSuccessFlash('Business type added.');
            }else{
                $this->setErrorFlash($this->getErrorMessage('error'));
            }
            return $this->refresh();
        }

        # Delete user
        if (null !== $this->getRequest()->get('deleteId')){
            if(User::deleteUserById(Yii::$app->request->get('deleteId'))){
                $this->setSuccessFlash('User is Deleted!');
            }else{
                $this->setErrorFlash($this->getErrorMessage('error'));
            }
            return $this->redirect('/master/users');
        }

        # Resend invitation letter
        if (null !== $this->getRequest()->get('resendUserLetter')){
            try{
                $userForm->resendInvitation($this->getRequest()->get('resendUserLetter'));
                $this->setSuccessFlash('Email was sent!');
            }catch (\Swift_TransportException $e){
                $this->setErrorFlash('Email wasn\'t sent. Please try one more time in a few seconds from the table!');
                return $this->redirect('/master/users');
            }
            return $this->redirect('/master/users');
        }

        if($this->getRequest()->isPost && $userUpdate->load($this->getRequest()->post()) && $userUpdate->validate()){
            if ($userUpdate->reg()){
                $this->setSuccessFlash('The changes were successfully applied!');
            }else{
                $this->setErrorFlash($this->getErrorMessage('error'));
            }
            return $this->refresh();
        }

        $user_list = User::find()->with()->andWhere(['!=', 'status', User::STATUS_DELETED])->all();
        $listUserLessons = Instrument::lessonListProfile();
        $role_list = AuthItem::getRoleList();
        $teacherList = User::teacherList();
        $businessTypes = TeacherBusinessType::getBusinessTypeList();

        return $this->render('index', [
            'model' => $userForm,
            'role_user_ids' => $roleUserIds,
            'user_list' => $user_list,
            'userUpdate' => $userUpdate,
            'listUserLessons' => $listUserLessons,
            'role_list' => $role_list,
            'studentAddForm' => $studentAddForm,
            'teacherList' => $teacherList,
            'businessTypeForm' => $businessTypeForm,
            'businessTypes' => $businessTypes,
        ]);
    }

    public function actionDelBt()
    {
        if ($this->getRequest()->isAjax && $this->getRequest()->isPost) {
            $post = $this->getRequest()->post();
            $result = TeacherBusinessType::findOne($post['btId']);
            $this->getResponse()->format = Response::FORMAT_JSON;
            return [
                'success' => $result->delete()
            ];
        }

        $this->goTo404();
    }

    public function actionEditBt()
    {
        if ($this->getRequest()->isAjax && $this->getRequest()->isPost) {
            $post = $this->getRequest()->post();
            $result = TeacherBusinessType::findOne($post['btId']);
            $this->getResponse()->format = Response::FORMAT_JSON;
            return [
                'result' => $result,
                'date_from' => $result->getDateFrom()
            ];
        }

        $this->goTo404();
    }
}