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

class InviteUserForm extends Model
{
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
                'message' => 'Эта почта уже занята.'],
            [['role'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['role' => 'name']],
        ];
    }

    public function sendInvitation()
    {
        $user = new User();
        $user->email = $this->email;
        $user->status = User::STATUS_NOT_ACTIVE;
        if ($this->phone) $user->phone = $this->phone;
        $user->generateSecretKey();
        $user->letter_status = User::STATUS_LETTER_NOT_SENT;


        if ($user->save())
        {
            try{
                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole($this->role);
                $auth->assign($authorRole, $user->getId());

                self::registrationEmail($user);
                $user->letter_status = User::STATUS_LETTER_SENT;
                $user->save();
                Yii::$app->session->setFlash('Success', 'Email was sent!');
            }catch (\Swift_TransportException $e){
//                Yii::$app->session->setFlash('error_host_connection', $e->getMessage());
                Yii::$app->session->setFlash('Error', 'Email wasn\'t sent. Please try one more time in a few seconds from the table of Users !');
                Yii::$app->response->redirect(Url::to(['/master/users']))->send();
            }
        }else{
            Yii::$app->session->setFlash('Warning', 'Some problems with User saving.');
            Yii::$app->response->redirect(Url::to(['/master/users']))->send();
        }
    }

    public static function registrationEmail(User $user)
    {
        Yii::$app->mailer->compose('registration/registrationEmail.php', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Registration Email')
            ->send();
    }

    public static function resendInvitation($id){
        /* @var $user User */
        $user = User::findIdentity($id);
        $user->generateSecretKey();

        if ($user->save())
        {
            try{
                self::registrationEmail($user);

                $user->letter_status = User::STATUS_LETTER_SENT;
                $user->save();

            }catch (\Swift_TransportException $e){
//                Yii::$app->session->setFlash('error_host_connection', $e->getMessage());
                Yii::$app->session->setFlash('Error', 'Email wasn\'t sent. Please try one more time in a few seconds from the table!');
                Yii::$app->response->redirect(Url::to(['/master/users']))->send();
            }
            Yii::$app->session->setFlash('Success', 'Email was sent!');
        }else{
            Yii::$app->session->setFlash('Warning', 'Some problems with User saving.');
            Yii::$app->response->redirect(Url::to(['/master/users']))->send();
        }
    }

}