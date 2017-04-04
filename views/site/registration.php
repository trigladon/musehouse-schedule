<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 30.08.16
 * Time: 4:43
 */



use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\AuthItemChild;

/* @var $this yii\web\View */
/* @var $model app\models\RegForm */
/* @var $form ActiveForm */

$this->title = 'User Registration';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill in the following fields to Register a new User:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'reg-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]);
    ?>

    <?= $form->field($model, 'first_name')->label(false)->textInput([
        'placeholder' => 'First Name',
    ]);
    ?>

    <?= $form->field($model, 'last_name')->label(false)->textInput([
        'placeholder' => 'Last Name',
    ]);
    ?>

    <?= $form->field($model, 'password')->label(false)->passwordInput([
        'placeholder' => 'Password',
    ]);
    ?>



    <div class="form-group">
        <div class="col-lg-11">
            <?= Html::submitButton('Register', ['class' => 'btn btn-success', 'name' => 'reg-button'])?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
//var_dump(AuthItemChild::role_list());
//var_dump($_POST);

?>