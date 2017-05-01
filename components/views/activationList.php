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

ini_set('xdebug.var_display_max_depth', 15);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
?>

<table class="table table-hover table-striped table-bordered">
    <tr><td colspan="8" style="color: #2e498b; font-size: 18px; border-bottom-width: 2px; border-bottom-color: #2e498b;">Masters (Admin users)</td></tr>
    <tr>
        <th class="text-center">#</th>
        <th class="text-center">First Name</th>
        <th class="text-center">Last Name</th>
        <th class="text-center">Email</th>
        <th class="text-center">Lessons</th>
        <th class="text-center">Status</th>
        <th class="text-center">Letter</th>
        <th class="text-center">Delete</th>
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
            if (Yii::$app->user->id == $user->id){
                echo '<td class="text-center" style="vertical-align: middle">'.Html::a('<i class="fa fa-trash-o fa-lg text-muted" aria-hidden="true"></i>');
            }else{
                echo '<td class="text-center" style="vertical-align: middle">'.Html::a('<i class="fa fa-trash-o fa-lg text-danger" aria-hidden="true"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                        '/master/users',
                    ]), [
                        'class'       => 'popup-delete linkaction',
                        'data-toggle' => 'modal',
                        'data-target' => '#modal',
                        'data-id' => $user->id,
                        'data-name' => $user->getUsername(),
                        'id'          => 'popupModal',]).'</td>';
            }
            echo '</tr>';
            $masterNumber++;
        endif;
    }
    ?>

    <tr><td colspan="8"
            style="
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
        <th class="text-center">Delete</th>
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
            echo '<td class="text-center" style="vertical-align: middle">'.Html::a('<i class="fa fa-trash-o fa-lg text-danger" aria-hidden="true"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                    '/master/users',
                ]), [
                    'class'       => 'popup-delete linkaction',
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'data-id' => $user->id,
                    'data-name' => $user->getUsername(),
                    'id'          => 'popupModal',]).'</td>';
            echo '</tr>';
            $teacherNumber++;


        endif;
        ?>
    <?php endforeach;?>
    <?php if (!isset($teacherNumber)):?>
        <tr>
            <td colspan="8" class="text-center" style="vertical-align: middle">No Teachers there...</td>
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
