<?php

/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 28.03.17
 * Time: 14:16
 */

namespace app\components;

use yii\base\Widget;

class ActivationListWidget extends Widget
{
    public $user_list;
    public $userUpdate;
    public $listUserLessons;
    public $teacherList;

    public function init()
    {
//        parent::init();

    }

    public function run()
    {
        return $this->render('activationList', [
            'user_list' => $this->user_list,
            'userUpdate' => $this->userUpdate,
            'listUserLessons' => $this->listUserLessons,
            'teacherList' => $this->teacherList,
        ]);
    }
}