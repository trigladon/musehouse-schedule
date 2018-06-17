<?php

namespace app\modules\master\forms;

use yii\base\Model;
//use app\modules\master\models\StudentTeacherPricing;
use app\models\User;
use app\modules\master\models\Instrument;

class PricingForm extends Model
{
    public $id;
    public $studentId;
    public $teacherId;
    public $price;
    public $priority;
    public $instrumentId;
    public $dateRange;

    public function rules()
    {
        return [
            [['studentId', 'teacherId', 'price', 'priority', 'instrumentId', 'dateRange'], 'required'],
            [['id', 'studentId', 'teacherId', 'priority', 'instrumentId'], 'integer'],
            [['price'], 'double'],
            [['dateRange'], 'string'],
            [['teacherId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['teacherId' => 'id']],
            [['studentId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['studentId' => 'id']],
            [['instrumentId'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instrumentId' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'studentId' => 'Student',
            'teacherId' => 'Teacher',
            'price' => 'Price',
            'priority' => 'Priority',
            'instrumentId' => 'Lesson',
            'dateRange' => 'DateRange',
        ];
    }

}