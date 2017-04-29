<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 27.04.17
 * Time: 7:29
 */

namespace app\modules\master\forms;


use yii\base\Model;
use app\models\User;

class PasswordUpdateForm extends Model
{
    public $oldPass;
    public $newPass;
    public $newPass_repeat;
    public $user_id;

    public function rules()
    {
        return [
            [['newPass', 'newPass_repeat', 'oldPass'],'filter', 'filter' => 'trim'],
            [['newPass', 'newPass_repeat', 'oldPass'],'required'],
            ['newPass', 'string', 'min' => 6, 'max' => 255],
            ['newPass_repeat', 'compare', 'compareAttribute'=>'newPass', 'message'=>"Passwords don't match" ],
            [['user_id'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function reg(){
        $user = $user = User::findOne($this->user_id);

        if($user->validatePassword($this->oldPass)){
            $user->setPassword($this->newPass);
            return $user->save() ? true : false ;
        }else{
            return false;
        }

    }
}