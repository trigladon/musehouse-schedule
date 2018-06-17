<?php

namespace app\modules\master\models;

use app\models\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "student_teacher_pricing".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $teacher_id
 * @property integer $priority
 * @property integer $instrument_id
 * @property string $date_from
 * @property string $date_to
 * @property string $updated_at
 * @property string $created_at
 *
 * @property User $student
 * @property User $teacher
 */
class StudentTeacherPricing extends ActiveRecord
{

    const PRIORITY_COMMON = 1;
    const PRIORITY_MAJOR = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_teacher_pricing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'teacher_id', 'priority', 'instrument_id', 'date_from', 'date_to', 'updated_at', 'created_at'], 'required'],
            [['student_id', 'teacher_id', 'priority', 'instrument_id'], 'integer'],
            [['date_from', 'date_to', 'updated_at', 'created_at'], 'safe'],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['teacher_id' => 'id']],
            [['instrument_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instrument_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student ID',
            'teacher_id' => 'Teacher ID',
            'priority' => 'Priority',
            'instrument_id' => 'Lesson ID',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(User::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(User::className(), ['id' => 'teacher_id']);
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

    public static function getPriorityList()
    {
        $list = [];
        $list[self::PRIORITY_COMMON] = 'Common';
        $list[self::PRIORITY_MAJOR] = 'Major';

        return $list;
    }
}
