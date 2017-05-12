<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 28.03.17
 * Time: 14:24
 */

use yii\helpers\Html;
use app\models\User;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $userUpdate app\modules\master\forms\UserUpdateForm */

ini_set('xdebug.var_display_max_depth', 15);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
?>

<table class="table table-hover table-striped table-bordered">
    <tr><td colspan="8" style="text-align: center;color: #2e498b; font-size: 18px; border-bottom-width: 2px; border-bottom-color: #2e498b;">Masters (Admin users)</td></tr>
    <tr>
        <th class="text-center">#</th>
        <th class="text-center">First Name</th>
        <th class="text-center">Last Name</th>
        <th class="text-center">Email</th>
        <th class="text-center">Lessons</th>
        <th class="text-center">Status</th>
        <th class="text-center">Letter</th>
        <th class="text-center">Edit / Delete</th>
    </tr>
    <?php
    foreach ($user_list as $user){
        /* @var $user app\models\User */

        if ($user->userRole() == 'master'):
            $masterNumber = 1;
            $classReg = $user->status==1?'text-danger':'text-success';
            $classLet = $user->letter_status==0||!User::isSecretKeyExpire($user->secret_key)?'text-danger':'text-success';
            echo '<tr style="vertical-align: middle">';
            echo '<td style="vertical-align: middle">'.$masterNumber.'</td>';
            echo '<td style="vertical-align: middle">'.$user->first_name.'</td>';
            echo '<td style="vertical-align: middle">'.$user->last_name.'</td>';
            echo '<td style="vertical-align: middle">'.$user->email.'</td>';
            echo '<td class="text-left" style="vertical-align: middle; padding-left: 15px">';
            foreach ($user->getUserLessons() as $lesson):?>
                <div style="margin: 1px 0"><img src="/images/icons/<?=$lesson['instricon']['icon']?>" class="icon_reg" style="margin: 0 5px"><?=$lesson['instricon']['instr_name']?></div>
            <?php endforeach;
            foreach ($user->getUserLessons() as $lessons):;
                $userInstr[] = $lessons['instricon']['id'];
            endforeach;
            echo '</td>';
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
            echo '</td></tr>';
            $masterNumber++;
        endif;
    }
    ?>

    <tr><td colspan="8"
            style="
                text-align: center;
                color: #717700;
                font-size: 18px;
                padding-top: 20px;
                border-bottom-width: 2px;
                border-bottom-color: #717700; ">Teachers (Common users)</td></tr>
    <tr>
        <th class="text-center">#</th>
        <th class="text-center">First Name</th>
        <th class="text-center">Last Name</th>
        <th class="text-center">Email</th>
        <th class="text-center">Lessons</th>
        <th class="text-center">Status</th>
        <th class="text-center">Letter</th>
        <th class="text-center">Edit / Delete</th>
    </tr>
    <?php
    foreach ($user_list as $user):
        if ($user->userRole() == 'teacher'):
            $teacherNumber = 1;
            $classReg = $user->status==1?'text-danger':'text-success';
            $classLet = $user->letter_status==0||!User::isSecretKeyExpire($user->secret_key)?'text-danger':'text-success';
            echo '<tr style="vertical-align: middle">';
            echo '<td style="vertical-align: middle">'.$teacherNumber.'</td>';
            echo '<td style="vertical-align: middle">'.$user->first_name.'</td>';
            echo '<td style="vertical-align: middle">'.$user->last_name.'</td>';
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
            $teacherNumber++;


        endif;
        ?>
    <?php endforeach;?>
    <?php if (!isset($teacherNumber)):?>
        <tr>
            <td colspan="8" class="text-center" style="vertical-align: middle">No Teachers there...</td>
        </tr>
    <?php endif;?>


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
        <th class="text-center">First Name</th>
        <th class="text-center">Last Name</th>
        <th class="text-center">Teacher</th>
        <th class="text-center">Lessons</th>
        <th class="text-center">Status</th>
        <th class="text-center">Letter</th>
        <th class="text-center">Edit / Delete</th>
    </tr>
    <?php
    foreach ($user_list as $user):
        if ($user->userRole() == 'student'):
            $studentNumber = 1;
            $classReg = $user->status==1?'text-danger':'text-success';
            $classLet = $user->letter_status==0||!User::isSecretKeyExpire($user->secret_key)?'text-danger':'text-success';
            echo '<tr style="vertical-align: middle">';
            echo '<td style="vertical-align: middle">'.$studentNumber.'</td>';
            echo '<td style="vertical-align: middle">'.$user->first_name.'</td>';
            echo '<td style="vertical-align: middle">'.$user->last_name.'</td>';
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
            $studentNumber++;


        endif;
        ?>
    <?php endforeach;?>
    <?php if (!isset($student)):?>
        <tr>
            <td colspan="8" class="text-center" style="vertical-align: middle">No Students there...</td>
        </tr>
    <?php endif;?>
</table>


<?php Modal::begin([
    'header' => '<h3 class="text-warning"><i class="icon fa fa-exclamation-triangle"></i> Warning!</h3>',
    'id'     => 'modal-delete',
    'size' => 'modal-sm',
    'footer' => Html::a('Delete', '', ['class' => 'btn btn-danger', 'id' => 'delete-confirm']),
]); ?>

    <p class="modal-message">Do you really want to delete <strong class='text-danger modal-name'></strong>?</p>

<?php Modal::end(); ?>

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

<?php Modal::begin([
    'header' => '<h4 class="text-info">User Information</h4>',
    'id'     => 'modalUserEdit',
    'size' => 'modal-sm',
//    'footer' => Html::a('Delete', '', ['class' => 'btn btn-danger', 'id' => 'delete-confirm']),
]); ?>

<?php $form = ActiveForm::begin([
    'id' => 'userUpdateForm_Management',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'fieldConfig' => [
        'template' => "<div>{label}</div><div class=\"col-lg-12\">{input}</div>{error}",
        'labelOptions' => ['class' => 'col-lg-12 control-label', 'style' => 'text-align: left'],
        'inputOptions' => ['class' => 'form-control'],
    ],
]); ?>

    <?= $form->field($userUpdate, 'first_name')->textInput([
        'id' => 'first_name',
    ])?>

    <?= $form->field($userUpdate, 'last_name')->textInput([
        'id' => 'last_name',
    ])?>

    <?php
    $escape2 = new JsExpression("function(m) { return m; }");
    echo $form->field($userUpdate, 'lessons')->widget(Select2::className(), [
        'id' => 'lessons',
        'data' => $listUserLessons,
        'theme' => Select2::THEME_BOOTSTRAP,
        'hideSearch' => true,
        'options' => ['placeholder' => 'Type of the Lesson', 'multiple' => true],
        'pluginOptions' => [
            'escapeMarkup' => $escape2,
            'allowClear' => true,
            'closeOnSelect' =>false,
        ],
    ])->label('Lessons');?>

    <?= Html::activeHiddenInput($userUpdate,'user_id', [
        'id' => 'user_idInput',
    ]);?>

    <div class="form-group">
        <div class="col-lg-2">
            <?= Html::submitButton('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update Information', ['class' => 'btn btn-warning', 'id' => 'editUserButton'])?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

<?php Modal::end(); ?>