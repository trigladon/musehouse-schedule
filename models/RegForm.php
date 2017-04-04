<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 02.09.16
 * Time: 18:13
 */

namespace app\models;


use yii\base\Model;
use Yii;


class RegForm extends Model
{
    public $first_name;
    public $last_name;
    public $password;
    public $status;


    public function rules()
    {
        return [
            [['first_name', 'last_name', 'password'],'filter', 'filter' => 'trim'],
            [['first_name', 'last_name', 'password'],'required'],
            [['first_name', 'last_name'], 'string', 'min' => 4, 'max' => 255],
            ['first_name', 'validateUsername', 'skipOnEmpty' => false],
            ['last_name', 'validateUsername', 'skipOnEmpty' => false],
            ['password', 'string', 'min' => 6, 'max' => 255],
            ['password', 'validatePassword', 'skipOnEmpty' => false],
            ['status', 'in', 'range' => [
                User::STATUS_NOT_ACTIVE,
                User::STATUS_ACTIVE,
            ]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'password' => 'password',
        ];
    }

// validation
    public function validateUsername ($attr){

        $attrLength = strlen($this->$attr);
        if (!ctype_alnum($this->$attr) || $attrLength <= 4 || $attrLength >= 255){
            $this->addError($attr, 'Поле должно содержать только буквы и цифры. Длина текста не менее 4х символов.');
        }

    }

    public function validatePassword ($attr){

        $attrLength = strlen($this->$attr);
        if (!ctype_alnum($this->$attr) || $attrLength <= 6 || $attrLength >= 255){
            $this->addError($attr, 'Поле должно содержать только буквы и цифры. Длина текста не менее 6 символов.');
        }

    }
// end validation

    public function reg($key)
    {
        /* @var $user User */
        $user = User::findBySecretKey($key);

        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->removeSecretKey();
        $user->status = User::STATUS_ACTIVE;

        return $user->save()?$user:false;

    }

    public function sendActivationEmail($user)
    {
        Yii::$app->mailer->compose('registration/activationEmail.php', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo('bdionis@gmail.com')
            ->setSubject('Activation letter')
            ->send();
    }

}