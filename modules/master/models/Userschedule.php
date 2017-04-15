<?php

namespace app\modules\master\models;

use Yii;
use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use DateTime;

/**
 * This is the model class for table "userschedule".
 *
 * @property integer $id
 * @property string $lesson_start
 * @property string $lesson_finish
 * @property string $comment
 * @property integer $user_id
 * @property integer $instricon_id
 * @property integer $statusschedule_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Schedinstricon[] $schedinstricons
 * @property Instrument $instricon
 * @property Statusschedule $statusschedule
 * @property User $user
 */
class Userschedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userschedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lesson_start', 'lesson_finish', 'created_at', 'updated_at'], 'safe'],
            [['user_id', 'instricon_id', 'statusschedule_id'], 'integer'],
            [['instricon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instricon_id' => 'id']],
            [['statusschedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statusschedule::className(), 'targetAttribute' => ['statusschedule_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['comment'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lesson_start' => 'Lesson Start',
            'lesson_finish' => 'Lesson Finish',
            'user_id' => 'User ID',
            'instricon_id' => 'Instricon ID',
            'statusschedule_id' => 'Statusschedule ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedinstricons()
    {
        return $this->hasMany(Schedinstricon::className(), ['userschedule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstricon()
    {
        return $this->hasOne(Instrument::className(), ['id' => 'instricon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusschedule()
    {
        return $this->hasOne(Statusschedule::className(), ['id' => 'statusschedule_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    public static function getScheduleList($toShow, $daysToShow, $whtsh){

        switch ($whtsh) {
            case "month":
                $month = new DateTime($toShow);
                $month->modify('first day of this month');
                $month->modify('last Monday');
                $startDay = $month->format('U')-1;
                $month->modify('+'.$daysToShow.' days');
                $endDay = $month->format('U');
                break;
            case 'week':
                $month = new DateTime($toShow);
                $startDay = $month->format('U')-1;
                $month->modify('+'.$daysToShow.' days');
                $endDay = $month->format('U');
                break;
            case 'day':
                $month = new DateTime($toShow);
                $startDay = $month->format('U')-1;
                $month->modify('+'.$daysToShow.' days');
                $endDay = $month->format('U');
                break;
            default:
                $startDay = 0;
                $endDay = 0;
        }

        $rows = (new Query())
            ->select(['ussch.id', 'ussch.lesson_start', 'ussch.lesson_finish', 'ussch.`comment`',
                'inst.icon', 'inst.instr_name',
                'stsch.name', 'stsch.color',
                'u.id', 'u.first_name', 'u.last_name'])
            ->from('userschedule ussch')
            ->leftJoin('instricon inst', 'ussch.instricon_id = inst.id')
            ->leftJoin('statusschedule stsch', 'ussch.statusschedule_id = stsch.id')
            ->leftJoin('user u', 'ussch.user_id = u.id')
            ->orderBy('ussch.lesson_start')
            ->where(['between', 'ussch.lesson_start', $startDay, $endDay])
            ->all();

        if ($rows){
            foreach ($rows as $value){
                $scheduleList[date('Y', $value['lesson_start'])]['week'][date('W', $value['lesson_start'])]['month'][date('m', $value['lesson_start'])]['day'][date('d', $value['lesson_start'])]['actionList'][] = $value;
            };
        }else{
            $scheduleList = [];
        }

        return $scheduleList;
    }
}