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
use yii\db\Query;


class AddLessonForm extends Model
{

    public $lesson_start;
    public $lesson_finish;
    public $lesson_start_repeat;
    public $comment;
    public $user_id;
    public $student_id;
    public $instricon_id;
    public $statusschedule_id;
    public $action_date;
    public $id;

    public function rules()
    {
        return [
            [['lesson_start', 'lesson_finish', 'statusschedule_id', 'action_date', 'instricon_id'], 'required'],
            [['user_id', 'instricon_id', 'statusschedule_id', 'student_id'], 'integer'],
            [['instricon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instricon_id' => 'id']],
            [['statusschedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statusschedule::className(), 'targetAttribute' => ['statusschedule_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['comment'], 'string'],
            [['action_date'], 'string'],
            [['id'], 'number'],
            ['lesson_finish', 'validateLessonFinish', 'skipOnEmpty' => false],
            ['student_id', 'validateStudent', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'lesson_start' => 'Start Time',
            'lesson_finish' => 'Finish Time',
            'statusschedule_id' => 'Status',
            'instricon_id' => 'Type of the Lesson',
            'comment' => 'Comments',
            'action_date' => 'Date',
            'student_id' => 'Student',
        ];
    }

    public function validateStudent(){
        if ($this->statusschedule_id != 1 && !$this->student_id){
            $this->addError('student_id', 'Student need to be assigned!');
        }
    }

    public function validateLessonFinish(){
        $action_date_save = explode('-', $this->action_date);
        $lesson_start_save = explode(':', $this->lesson_start);
        $lesson_finish_save= explode(':', $this->lesson_finish);

        $lesson_start = mktime($lesson_start_save[0], $lesson_start_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]);
        $lesson_finish = mktime($lesson_finish_save[0], $lesson_finish_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]);

        if ($lesson_start > $lesson_finish){
            $this->addError('lesson_finish', 'Finish Time shouldn\'t be earlier than Start Time');
        }

        $mergeTime = Userschedule::find()
            ->andWhere(['between', 'lesson_start', $lesson_start+1, $lesson_finish-1])
            ->orWhere(['between', 'lesson_finish', $lesson_start+1, $lesson_finish-1])
            ->andWhere(['user_id' => $this->user_id ? $this->user_id : Yii::$app->user->id])
            ->andWhere(['!=', 'id', $this->id])
            ->one();

        $prevLesson = Userschedule::find()
            ->andWhere(['<=', 'lesson_start', $lesson_start])
            ->andWhere(['user_id' => $this->user_id ? $this->user_id : Yii::$app->user->id])
            ->andWhere(['!=', 'id', $this->id])
            ->orderBy('lesson_start DESC')
            ->limit(1)
            ->one();

        /* @var $prevLesson Userschedule */

        if ($mergeTime){
            /* @var $mergeTime Userschedule */
            $date = date('d-m-Y', $mergeTime->lesson_start);
            $start = date('H:i', $mergeTime->lesson_start);
            $finish = date('H:i', $mergeTime->lesson_finish);

            $mergeLesson = $date.' '.$start.'-'.$finish;

            $this->addError('lesson_finish', 'This time is not free. You have already had the lesson fo this time: '.$mergeLesson);
            $this->addError('lesson_start', '');
        }elseif ($prevLesson && $lesson_start > $prevLesson->lesson_start && $lesson_start < $prevLesson->lesson_finish){
            $date = date('d-m-Y', $prevLesson->lesson_start);
            $start = date('H:i', $prevLesson->lesson_start);
            $finish = date('H:i', $prevLesson->lesson_finish);

            $mergeLesson = $date.' '.$start.'-'.$finish;

            $this->addError('lesson_finish', 'This time is not free. You have already had the lesson fo this time: '.$mergeLesson);
            $this->addError('lesson_start', '');
        }
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
                'student_id' => $this->student_id,
                'instricon_id' => $this->instricon_id,
                'statusschedule_id' => $this->statusschedule_id,
                'user_id' => $this->user_id,
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
            $lesson->student_id = $this->student_id;
            $lesson->instricon_id = $this->instricon_id;
            $lesson->statusschedule_id = $this->statusschedule_id;
            $lesson->user_id = Yii::$app->user->identity->getId();

            $lesson->save()?true:false;
        }

    }

}