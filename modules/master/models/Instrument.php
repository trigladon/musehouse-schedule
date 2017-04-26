<?php

namespace app\modules\master\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "instricon".
 *
 * @property integer $id
 * @property string $icon
 * @property string $instr_name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Schedinstricon[] $schedinstricons
 * @property Userinstr[] $userinstrs
 * @property Userschedule[] $userschedules
 */

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\Query;

class Instrument extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instricon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['icon', 'instr_name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['icon'], 'string', 'max' => 255],
            [['instr_name'], 'string', 'max' => 25],
            [['instr_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'icon' => 'Icon',
            'instr_name' => 'Instr Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedinstricons()
    {
        return $this->hasMany(Schedinstricon::className(), ['instricon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserinstrs()
    {
        return $this->hasMany(Userinstr::className(), ['instricon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserschedules()
    {
        return $this->hasMany(Userschedule::className(), ['instricon_id' => 'id']);
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

    public static function lessonList(){
        $rows = (new Query())
            ->select(['id', 'icon', 'instr_name'])
            ->from('instricon')
            ->all();

        foreach ($rows as $value){
            $lesson_list[$value['id']]['icon'] = $value['icon'];
            $lesson_list[$value['id']]['instr_name'] = $value['instr_name'];
        }

        return $lesson_list;
    }

    public static function lessonListDropBox(){
        $rows = (new Query())
            ->select(['id', 'icon', 'instr_name'])
            ->from('instricon')
            ->all();

        foreach ($rows as $value){
            $lesson_list[$value['id']] = '<img src="/images/icons/'.$value['icon'].'" class="dropBoxIcon">'.$value['instr_name'];
        }

        return $lesson_list;
    }

    public static function lessonListReg(){
        $rows = (new Query())
            ->select(['id', 'icon', 'instr_name'])
            ->from('instricon')
            ->all();

        foreach ($rows as $value){
            $lesson_list[$value['id']] = '<img src="/images/icons/'.$value['icon'].'" class="icon_reg">'.$value['instr_name'];
        }

        return $lesson_list;
    }

    public static function deleteLessonById($id){
        $row = (new Query())
            ->select(['icon'])
            ->from('instricon')
            ->where(['id' => $id])
            ->one();
        $icon = $row['icon'];

        if(file_exists('images/icons/'.$icon)){
            unlink('images/icons/'.$icon);
        }

        Yii::$app->db->createCommand()->delete('instricon', 'id = '.$id)->execute();
    }

    public static function lessonListUser(){
        $rows = (new Query())
            ->select(['ui.instricon_id', 'i.icon', 'i.instr_name'])
            ->from('userinstr ui')
            ->leftJoin('instricon i', 'ui.instricon_id = i.id')
            ->where(['ui.user_id' => Yii::$app->user->identity->getId()])
            ->all();

        foreach ($rows as $value){
            $lesson_list[$value['instricon_id']] = '<img src="/images/icons/'.$value['icon'].'" class="icon_reg">'.$value['instr_name'];
        }

        return $lesson_list;
    }

}
