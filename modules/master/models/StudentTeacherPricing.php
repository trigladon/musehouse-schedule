<?php

namespace app\modules\master\models;

use app\modules\master\models\Instrument;
use Yii;
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
 * @property integer $instrument_id
 * @property integer $target_qnt_lessons
 * @property double $short_full_money
 * @property double $short_clean_money
 * @property double $short_tax_money
 * @property double $middle_full_money
 * @property double $middle_clean_money
 * @property double $middle_tax_money
 * @property double $long_full_money
 * @property double $long_clean_money
 * @property double $long_tax_money
 * @property string $date_from
 * @property string $updated_at
 * @property string $created_at
 *
 * @property Instrument $instrument
 * @property User $student
 * @property User $teacher
 */
class StudentTeacherPricing extends \yii\db\ActiveRecord
{
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
            [['student_id', 'teacher_id', 'instrument_id', 'target_qnt_lessons', 'short_full_money', 'short_clean_money', 'short_tax_money', 'middle_full_money', 'middle_clean_money', 'middle_tax_money', 'long_full_money', 'long_clean_money', 'long_tax_money', 'date_from'], 'required'],
            [['student_id', 'teacher_id', 'instrument_id', 'target_qnt_lessons'], 'integer'],
            [['short_full_money', 'short_clean_money', 'short_tax_money', 'middle_full_money', 'middle_clean_money', 'middle_tax_money', 'long_full_money', 'long_clean_money', 'long_tax_money'], 'number'],
            [['date_from', 'updated_at', 'created_at'], 'safe'],
            [['instrument_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instrument_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['teacher_id' => 'id']],
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
            'instrument_id' => 'Instrument ID',
            'target_qnt_lessons' => 'Target Qnt Lessons',
            'short_full_money' => 'Short Full Money',
            'short_clean_money' => 'Short Clean Money',
            'short_tax_money' => 'Short Tax Money',
            'middle_full_money' => 'Middle Full Money',
            'middle_clean_money' => 'Middle Clean Money',
            'middle_tax_money' => 'Middle Tax Money',
            'long_full_money' => 'Long Full Money',
            'long_clean_money' => 'Long Clean Money',
            'long_tax_money' => 'Long Tax Money',
            'date_from' => 'Date From',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstrument()
    {
        return $this->hasOne(Instrument::className(), ['id' => 'instrument_id']);
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

    public static function getUnsetPriceLessons()
    {
        $query = Userschedule::find()
            ->select([
                'userschedule.user_id',
                'userschedule.student_id',
                'userschedule.instricon_id',
                'MIN(userschedule.lesson_start) as lessonTimeStart',
                'MIN(stp.date_from) as price_from'
            ])
            ->leftJoin('student_teacher_pricing as stp', 'userschedule.user_id = stp.teacher_id and userschedule.student_id = stp.student_id and userschedule.instricon_id = stp.instrument_id')
            ->where(['and',
                ['>', 'userschedule.student_id', 0],
                ['not in', 'userschedule.instricon_id', [27]]
            ])
            ->groupBy('userschedule.user_id, userschedule.student_id, userschedule.instricon_id')
            ->orderBy('lessonTimeStart')
            ->distinct()
            ->asArray()
            ->all();

        $return_array = [];

        foreach ($query as $item) {
            if ($item['lessonTimeStart'] < strtotime($item['price_from']) || is_null($item['price_from'])) $return_array[] = $item;
        }

        return $return_array;
    }

    public function getDateFrom()
    {
        $date = new \DateTime($this->date_from);
        return $date->format('d-m-Y');
    }

    public static function getPricesFilter()
    {
        $get = Yii::$app->request->get();
        $filterArray = [];
        if ($get){
            if (!empty($get['filterTeacher'])) $filterArray['teacher_id'] = $get['filterTeacher'];
            if (!empty($get['filterStudent'])) $filterArray['student_id'] = $get['filterStudent'];
            if (!empty($get['filterLesson'])) $filterArray['instrument_id'] = $get['filterLesson'];
        }

        $query = StudentTeacherPricing::find();

        if ($filterArray) {
            $query = $query
                ->where($filterArray);
        }

        $query = $query
            ->all();

        return $query;
    }
}
