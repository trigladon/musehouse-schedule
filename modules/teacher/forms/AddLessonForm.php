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

    public function rules()
    {
        return [
            [['lesson_start', 'lesson_finish', 'statusschedule_id'], 'required'],
            [['user_id', 'instricon_id', 'statusschedule_id'], 'integer'],
            [['instricon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instricon_id' => 'id']],
            [['statusschedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statusschedule::className(), 'targetAttribute' => ['statusschedule_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['comment'], 'string'],
            [['action_date'], 'string'],
        ];
    }

    public function regLesson(){

        $lesson = new Userschedule();

        $action_date_save = explode('_', $this->action_date);
        $lesson_start_save = explode(':', $this->lesson_start);
        $lesson_finish_save= explode(':', $this->lesson_finish);

        $lesson->lesson_start = mktime($lesson_start_save[0], $lesson_start_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]);
        $lesson->lesson_finish = mktime($lesson_finish_save[0], $lesson_finish_save[1], 0, $action_date_save[1], $action_date_save[0], $action_date_save[2]);
        $lesson->comment = $this->comment;
        $lesson->instricon_id = $this->instricon_id;
        $lesson->statusschedule_id = $this->statusschedule_id;
        $lesson->user_id = Yii::$app->user->identity->getId();

        $lesson->save()?true:false;
    }

}