<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ResetPasswordForm */
/* @var $form ActiveForm */
?>
<div class="site-resetPassword">

    <?php $form = ActiveForm::begin([
        'id' => 'resetPassword-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'password')->label(false)->passwordInput([
            'placeholder' => 'Password',
        ]) ?>
    
        <div class="form-group">
            <?= Html::submitButton('Change', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-resetPassword -->
