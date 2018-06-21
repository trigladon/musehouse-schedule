<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 4:13
 */

namespace app\modules\master\controllers;

use app\models\AuthItem;
use app\modules\master\forms\StudentAddForm;
use app\modules\master\forms\TeacherBusinessTypeForm;
use app\modules\master\forms\UserUpdateForm;
use app\modules\master\models\TeacherBusinessType;
use yii\web\Controller;
use app\modules\master\forms\InviteUserForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use app\modules\master\models\Instrument;
use yii\web\Response;

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
        $studentAddForm = new StudentAddForm();
        $businessTypeForm = new TeacherBusinessTypeForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->sendInvitation();
                return $this->refresh();
            }
        }

        if ($studentAddForm->load(Yii::$app->request->post()) && $studentAddForm->validate()){

            if ($studentAddForm->reg()){
                Yii::$app->session->setFlash('Success', 'Student Added');
            }else{
                Yii::$app->session->setFlash('Error', 'Something went wrong!');
            }
            return $this->refresh();
        }

        if ($businessTypeForm->load(Yii::$app->request->post()) && $businessTypeForm->validate()){

            if ($businessTypeForm->saveBT()){
                Yii::$app->session->setFlash('Success', 'Business type added.');
            }else{
                Yii::$app->session->setFlash('Error', 'Something went wrong!');
            }
            return $this->refresh();
        }

        if (null !== Yii::$app->request->get('deleteId')){
            if(User::deleteUserById(Yii::$app->request->get('deleteId'))){
                Yii::$app->session->setFlash('Success', 'User is Deleted!');
            }else{
                Yii::$app->session->setFlash('Error', 'Something went wrong!');
            }
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
            var_dump(Yii::$app->request->post());
            return $this->refresh();
        }

        $user_list = User::find()->andWhere(['!=', 'status', User::STATUS_DELETED])->all();
        $listUserLessons = Instrument::lessonListProfile();
        $role_list = AuthItem::getRoleList();
        $teacherList = User::teacherList();
        $businessTypes = \app\modules\master\models\TeacherBusinessType::getBusinessTypeList();

        return $this->render('index', [
            'model' => $model,
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
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->getRequest();
            if ($request->isPost) {
                $post = Yii::$app->request->post();
                $result = TeacherBusinessType::findOne($post['btId']);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => $result->delete()
                ];
            }
        }
    }

    public function actionEditBt()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->getRequest();
            if ($request->isPost) {
                $post = Yii::$app->request->post();
                $result = TeacherBusinessType::findOne($post['btId']);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'result' => $result,
                    'date_from' => $result->getDateFrom()
                ];
            }
        }
    }
}