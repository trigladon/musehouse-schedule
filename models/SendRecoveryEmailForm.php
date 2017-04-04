<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 21.03.17
 * Time: 18:03
 */

namespace app\models;

use Yii;
use yii\base\Model;

class SendRecoveryEmailForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::className(),
                'filter' => [
                    'status' => User::STATUS_ACTIVE,
                ],
                'message' => 'Entered email is not registered.',
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }

    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user)
        {
            $user->generateSecretKey();
            if ($user->save())
            {
                Yii::$app->mailer->compose('registration/password_recovery.php', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo('bdionis@gmail.com')
                    ->setSubject('Password Recovery')
                    ->send();
            }
        }
    }
}