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
    public $s_clean;
    public $s_tax;
    public $m_clean;
    public $m_tax;
    public $l_clean;
    public $l_tax;
    public $priority;
    public $instrumentId;
    public $date_from;
    public $target;

    public function rules()
    {
        return [
            [[
                'studentId', 'teacherId',
                'instrumentId',
                'date_from',
                'target',
                's_clean', 's_tax',
                'm_clean', 'm_tax',
                'l_clean', 'l_tax'
            ], 'required'],
            [['id', 'studentId', 'teacherId', 'priority', 'instrumentId', 'target'], 'integer'],
            [['s_clean', 's_tax', 'm_clean', 'm_tax', 'l_clean', 'l_tax'], 'double'],
            [['date_from'], 'string'],
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
            'date_from' => 'Valid from date',
            's_clean' => 'S-clean',
            's_tax' => 'S-tax',
            'm_clean' => 'M-clean',
            'm_tax' => 'M-tax',
            'l_clean' => 'L-clean',
            'l_tax' => 'L-tax',
            'target' => 'Lessons target'
        ];
    }

    public function savePrices()
    {

    }
}