<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SendRecoveryEmailForm */
/* @var $form ActiveForm */
?>
<div class="site-sendRecoveryEmail">

    <?php $form = ActiveForm::begin([
        'id' => 'sendRecoveryEmail-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'email')->label(false)->input('email', [
            'placeholder' => 'Email',
        ]) ?>

    <div class="form-group">
        <div class="col-lg-11">
            <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div><!-- site-sendRecoveryEmail -->
