<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 22.03.17
 * Time: 13:08
 */

namespace app\modules\master\forms;


use app\models\AuthItem;
use yii\base\Model;
use Yii;
use yii\helpers\Url;
use app\models\User;
use app\models\AuthItemChild;


class UserForm extends Model
{
# todo need refactoring
    public $email;
    public $role;
    public $phone;

    public function rules()
    {
        return [
            [['email', 'role'], 'required'],
            ['email', 'email'],
            ['phone', 'string', 'max' => 30],
            ['email', 'unique',
                'targetClass' => User::className(),
                'message' => 'This email has already been registered.'],
            [['role'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['role' => 'name']],
        ];
    }

    public function save()
    {
        $user = new User();
        $user->email = $this->email;
        $user->status = User::STATUS_NOT_ACTIVE;
        if ($this->phone) $user->phone = $this->phone;
        $user->letter_status = User::STATUS_LETTER_NOT_SENT;
        $user->generateSecretKey();
        $user->save();

        if ($user->id){
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole($this->role);
            $auth->assign($authorRole, $user->getId());
        }

        return $user;
    }

    public function sendInvitation(User $user)
    {
        $this->registrationEmail($user);
        $user->letterSendStatus();
    }

    public function registrationEmail(User $user)
    {
        Yii::$app->mailer->compose('registration/registrationEmail.php', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Registration Email')
            ->send();
    }

    public function resendInvitation($id){
        /* @var $user User */
        $user = User::findIdentity($id);
        $user->generateSecretKey();
        $this->registrationEmail($user);
        $user->letterSendStatus();
    }

}