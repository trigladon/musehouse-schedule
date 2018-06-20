<?php

namespace app\modules\master\forms;


use app\models\User;
use app\modules\master\models\TeacherBusinessType;
use yii\base\Model;
use DateTime;

class TeacherBusinessTypeForm extends Model
{
    public $teacher_id;
    public $business_type;
    public $date_from;

    public function rules()
    {
        return [
            [['teacher_id', 'business_type', 'date_from'], 'required'],
            [['business_type', 'date_from'], 'string'],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'teacher_id' => 'Teacher',
            'business_type' => 'Business Type',
            'date_from' => 'From date'
        ];
    }

    public function saveBT()
    {

        $date = new DateTime($this->date_from);
        $saveDate = $date->format('Y-m-d H:i:s');

        $businessType = new TeacherBusinessType();
        $businessType->user_id = (int)$this->teacher_id;
        $businessType->type = $this->business_type;
        $businessType->date_from = $saveDate;

        return $businessType->save() ? true : false ;
    }

}