<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 21.03.17
 * Time: 18:54
 */

namespace app\models;


use yii\base\InvalidParamException;
use yii\base\Model;

class ResetPasswordForm extends Model
{

    public $password;
    private $_user;

    public function rules()
    {
        return [
            ['password', 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Password',
        ];
    }

    public function __construct($key, array $config = [])
    {
        if (empty($key) || !is_string($key))
        {
            throw new InvalidParamException('The key can\'t be empty');
        }

        $this->_user = User::findBySecretKey($key);

        if (!$this->_user)
        {
            throw new InvalidParamException('Wrong key');
        }

        parent::__construct($config);
    }

    public function resetPassword()
    {
        /* @var $user User */

        $user = $this->_user; //TODO

        $user->setPassword($this->password);
        $user->removeSecretKey();
        return $user->save();
    }

}