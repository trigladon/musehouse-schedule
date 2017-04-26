<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 18.04.17
 * Time: 0:23
 */

namespace app\modules\master\forms;


use yii\base\Model;
use Yii;

class CalendarFilterForm extends Model
{
    public $lessonFilter;
    public $teacherFilter;
    public $statusFilter;

    public function rules()
    {
        return [
            [['lessonFilter', 'teacherFilter', 'statusFilter'], 'each', 'rule' => ['integer']],
        ];
    }

    public function addSessionData(){

        $session = Yii::$app->session;

        $session->set('lessonFilter', $this->lessonFilter);
        $session->set('teacherFilter', $this->teacherFilter);
        $session->set('statusFilter', $this->statusFilter);
    }

    public static function clearSessionData(){
        $session = Yii::$app->session;

        $session->remove('lessonFilter');
        $session->remove('teacherFilter');
        $session->remove('statusFilter');
    }

}