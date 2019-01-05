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
use DateTime;


class AddLessonForm extends Model
{

    public $lesson_start;
    public $lesson_finish;
    public $lesson_length;
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
            [['lesson_start', 'lesson_length', 'statusschedule_id', 'action_date', 'instricon_id'], 'required'],
            [['user_id', 'instricon_id', 'statusschedule_id', 'student_id'], 'integer'],
            [['instricon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instricon_id' => 'id']],
            [['statusschedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statusschedule::className(), 'targetAttribute' => ['statusschedule_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['comment'], 'string'],
            [['action_date'], 'string'],
            [['id'], 'number'],
            ['lesson_length', 'validateLessonLength', 'skipOnEmpty' => false],
            ['student_id', 'validateStudent', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'lesson_start' => 'Start Time',
            'lesson_finish' => 'Finish Time',
            'lesson_length' => 'Lesson Length',
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

    public function validateLessonLength(){

        $lessonLengthArray = [Userschedule::LESSON_SHORT, Userschedule::LESSON_MIDDLE, Userschedule::LESSON_LONG];

        if (!in_array($this->lesson_length, $lessonLengthArray)) {
            $this->addError('lesson_length', 'Finish Time shouldn\'t be earlier than Start Time');
        }


        $action_date_save = explode('-', $this->action_date);
        $lesson_start_save = explode(':', $this->lesson_start);
//        $lesson_finish_save= explode(':', $this->lesson_finish);

        $lesson_start = mktime($lesson_start_save[0], $lesson_start_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]);
//        $lesson_finish = mktime($lesson_finish_save[0], $lesson_finish_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]);
        $lesson_finish = $lesson_start + $this->lesson_length*60;

        if ($lesson_start > $lesson_finish){
            $this->addError('lesson_length', 'Finish Time shouldn\'t be earlier than Start Time');
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

            $this->addError('lesson_length', 'This time is not free. You have already had the lesson fo this time: '.$mergeLesson);
            $this->addError('lesson_start', '');
        }elseif ($prevLesson && $lesson_start > $prevLesson->lesson_start && $lesson_start < $prevLesson->lesson_finish){
            $date = date('d-m-Y', $prevLesson->lesson_start);
            $start = date('H:i', $prevLesson->lesson_start);
            $finish = date('H:i', $prevLesson->lesson_finish);

            $mergeLesson = $date.' '.$start.'-'.$finish;

            $this->addError('lesson_length', 'This time is not free. You have already had the lesson fo this time: '.$mergeLesson);
            $this->addError('lesson_start', '');
        }

        $now = new DateTime();
        $cur = $now->format('U');
        $curMY =  $now->format('m-Y');
        $curM =  $now->format('m');
        $curY =  $now->format('Y');
        $curU =  $now->format('U');
        $now->modify('first day of this month midnight');
        $now->modify(Yii::$app->params['lessonEditing']);
        $till = $now->format('U');

        $chMY = date( "m-Y", $lesson_start);
        $chM = date( "m", $lesson_start);
        $chY = date( "Y", $lesson_start);
        $chU = date( "U", $lesson_start);

        if (!($chMY == $curMY || // current month
            $cur<$till && $chY == $curY && ($curM-$chM)==1 ||
            $cur<$till && ($curM-$chM)==-11 && ($curY-$chY)==1 ||
            $chU > $curU ||
            User::isMaster())) {
            $this->addError('action_date', 'You are not allowed to add lesson for this date');
        }
    }

    public function regLesson(){

        $action_date_save = explode('-', $this->action_date);
        $lesson_start_save = explode(':', $this->lesson_start);
//        $lesson_finish_save= explode(':', $this->lesson_finish);
        $lesson_start = mktime($lesson_start_save[0], $lesson_start_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]);
        $lesson_finish = $lesson_start + $this->lesson_length*60;

        if ($this->id){
            if (Yii::$app->db->createCommand()->update('userschedule', [
                'lesson_start' => $lesson_start,
                'lesson_length_type' => $this->lesson_length,
                'lesson_finish' => $lesson_finish,
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

            $lesson->lesson_start = $lesson_start;
            $lesson->lesson_length_type = $this->lesson_length;
            $lesson->lesson_finish = $lesson_finish;
            $lesson->comment = $this->comment;
            $lesson->student_id = $this->student_id;
            $lesson->instricon_id = $this->instricon_id;
            $lesson->statusschedule_id = $this->statusschedule_id;
            $lesson->user_id = Yii::$app->user->identity->getId();

            return $lesson->save()?true:false;
        }

    }

}