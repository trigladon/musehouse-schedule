<?php

namespace app\controllers;

use app\models\RegForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
//use app\models\ContactForm;
use app\models\SendRecoveryEmailForm;
use app\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\helpers\Html;
use yii\helpers\Url;
//use app\models\AccountActivation;
use app\models\InviteUserForm;
use app\models\Calendar;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionRegistration()
    {

        $model = new RegForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($user = $model->reg($_GET['key']))
            {
                if (Yii::$app->getUser()->login($user))
                {
                    Yii::$app->session->setFlash('reg_succ', 'You where successfully registered. Please Login.');
                    return $this->goHome();
                }
            }else{
                Yii::$app->session->setFlash('reg_error', 'The error appeared during registration');
                return $this->refresh();
            }

        }

        if (!isset($_GET['key'])) {
            Yii::$app->session->setFlash('error_key', 'Please apply for a Registration letter first.');
            return $this->goHome();
        }elseif (User::findBySecretKey($_GET['key'])==null){
            Yii::$app->session->setFlash('error_key_not_found', 'Wrong key. Please, apply for a new Registration letter.');
            return $this->goHome();
        }else {
            if (User::isSecretKeyExpire($_GET['key'])) {
                return $this->render('registration', [
                    'model' => $model,
                    'key' => $_GET['key'],
                ]);
            } else {
                Yii::$app->session->setFlash('error_time_expired', 'Time for registration is expired. Please, apply for a new Registration letter.');
                return $this->goHome();
            }
        }
    }

    public function actionAccountActivation($key)
    {
        try {
            $user = new AccountActivation($key);
        }catch (InvalidParamException $e){
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($user->activateAccount()){
            Yii::$app->session->setFlash('success', 'Activation of user '.$user->getUsername().' passed successfully.');
        }else{
            Yii::$app->session->setFlash('error', 'Activation ERROR.');
        }

        return $this->redirect(Url::to(['site/login']));
    }

    public function actionSendRecoveryEmail()
    {
        $model = new SendRecoveryEmailForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->sendEmail())
                {
                    Yii::$app->getSession()->setFlash('warning', 'Check your email box');
                    return $this->goHome();
                }else{
                    Yii::$app->getSession()->setFlash('error', 'The password can\'t be reset');
                }
            }
        }

        return $this->render('sendRecoveryEmail', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($key)
    {
        try{
            $model = new ResetPasswordForm($key);
        }catch (InvalidParamException $e){
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->getSession()->setFlash('warning', 'The password has been changed!');
                return $this->redirect(['/site/login']);
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }



    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
