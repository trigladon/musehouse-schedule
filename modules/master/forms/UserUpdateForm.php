<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 27.04.17
 * Time: 5:52
 */

namespace app\modules\master\forms;


use app\models\User;
use app\modules\master\models\StudentRel;
use app\modules\master\models\Userinstr;
use yii\base\Model;
use app\modules\master\models\Instrument;


class UserUpdateForm extends Model
{
    public $user_id;
    public $first_name;
    public $last_name;
    public $lessons;
    public $teachers;
    public $role;

    public function rules()
    {
        return [
            [['lessons'], 'each', 'rule' => ['integer']],
            [['teachers'], 'each', 'rule' => ['integer']],
            [['first_name', 'last_name', 'role'], 'filter', 'filter' => 'trim'],
            [['first_name', 'last_name', 'role'], 'string', 'max' => 255],
            [['user_id'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'lessons' => 'Lessons',
        ];
    }

    public function reg(){

        $user = $user = User::findOne($this->user_id);

        if ($this->role != 'Student'){
            $arrayExist = [];
            foreach ($user->getUserLessons() as $lesson){
                $arrayExist[] = $lesson['instricon_id'];
            }

            $arrayChange = $this->lessons;
            !$arrayChange?$arrayChange=[]:$arrayChange;

            $arrayDelete = array_diff($arrayExist, $arrayChange);
            $arrayAdd = array_diff($arrayChange, $arrayExist);

            if(!empty($arrayDelete)){
                foreach ($arrayDelete as $iId){
                    $transaction = Userinstr::getDb()->beginTransaction();
                    try {
                        Userinstr::deleteByUidIid($user->id, $iId);
                        $transaction->commit();
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }
            }

            if(!empty($arrayAdd)){
                foreach ($arrayAdd as $add) {
                    $transaction = Userinstr::getDb()->beginTransaction();
                    try {
                        $userInstr = new Userinstr();
                        $userInstr->user_id = $this->user_id;
                        $userInstr->instricon_id = $add;
                        $userInstr->save();
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }
            }
        }else{
            $arrayExist = [];
            foreach ($user->teachers() as $teacher){
                /* @var $teacher User*/
                $arrayExist[] = $teacher->id;
            }

            $arrayChange = $this->teachers;
            !$arrayChange?$arrayChange=[]:$arrayChange;

            $arrayDelete = array_diff($arrayExist, $arrayChange);
            $arrayAdd = array_diff($arrayChange, $arrayExist);

            if(!empty($arrayDelete)){
                foreach ($arrayDelete as $iId){
                    $transaction = StudentRel::getDb()->beginTransaction();
                    try {
                        StudentRel::deleteByUidTid($user->id, $iId);
                        $transaction->commit();
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }
            }

            if(!empty($arrayAdd)){
                foreach ($arrayAdd as $add) {
                    $transaction = StudentRel::getDb()->beginTransaction();
                    try {
                        $userTeacher = new StudentRel();
                        $userTeacher->student_id = $this->user_id;
                        $userTeacher->teacher_id = $add;
                        $userTeacher->save();
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }
            }
        }


        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;

        return $user->save() ? true : false ;
    }

}