<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 28.03.17
 * Time: 14:24
 */

use yii\helpers\Html;
use app\models\User;

/** @var $businessTypeForm \app\modules\master\forms\TeacherBusinessTypeForm */
/** @var $bt \app\modules\master\models\TeacherBusinessType */

?>
<?php if (Yii::$app->session->hasFlash('Error')): ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-exclamation-triangle"></i> Warning!</h4>
        <?= Yii::$app->session->getFlash('Error') ?>
    </div>
<?php elseif (Yii::$app->session->hasFlash('Success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-hand-peace-o"></i> Success!</h4>
        <?= Yii::$app->session->getFlash('Success') ?>
    </div>
<?php endif;?>
<table class="table table-hover table-bordered" style="margin-bottom: -1px;">
    <tr><td colspan="9" style="text-align: center;color: #2e498b; font-size: 18px; border-bottom-width: 2px; border-bottom-color: #2e498b;">Masters (Admin users)</td></tr>
    <tr>
        <th class="text-center">#</th>
        <th class="text-center">Username</th>
        <th class="text-center">Phone Number</th>
        <th class="text-center">Email</th>
        <th class="text-center">Lessons</th>
        <th class="text-center">DPP/ZL</th>
        <th class="text-center">Status</th>
        <th class="text-center">Letter</th>
        <th class="text-center">Edit / Delete</th>
    </tr>
    <?php
    $masterNumber = 0;
    foreach ($user_list as $user){
        /* @var $user app\models\User */

        if ($user->userRole() == 'Master' && $user->status == User::STATUS_ACTIVE):
            ++$masterNumber;
            $classReg = $user->status==1?'text-danger':'text-success';
            $classLet = $user->letter_status==0||!User::isSecretKeyExpire($user->secret_key)?'text-danger':'text-success';
            echo '<tr style="vertical-align: middle">';
            echo '<td class="text-center" style="vertical-align: middle">'.$masterNumber.'</td>';
            echo '<td style="vertical-align: middle">'.$user->getUsername().'</td>';
            echo '<td style="vertical-align: middle">'.($user->phone?:'no phone provided').'</td>';
            echo '<td style="vertical-align: middle">'.$user->email.'</td>';
            echo '<td class="text-left" style="vertical-align: middle; padding-left: 15px">';
            foreach ($user->getUserLessons() as $lesson):?>
                <div style="margin: 1px 0"><img src="/images/icons/<?=$lesson['instricon']['icon']?>" class="icon_reg" style="margin: 0 5px"><?=$lesson['instricon']['instr_name']?></div>
            <?php endforeach;
            foreach ($user->getUserLessons() as $lessons):;
                $userInstr[] = $lessons['instricon']['id'];
            endforeach;
            echo '</td>';
            echo '<td style="vertical-align: middle; text-align: center">';
            echo $user->getCurrentBusinessType()?:"No data found";
            echo '<br><small class="cursor text-info '.$user->id.'arrow" onclick="showBT('.$user->id.', \'open\')">(show <i class="fa fa-caret-down" aria-hidden="true"></i>)</small></td>';
            echo '<td class="text-center" style="vertical-align: middle">
            <i class="fa fa-user fa-lg '.$classReg.'" aria-hidden="true"></i>
            </td>';
            if ($classReg === 'text-success'){
                echo '<td class="text-center" style="vertical-align: middle"><i class="fa fa-check fa-lg text-success" aria-hidden="true"></i></td>';
            }else{
                echo '<td class="text-center" style="vertical-align: middle"><i class="fa fa-check fa-lg '.$classLet.'" style="margin-right:11px" aria-hidden="true"></i>'.
                    Html::a('<i class="fa fa-share text-warning" aria-hidden="true"></i>
                        <i class="fa fa-envelope text-warning" aria-hidden="true"></i>',
                        Yii::$app->urlManager->createAbsoluteUrl([
                            '/master/users',
                            'resendUserLetter' => $user->id,
                        ]), ['class' => 'linkaction']).'</td>';
            }
            echo '<td class="text-center" style="vertical-align: middle">'.
                Html::a('<i class="fa fa fa-pencil-square-o fa-lg text-warning" aria-hidden="true" style="vertical-align: -3px;"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                    '/master/users',
                ]), [
                    'data-user_id' => $user->id,
                    'data-first_name' => $user->first_name,
                    'data-last_name' => $user->last_name,
                    'data-lessons' => $userInstr,
                    'data-teachers' => '',
                    'data-role' => $user->userRole(),
                    'data-phone' => $user->phone,
                    'id'          => 'editUser',]).' / ';
            echo Yii::$app->user->id == $user->id ? Html::a('<i class="fa fa-trash-o fa-lg text-muted" aria-hidden="true"></i>') :
                Html::a('<i class="fa fa-trash-o fa-lg text-danger" aria-hidden="true"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                    '/master/users',
                ]), [
                    'class'       => 'popup-delete linkaction',
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'data-id' => $user->id,
                    'data-name' => $user->getUsername(),
                    'id'          => 'popupModal',
                    ]);
            echo '</td></tr><tr><td class="'.$user->id.'bt text-center" colspan="9" style="display: none">';
            echo '<strong style="font-size: larger">Changes history</strong><br>';
            $historyBusinessTypes = $user->getHistoryBusinessType();
            if ($historyBusinessTypes) :
                foreach ($historyBusinessTypes as $bt):
                    echo $bt->type.' from '.$bt->getDateFrom().' <i onclick="setBusinessType('.$user->id.', '.$bt->id.')" class="fa fa-pencil-square-o text-warning cursor" aria-hidden="true"></i> / <i class="fa fa-trash-o text-danger cursor" style="vertical-align: 7%;" onclick="delBusinessType('.$bt->id.')" aria-hidden="true"></i><br>';
                endforeach;
            else:
                echo 'No data found.';
            endif;
            echo '<br><span class="btn btn-success" role="button" onclick="setBusinessType('.$user->id.')">Add info <i class="fa fa-plus" aria-hidden="true"></i></span>';
            echo '</td></tr>';
        endif;
    }
    ?>

    <tr><td colspan="9"
            style="
                text-align: center;
                color: #717700;
                font-size: 18px;
                padding-top: 20px;
                border-bottom-width: 2px;
                border-bottom-color: #717700; ">Teachers (Common users)</td></tr>
    <tr>
        <th class="text-center">#</th>
        <th class="text-center">Username</th>
        <th class="text-center">Phone Number</th>
        <th class="text-center">Email</th>
        <th class="text-center">Lessons</th>
        <th class="text-center">DPP/ZL</th>
        <th class="text-center">Status</th>
        <th class="text-center">Letter</th>
        <th class="text-center">Edit / Delete</th>
    </tr>
    <?php
    $teacherNumber = 0;
    foreach ($user_list as $user):
        if ($user->userRole() == 'Teacher'):
            ++$teacherNumber;
            $classReg = $user->status==1?'text-danger':'text-success';
            $classLet = $user->letter_status==0||!User::isSecretKeyExpire($user->secret_key)?'text-danger':'text-success';
            echo '<tr style="vertical-align: middle">';
            echo '<td class="text-center" style="vertical-align: middle">'.$teacherNumber.'</td>';
            echo '<td style="vertical-align: middle">'.$user->getUsername().'</td>';
            echo '<td style="vertical-align: middle">'.($user->phone?:'no phone provided').'</td>';
            echo '<td style="vertical-align: middle">'.$user->email.'</td>';
            echo '<td class="text-left" style="vertical-align: middle; padding-left: 15px">';
            foreach ($user->getUserLessons() as $lesson):?>
                <div style="margin: 1px 0"><img src="/images/icons/<?=$lesson['instricon']['icon']?>" class="icon_reg" style="margin: 0 5px"><?=$lesson['instricon']['instr_name']?></div>
            <?php endforeach;
            $userInstr = [];
            foreach ($user->getUserLessons() as $lessons):;
                $userInstr[] = $lessons['instricon']['id'];
            endforeach;
            echo '</td>';
            echo '<td style="vertical-align: middle; text-align: center">';
            echo $user->getCurrentBusinessType()?:"No data found";
            echo '<br><small class="cursor text-info '.$user->id.'arrow" onclick="showBT('.$user->id.', \'open\')">(show <i class="fa fa-caret-down" aria-hidden="true"></i>)</small></td>';
            echo '<td class="text-center" style="vertical-align: middle">
            <i class="fa fa-user fa-lg '.$classReg.'" aria-hidden="true"></i>

            </td>';
            if ($classReg === 'text-success'){
                echo '<td class="text-center" style="vertical-align: middle"><i class="fa fa-check fa-lg text-success" aria-hidden="true"></i></td>';
            }else{
                echo '<td class="text-center" style="vertical-align: middle"><i class="fa fa-check fa-lg '.$classLet.'" style="margin-right:11px" aria-hidden="true"></i>'.
                    Html::a('<i class="fa fa-share text-warning" aria-hidden="true"></i>
                        <i class="fa fa-envelope text-warning" aria-hidden="true"></i>',
                        Yii::$app->urlManager->createAbsoluteUrl([
                            '/master/users',
                            'resendUserLetter' => $user->id,
                        ]), ['class' => 'linkaction']).'</td>';
            }
            echo '<td class="text-center" style="vertical-align: middle">'.
                Html::a('<i class="fa fa fa-pencil-square-o fa-lg text-warning" aria-hidden="true" style="vertical-align: -3px;"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                    '/master/users',
                ]), [
//                    'class'       => 'linkaction',
                    'data-user_id' => $user->id,
                    'data-first_name' => $user->first_name,
                    'data-last_name' => $user->last_name,
                    'data-lessons' => $userInstr,
                    'data-teachers' => '',
                    'data-role' => $user->userRole(),
                    'data-phone' => $user->phone,
                    'id'          => 'editUser',]).' / ';
            echo Html::a('<i class="fa fa-trash-o fa-lg text-danger" aria-hidden="true"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                    '/master/users',
                ]), [
                    'class'       => 'popup-delete linkaction',
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'data-id' => $user->id,
                    'data-name' => $user->getUsername(),
                    'id'          => 'popupModal',
                ]);
            echo '</td></tr><tr><td class="'.$user->id.'bt text-center" colspan="9" style="display: none">';
            echo '<strong style="font-size: larger">Changes history</strong><br>';
            $historyBusinessTypes = $user->getHistoryBusinessType();
            if ($historyBusinessTypes) :
                foreach ($historyBusinessTypes as $bt):
                    echo $bt->type.' from '.$bt->getDateFrom().' <i onclick="setBusinessType('.$user->id.', '.$bt->id.')" class="fa fa-pencil-square-o text-warning cursor" aria-hidden="true"></i> / <i class="fa fa-trash-o text-danger cursor" style="vertical-align: 7%;" onclick="delBusinessType('.$bt->id.')" aria-hidden="true"></i><br>';
                endforeach;
            else:
                echo 'No data found.';
            endif;
            echo '<br><span class="btn btn-success" role="button" onclick="setBusinessType('.$user->id.')">Add info <i class="fa fa-plus" aria-hidden="true"></i></span>';
            echo '</td></tr>';

        endif;
        ?>
    <?php endforeach;?>
    <?php if ($teacherNumber == 0):?>
        <tr>
            <td colspan="8" class="text-center" style="vertical-align: middle">No Teachers there...</td>
        </tr>
    <?php endif;?>
