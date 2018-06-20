<?php

namespace app\modules\master\models;

use app\models\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "teacher_business_type".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $date_from
 * @property string $updated_at
 * @property string $created_at
 *
 * @property User $user
 */
class TeacherBusinessType extends ActiveRecord
{

    const DDP_TYPE = 'DDP';
    const ZL_TYPE = 'ZL';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher_business_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'date_from'], 'required'],
            [['user_id'], 'integer'],
            [['type'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'date_from' => 'Date From',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
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

    public static function getBusinessTypeList()
    {
        return [
            self::DDP_TYPE => self::DDP_TYPE,
            self::ZL_TYPE => self::ZL_TYPE,
        ];
    }

    public function getDateFrom()
    {
        $date = new \DateTime($this->date_from);
        return $date->format('d-m-Y');
    }
}
