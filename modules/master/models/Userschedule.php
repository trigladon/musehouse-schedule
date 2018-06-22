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
 * @property int $lesson_length_type
 * @property string $lesson_finish
 * @property string $comment
 * @property integer $user_id
 * @property integer $student_id
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
class Userschedule extends ActiveRecord
{
    const STATUS_OPEN = 1;
    const STATUS_DONE = 2;
    const STATUS_LOSE = 3;
    const LESSON_SHORT = 45;
    const LESSON_MIDDLE = 60;
    const LESSON_LONG = 90;

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
            [['user_id', 'instricon_id', 'lesson_length_type', 'statusschedule_id', 'student_id'], 'integer'],
            [['instricon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instricon_id' => 'id']],
            [['statusschedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statusschedule::className(), 'targetAttribute' => ['statusschedule_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['student_id' => 'id']],
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
            'lesson_length_type' => 'Lesson Length',
            'lesson_finish' => 'Lesson Finish',
            'user_id' => 'User ID',
            'student_id' => 'Student ID',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(User::className(), ['id' => 'student_id']);
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
        $session = Yii::$app->session;
        $rows = (new Query())
            ->select([
                'ussch.id as lesson_id', 'ussch.lesson_start', 'ussch.lesson_finish',
                '(ussch.lesson_finish-ussch.lesson_start)/60 as length', 'ussch.`comment`', 'ussch.student_id',
                'inst.icon', 'inst.instr_name', 'inst.id',
                'stsch.name', 'stsch.color',
                'u.id', 'u.first_name', 'u.last_name'
            ])
            ->from('userschedule ussch')
            ->leftJoin('instricon inst', 'ussch.instricon_id = inst.id')
            ->leftJoin('statusschedule stsch', 'ussch.statusschedule_id = stsch.id')
            ->leftJoin('user u', 'ussch.user_id = u.id')
            ->orderBy('ussch.lesson_start')
            ->where(['between', 'ussch.lesson_start', $startDay, $endDay]);

        if($session->has('teacherFilter') && $session['teacherFilter'] != null){
            $rows->andWhere(['u.id' => $session->get('teacherFilter')]);
        }
        if($session->has('lessonFilter') && $session['lessonFilter'] != null){
            $rows->andWhere(['inst.id' => $session->get('lessonFilter')]);
        }
        if($session->has('statusFilter') && $session['statusFilter'] != null){
            $rows->andWhere(['stsch.id' => $session->get('statusFilter')]);
        }
        if (!User::isMaster()){
            $rows->andWhere(['ussch.user_id' => Yii::$app->user->id]);
        }

        $rows = $rows->all();

        if ($rows){
            foreach ($rows as $value){
                $scheduleList[date('Y', $value['lesson_start'])]['week'][date('W', $value['lesson_start'])]['month'][date('m', $value['lesson_start'])]['day'][date('d', $value['lesson_start'])]['actionList'][] = $value;
            };
        }else{
            $scheduleList = [];
        }

        return $scheduleList;
    }

    public static function deleteLessonById($id){

        Yii::$app->db->createCommand()->delete('userschedule', 'id = '.$id)->execute();
    }

    public static function lessonToUpdate($id){

        $rows = static::find()
            ->select(['id', 'lesson_start', 'lesson_length_type', 'user_id', 'instricon_id', 'statusschedule_id', '`comment`', 'student_id'])
            ->where(['id' => $id])
            ->limit(1)
            ->asArray()
            ->one();

        $rows['action_date'] = date('d-m-Y', $rows['lesson_start']);
        $rows['lesson_start'] = date('H:i', $rows['lesson_start']);
        return $rows;
    }

    public static function getLessonsTypeArray($isAjax = false)
    {
        $returnArray = [
            self::LESSON_SHORT => self::LESSON_SHORT.' minutes',
            self::LESSON_MIDDLE => self::LESSON_MIDDLE.' minutes',
            self::LESSON_LONG => self::LESSON_LONG.' minutes'
        ];

        if (!$isAjax) {
            return $returnArray;
        }

        $returnAjaxArray = [];

        foreach ($returnArray as $key => $value){
            $returnAjaxArray[] = ['id' => $key, 'text' => $value];
        }

        return $returnAjaxArray;
    }

    public static function getReportData()
    {
        $query = Userschedule::find()
            ->select([
                'userschedule.user_id AS teacherId',
                'userschedule.student_id AS studentId',
                'userschedule.instricon_id AS lessonInstrId',
                'FROM_UNIXTIME(userschedule.lesson_start, \'%Y-%m-%d %k:%i:%s\') AS lessonStartTime',
                'userschedule.lesson_length_type AS lessonLength',
                'userschedule.statusschedule_id AS lessonStatus',
                'userschedule.comment AS lessonComment',
                'stp.date_from AS priceValidFrom',
                'stp.target_qnt_lessons AS targetQntInMonth',
                'stp.short_clean_money AS sC',
                'stp.short_tax_money AS sT',
                'stp.short_full_money AS sF',
                'stp.middle_clean_money AS mC',
                'stp.middle_tax_money AS mT',
                'stp.middle_full_money AS mF',
                'stp.long_clean_money AS lC',
                'stp.long_tax_money AS lT',
                'stp.long_full_money AS lF',
                'tbt.`type` AS businessType',
                'tbt.date_from AS btValidFrom'
            ])
            ->leftJoin('student_teacher_pricing AS stp', 'stp.id = (
                    SELECT stp2.id
                    FROM student_teacher_pricing AS stp2
                    WHERE userschedule.user_id = stp2.teacher_id
                    AND userschedule.student_id = stp2.student_id
                    AND userschedule.instricon_id = stp2.instrument_id
                    AND FROM_UNIXTIME(userschedule.lesson_start) > stp2.date_from
                    ORDER BY stp2.date_from DESC
                    LIMIT 1)'
            )
            ->leftJoin('teacher_business_type AS tbt', 'tbt.id = (
                    SELECT tbt2.id
                    FROM teacher_business_type AS tbt2
                    WHERE FROM_UNIXTIME(userschedule.lesson_start) > tbt2.date_from
                    AND userschedule.user_id = tbt2.user_id
                    ORDER BY tbt2.date_from DESC
                    LIMIT 1)'
            )
            ->where(['and',
                ['IS NOT', 'stp.date_from', null],
                ['IS NOT', 'tbt.type', null]
            ])
            ->orderBy('userschedule.lesson_start')
            ->asArray()
            ->all();

        $returnArray = [];

        foreach ($query as $item)
        {
            $returnArray[$item['teacherId']][$item['studentId']][$item['lessonInstrId']][$item['lessonStartTime']] = $item;
        }

        return $returnArray;
    }
}
