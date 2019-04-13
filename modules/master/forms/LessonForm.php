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
use yii\log\Logger;

class LessonForm extends Model
{
    /**
     * @var UploadedFile
     */

    public $icon;
    public $lessonName;

    public function rules()
    {
        return [
            ['icon', 'required'],
            ['lessonName', 'required'],
            [['icon'], 'file', 'extensions' => 'png'],
            ['lessonName', 'string', 'length' => [2, 25]],
            ['lessonName', 'match', 'pattern' => '/^[a-zA-Z0-9\s]*$/i']
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $iconName = uniqid().'.'.$this->icon->extension;
            $this->icon->saveAs(Yii::$app->getBasePath().'/web/images/icons/' . $iconName);
            $this->reg($iconName);
        } else {
            echo 'shit happens';
        }
    }

    public function reg($iconName){

        $icon = new Instrument();
        $icon->icon = $iconName;
        $icon->instr_name = $this->lessonName;
        try {
            $icon->save();
        } catch (\Exception $e) {
            Yii::getLogger()->log($e, Logger::LEVEL_ERROR);
        }
    }
}