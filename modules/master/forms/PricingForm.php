<?php

namespace app\modules\master\forms;

use app\modules\master\models\StudentTeacherPricing;
use yii\base\Model;
//use app\modules\master\models\StudentTeacherPricing;
use app\models\User;
use app\modules\master\models\Instrument;
use yii\db\Exception;

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
        if ($this->id) {
            $prices = StudentTeacherPricing::findOne($this->id);
        } else {
            $prices = new StudentTeacherPricing();
        }
        var_dump(\Yii::$app->request->post());
        $date = new \DateTime($this->date_from);
        $saveDate = $date->format('Y-m-d H:i:s');

        $prices->student_id = (int)$this->studentId;
        $prices->teacher_id = (int)$this->teacherId;
        $prices->instrument_id = (int)$this->instrumentId;
        $prices->target_qnt_lessons = (int)$this->target;
        $prices->date_from = $saveDate;

        $prices->short_clean_money = (float)$this->s_clean;
        $prices->short_tax_money = (float)$this->s_tax;
        $prices->short_full_money = (float)$this->s_clean + (float)$this->s_tax;

        $prices->middle_clean_money = (float)$this->m_clean;
        $prices->middle_tax_money = (float)$this->m_tax;
        $prices->middle_full_money = (float)$this->m_clean + (float)$this->m_tax;

        $prices->long_clean_money = (float)$this->l_clean;
        $prices->long_tax_money = (float)$this->l_tax;
        $prices->long_full_money = (float)$this->l_clean + (float)$this->l_tax;

        try{
            $prices->save();
            return true;
        }catch (Exception $e) {
            $e->getMessage();
            return false;
        }
    }
}