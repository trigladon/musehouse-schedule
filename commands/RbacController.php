<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 17.03.17
 * Time: 18:40
 */

namespace app\commands;


use yii\console\Controller;


class RbacController extends Controller
{
    public function actionInit(){

        $auth = \Yii::$app->authManager;

//        //add permission 'createUser'
//        $createUser = $auth->createPermission('createUser');
//        $createUser->description = 'CRUD an User';
//        $auth->add($createUser);
//
//        //add permission 'addTypeLesson'
//        $addTypeLesson = $auth->createPermission('addTypeLesson');
//        $addTypeLesson->description = 'CRUD type of Lesson';
//        $auth->add($addTypeLesson);
//
//        //add permission 'addInstrument'
//        $addInstrument = $auth->createPermission('addInstrument');
//        $addInstrument->description = 'CRUD of Instruments';
//        $auth->add($addInstrument);
//
//        //add permission 'allView'
//        $allView = $auth->createPermission('allView');
//        $allView->description = 'Global lessons view';
//        $auth->add($allView);

        //add permission 'calendar'
//        $calendar = $auth->createPermission('calendar');
//        $calendar->description = 'Calendar';
//        $auth->add($calendar);

        // добавляем роль "teacher" и даём роли разрешение "calendar"
//        $teacher = $auth->createRole('teacher');
//        $auth->add($teacher);
//        $auth->addChild($teacher, $calendar);

        // добавляем роль "teacher" и даём роли разрешение "calendar"
        $student = $auth->createRole('student');
        $auth->add($student);

        // добавляем роль "admin" и даём роли разрешения на 'createUser', 'addTypeLesson', 'addInstrument', 'allView'
        // а также все разрешения роли "teacher"
//        $admin = $auth->createRole('admin');
//        $auth->add($admin);
//        $auth->addChild($admin, $createUser);
//        $auth->addChild($admin, $addTypeLesson);
//        $auth->addChild($admin, $addInstrument);
//        $auth->addChild($admin, $allView);
//        $auth->addChild($admin, $teacher);
    }

}