</table>
<table class="table table-hover table-bordered">
    <tr><td colspan="8"
            style="
                text-align: center;
                color: #41773c;
                font-size: 18px;
                padding-top: 20px;
                border-bottom-width: 2px;
                border-bottom-color: #41773c;">Students</td></tr>
    <tr>
        <th class="text-center">#</th>
        <th class="text-center">Username</th>
        <th class="text-center">Phone Number</th>
        <th colspan="3" class="text-center">Teachers</th>
        <th class="text-center">Edit / Delete</th>
    </tr>
    <?php
    $studentNumber = 0;
    foreach ($user_list as $user):
        if ($user->userRole() == 'Student'):
            ++$studentNumber;
            $classReg = $user->status==1?'text-danger':'text-success';
            $classLet = $user->letter_status==0||!User::isSecretKeyExpire($user->secret_key)?'text-danger':'text-success';
            echo '<tr style="vertical-align: middle">';
            echo '<td class="text-center" style="vertical-align: middle">'.$studentNumber.'</td>';
            echo '<td style="vertical-align: middle">'.$user->getUsername().'</td>';
            echo '<td style="vertical-align: middle">'.($user->phone?:'no phone provided').'</td>';
            echo '<td colspan="3" class="text-left" style="vertical-align: middle; padding-left: 15px">';
            $userTeachers = [];
            foreach ($user->teachers() as $teacher):/* @var $teacher User*/?>
                <div style="margin: 1px 0">- <?=$teacher->getUsername()?></div>
            <?php $userTeachers[] = $teacher->id;
            endforeach;
            echo '</td>';
            echo '<td class="text-center" style="vertical-align: middle">'.
                Html::a('<i class="fa fa fa-pencil-square-o fa-lg text-warning" aria-hidden="true" style="vertical-align: -3px;"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                    '/master/users',
                ]), [
//                    'class'       => 'linkaction',
                    'data-user_id' => $user->id,
                    'data-first_name' => $user->first_name,
                    'data-last_name' => $user->last_name,
                    'data-lessons' => '',
                    'data-teachers' => $userTeachers,
                    'data-role' => $user->userRole(),
                    'data-phone' => $user->phone,
                    'id'          => 'editUser',]).' / ';
            echo Html::a('<i class="fa fa-trash-o fa-lg text-danger" aria-hidden="true"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                '/master/users',
            ]), [
                'class'       => 'popup-delete linkaction',
                'data-toggle' => 'modal',
                'data-target' => '#modal',
                'data-id' => $user->id,
                'data-name' => $user->getUsername(),
                'id'          => 'popupModal',
            ]);
            echo '</td></tr>';



        endif;
        ?>
    <?php endforeach;?>
    <?php if ($studentNumber == 0):?>
        <tr>
            <td colspan="8" class="text-center" style="vertical-align: middle">No Students there...</td>
        </tr>
    <?php endif;?>
</table>

<?php

require '../templates/deleteConfirmationModal.php';
require '../templates/userUpdateFormModal.php';
require '../templates/teacherBusinessTypeModal.php';

?>