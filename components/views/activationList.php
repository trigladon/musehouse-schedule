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
?>
<table class="table table-hover table-striped table-bordered">
<?php if(isset($user_list['master'])):?>
    <tr><td colspan="7" style="color: #2e498b; font-size: 18px; border-bottom-width: 2px; border-bottom-color: #2e498b;">Masters (Admin users)</td></tr>
    <tr>
        <th class="text-center">#</th>
        <th class="text-center">First Name</th>
        <th class="text-center">Last Name</th>
        <th class="text-center">Email</th>
        <th class="text-center">RegST</th>
        <th class="text-center">LetterST</th>
        <th class="text-center">Delete</th>
    </tr>
<?php
    foreach ($user_list['master'] as $key => $value){
        $number = $key+1;
        $classReg = $value['status']==1?'text-danger':'text-success';
        $classLet = $value['letter_status']==0||!User::isSecretKeyExpire($value['secret_key'])?'text-danger':'text-success';
        echo '<tr>';
            echo '<td>'.$number.'</td>';
            echo '<td>'.$value['first_name'].'</td>';
            echo '<td>'.$value['last_name'].'</td>';
            echo '<td>'.$value['email'].'</td>';
            echo '<td class="text-center">
            <i class="fa fa-user fa-lg '.$classReg.'" aria-hidden="true"></i>

            </td>';
            if ($classReg === 'text-success'){
                echo '<td class="text-center"><i class="fa fa-check fa-lg text-success" aria-hidden="true"></i></td>';
            }else{
                echo '<td class="text-center"><i class="fa fa-check fa-lg '.$classLet.'" style="margin-right:11px" aria-hidden="true"></i>'.
                    Html::a('<i class="fa fa-share text-warning" aria-hidden="true"></i>
                        <i class="fa fa-envelope text-warning" aria-hidden="true"></i>',
                        Yii::$app->urlManager->createAbsoluteUrl([
                        '/master/users',
                        'resendUserLetter' => $value['id'],
                    ]), ['class' => 'linkaction']).'</td>';
            }
        echo '<td class="text-center">'.Html::a('<i class="fa fa-trash-o fa-lg text-danger" aria-hidden="true"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                '/master/users',
            ]), [
                'class'       => 'popup-delete linkaction',
                'data-toggle' => 'modal',
                'data-target' => '#modal',
                'data-id' => $value['id'],
                'data-name' => $value['first_name'].' '.$value['last_name'],
                'id'          => 'popupModal',]).'</td>';
        echo '</tr>';
    }
?>
<?php endif;?>
<?php if (isset($user_list['teacher'])):?>
<!--</table>-->
<!---->
<!--    <table class="table table-hover table-striped table-bordered">-->
        <tr><td colspan="7"
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
            <th class="text-center">RegST</th>
            <th class="text-center">LetterST</th>
            <th class="text-center">Delete</th>
        </tr>
        <?php
        foreach ($user_list['teacher'] as $key => $value){
            $number = $key+1;
            $classReg = $value['status']==1?'text-danger':'text-success';
            $classLet = $value['letter_status']==0||!User::isSecretKeyExpire($value['secret_key'])?'text-danger':'text-success';
            echo '<tr>';
            echo '<td>'.$number.'</td>';
            echo '<td>'.$value['first_name'].'</td>';
            echo '<td>'.$value['last_name'].'</td>';
            echo '<td>'.$value['email'].'</td>';
            echo '<td class="text-center">
            <i class="fa fa-user fa-lg '.$classReg.'" aria-hidden="true"></i>

            </td>';
            if ($classReg === 'text-success'){
                echo '<td class="text-center"><i class="fa fa-check fa-lg text-success" aria-hidden="true"></i></td>';
            }else{
                echo '<td class="text-center"><i class="fa fa-check fa-lg '.$classLet.'" style="margin-right:11px" aria-hidden="true"></i>'.
                    Html::a('<i class="fa fa-share text-warning" aria-hidden="true"></i>
                        <i class="fa fa-envelope text-warning" aria-hidden="true"></i>',
                        Yii::$app->urlManager->createAbsoluteUrl([
                            '/master/users',
                            'resendUserLetter' => $value['id'],
                        ]), ['class' => 'linkaction']).'</td>';
            }
            echo '<td class="text-center">'.Html::a('<i class="fa fa-trash-o fa-lg text-danger" aria-hidden="true"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                    '/master/users',
                ]), [
                    'class'       => 'popup-delete linkaction',
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'data-id' => $value['id'],
                    'data-name' => $value['first_name'].' '.$value['last_name'],
                    'id'          => 'popupModal',]).'</td>';
            echo '</tr>';
        }
        ?>
<?php endif; ?>
    </table>
<?php
    var_dump($user_list);

?>

<?php Modal::begin([
    'header' => '<h3 class="text-warning"><i class="icon fa fa-exclamation-triangle"></i> Warning!</h3>',
    'id'     => 'modal-delete',
    'size' => 'modal-sm',
    'footer' => Html::a('Delete', '', ['class' => 'btn btn-danger', 'id' => 'delete-confirm']),
]); ?>

    <p class="modal-message">Do you really want to delete <strong class='text-danger modal-name'></strong>?</p>

<?php Modal::end(); ?>
