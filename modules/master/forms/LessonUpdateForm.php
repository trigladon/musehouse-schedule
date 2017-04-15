<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 06.04.17
 * Time: 2:57
 */

namespace app\modules\master\forms;


use app\modules\master\models\Instrument;
use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class LessonUpdateForm extends Model
{

    public $lessonUpName;
    public $idUpName;

    public function rules()
    {
        return [
            [['lessonUpName', 'idUpName'], 'required'],
            ['lessonUpName', 'string', 'length' => [2, 25]],
            ['lessonUpName', 'match', 'pattern' => '/^[a-zA-Z0-9\s]+$/i'],
            [['idUpName'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['idUpName' => 'id']],
        ];
    }


    public function updateLesson(){

        Yii::$app->db->createCommand()->update('instricon', ['instr_name' => $this->lessonUpName], 'id ='.$this->idUpName)->execute();
    }
}