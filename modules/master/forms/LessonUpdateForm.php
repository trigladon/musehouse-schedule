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
use yii\db\Query;
use yii\web\UploadedFile;
use Yii;

class LessonUpdateForm extends Model
{
    /**
     * @var UploadedFile
     */

    public $icon;
    public $lessonUpName;
    public $idUpName;

    public function rules()
    {
        return [
            [['icon'], 'file', 'extensions' => 'png'],
            [['lessonUpName', 'idUpName'], 'required'],
            ['lessonUpName', 'string', 'length' => [2, 25]],
            ['lessonUpName', 'match', 'pattern' => '/^[a-zA-Z0-9\s]+$/i'],
            [['idUpName'], 'exist', 'skipOnError' => true, 'targetClass' => Instrument::className(), 'targetAttribute' => ['idUpName' => 'id']],
        ];
    }


    public function updateLesson(){

        if (is_object($this->icon)) {

            try {
                $row = (new Query())
                    ->select(['icon'])
                    ->from('instricon')
                    ->where(['id' => $this->idUpName])
                    ->one();
                $iconDel = $row['icon'];

                if (file_exists('images/icons/' . $iconDel)) {
                    unlink('images/icons/' . $iconDel);
                }

                $iconName = uniqid() . '.' . $this->icon->extension;
                $this->icon->saveAs(Yii::$app->getBasePath() . '/web/images/icons/' . $iconName);
                Yii::$app->db->createCommand()->update('instricon', ['icon' => $iconName], 'id =' . $this->idUpName)->execute();
            } catch (\yii\db\Exception $e) {
                echo $e->getMessage();
            }
        }

        Yii::$app->db->createCommand()->update('instricon', ['instr_name' => $this->lessonUpName], 'id ='.$this->idUpName)->execute();
    }

}
