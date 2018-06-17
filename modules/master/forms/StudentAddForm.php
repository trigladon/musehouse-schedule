<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 16.05.17
 * Time: 15:58
 */

namespace app\modules\master\forms;

use app\models\User;
use app\models\AuthItem;
use app\modules\master\models\StudentRel;
use yii\base\Model;
use Yii;
use yii\db\Exception;


class StudentAddForm extends Model
{
    public $first_name;
    public $last_name;
    public $teacher;
    public $role = 'Student';
    public $phone;

    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name'], 'filter', 'filter' => 'trim'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 30],
            [['teacher'], 'each', 'rule' => ['integer']],
//            [['teacher'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['teacher' => 'id']],
            [['role'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['role' => 'name']],
        ];
    }

    public static function generateRandomEmail() {
        $length = 5;
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        $randomString .='@mail.ku';

        return $randomString;
    }

    public function reg(){
        $user = new User();

        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = self::generateRandomEmail();
        if ($this->phone) $user->phone = $this->phone;
        $user->setPassword('muse');
        $user->generateAuthKey();
        $user->removeSecretKey();
        $user->status = User::STATUS_STUDENT;
        $user->letter_status = User::STATUS_LETTER_SENT;

        if ($user->save()){
            try{
                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole($this->role);
                $auth->assign($authorRole, $user->getId());

                foreach ($this->teacher as $teacher){
                    $rel = new StudentRel();
                    $rel->student_id = $user->id;
                    $rel->teacher_id = $teacher;
                    $rel->save();
                }

            }catch (Exception $e){
                var_dump($e->getMessage());
            }

            return true;
        }else{
            return false;
        }
    }

}