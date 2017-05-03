<?php

namespace app\modules\master\models;

use Yii;
use app\models\User;
use app\modules\master\models\Instrument;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "userinstr".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $instricon_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Instrument $instricon
 * @property User $user
 */
class Userinstr extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userinstr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'instricon_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['instricon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['instricon_id' => 'id']],
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
            'instricon_id' => 'Instricon ID',
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

    public static function reg($user_id){

        foreach ($_POST['RegForm']['id_lesson'] as $value){
            $cat_target = new Userinstr();
            $cat_target->instricon_id = $value;
            $cat_target->user_id = $user_id;

            $cat_target->save();
        }

        $freetime = Instrument::find()->select('id')->andWhere(['instr_name' => 'Free Time'])->limit(1)->asArray()->one();

        $cat_target = new Userinstr();
        $cat_target->instricon_id = $freetime['id'];
        $cat_target->user_id = $user_id;

        $cat_target->save();

        return true;
    }

    public static function deleteByUidIid($uId, $iId){
        $userInstr = static::findOne(['user_id' => $uId, 'instricon_id' => $iId]);
        $userInstr->delete();
    }
}
