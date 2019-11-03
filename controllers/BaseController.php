<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;


abstract class BaseController extends Controller
{

    private $httpErrors = [
        400 => 'Bad Request',
        404 => 'Page not found',
        500 => 'Internal Server Error',
    ];

    private $defaultMessages = [
        'error' => 'Something went wrong, please, contact you Administrator!',
        'success' => ''
    ];


    protected function goTo404()
    {
        throw new HttpException(404, $this->getHttpErrorMessage(404));
    }

    protected function getHttpErrorMessage($code)
    {

        return \Yii::t('app', isset($this->httpErrors[$code]) ? $this->httpErrors[$code] : $code);
    }

    protected function getErrorMessage($type)
    {

        return \Yii::t('app', isset($this->defaultMessages[$type]) ? $this->defaultMessages[$type] : $type);
    }
    /**
     * @return \yii\web\Request
     */
    protected function getRequest()
    {
        return \Yii::$app->getRequest();
    }

    protected function redirectTo($url, $scheme = false)
    {
        return Url::to($url, $scheme);
    }

    /**
     * @return \yii\web\Response
     */
    protected function getResponse()
    {
        return \Yii::$app->getResponse();
    }

    protected function getUser()
    {
        return \Yii::$app->getUser();
    }

    public function setSuccessFlash($text)
    {
        \Yii::$app->session->setFlash('Success', $text);
    }

    public function setErrorFlash($text)
    {
        \Yii::$app->session->setFlash('Error', $text);
    }

    public function setNoticeFlash($text)
    {
        \Yii::$app->session->setFlash('Notice', $text);
    }

    public function setWarningFlash($text)
    {
        \Yii::$app->session->setFlash('Warning', $text);
    }

}