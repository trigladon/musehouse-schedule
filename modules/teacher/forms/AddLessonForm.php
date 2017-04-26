<?php

/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 10.04.17
 * Time: 15:43
 */

namespace app\modules\teacher\forms;

use app\modules\master\models\Userschedule;
use yii\base\Model;
use app\modules\master\models\Instrument;
use app\modules\master\models\Statusschedule;
use app\models\User;
use Yii;


class AddLessonForm extends Model
{

    public $lesson_start;
    public $lesson_finish;
    public $comment;
    public $user_id;
    public $instricon_id;
    public $statusschedule_id;
    public $action_date;
    public $id;

    public function rules()
    {
        return [
            [['lesson_start', 'lesson_finish', 'statusschedule_id', 'action_date'], 'required'],
            [['user_id', 'instricon_id', 'statusschedule_id'], 'integer'],
            [['instricon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instricon_id' => 'id']],
            [['statusschedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statusschedule::className(), 'targetAttribute' => ['statusschedule_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['comment'], 'string'],
            [['action_date'], 'string'],
            [['id'], 'number'],
        ];
    }

    public function regLesson(){

        $action_date_save = explode('-', $this->action_date);
        $lesson_start_save = explode(':', $this->lesson_start);
        $lesson_finish_save= explode(':', $this->lesson_finish);

        if ($this->id){
            if (Yii::$app->db->createCommand()->update('userschedule', [
                'lesson_start' => mktime($lesson_start_save[0], $lesson_start_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]),
                'lesson_finish' => mktime($lesson_finish_save[0], $lesson_finish_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]),
                'comment' => $this->comment,
                'instricon_id' => $this->instricon_id,
                'statusschedule_id' => $this->statusschedule_id,
                'user_id' => Yii::$app->user->identity->getId(),
            ],
            'id ='.$this->id)->execute()){
                return true;
            }else{
                return false;
            }
        }else{
            $lesson = new Userschedule();

            $lesson->lesson_start = mktime($lesson_start_save[0], $lesson_start_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]);
            $lesson->lesson_finish = mktime($lesson_finish_save[0], $lesson_finish_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]);
            $lesson->comment = $this->comment;
            $lesson->instricon_id = $this->instricon_id;
            $lesson->statusschedule_id = $this->statusschedule_id;
            $lesson->user_id = Yii::$app->user->identity->getId();

            $lesson->save()?true:false;
        }

    }

}