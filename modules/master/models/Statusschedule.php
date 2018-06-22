<?php

namespace app\modules\master\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "statusschedule".
 *
 * @property integer $id
 * @property string $color
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Userschedule[] $userschedules
 */
class Statusschedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statusschedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['color', 'name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['color', 'name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserschedules()
    {
        return $this->hasMany(Userschedule::className(), ['statusschedule_id' => 'id']);
    }

    public static function statusList(){
        $rows = (new Query())
            ->select(['id', 'name', 'color'])
            ->from('statusschedule')
            ->all();

        foreach ($rows as $value){
            $status_list[$value['id']] = '<div style="display: inline-block"><div class="dropBoxStatus img-rounded pull-left" style="background-color:'.$value['color'].'"></div>'.$value['name'].'</div>';
        }

        return $status_list;
    }

    public static function getLessonStatuses()
    {
        $query = self::find()->all();
        $returnArray =[];

        /** @var Statusschedule $item */
        foreach ($query as $item)
        {
            $returnArray[$item->id] = [
                'color' => $item->color,
                'name' => $item->name
            ];
        }

        return $returnArray;
    }
}
