<?php

namespace app\modules\master\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "schedinstricon".
 *
 * @property integer $id
 * @property integer $instricon_id
 * @property integer $userschedule_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Instrument $instricon
 * @property Userschedule $userschedule
 */
class Schedinstricon extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedinstricon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['instricon_id', 'userschedule_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['instricon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instricon_id' => 'id']],
            [['userschedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userschedule::className(), 'targetAttribute' => ['userschedule_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'instricon_id' => 'Instricon ID',
            'userschedule_id' => 'Userschedule ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getUserschedule()
    {
        return $this->hasOne(Userschedule::className(), ['id' => 'userschedule_id']);
    }
}
