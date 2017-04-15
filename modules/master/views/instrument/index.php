<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 04.04.17
 * Time: 4:57
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\modules\master\forms\LessonForm */
/* @var $modelUpdate app\modules\master\forms\LessonUpdateForm */
/* @var $form ActiveForm */

$this->title = 'Lessons';
?>
<div class="master-instrument">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-3">
            <?php $form = ActiveForm::begin([
                'id' => 'instrument-form',
                'layout' => 'horizontal',
                'class' => 'form-inline',
                'options' => ['enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
            ]); ?>

            <?= $form->field($model, 'icon')->label(false)->fileInput([
                    'content' => 'Choose SVG icon'
            ]) ?>
            <?= $form->field($model, 'lessonName')->label(false)->textInput([
                    'placeholder' => 'Enter the name of the lesson'
            ])?>


            <div class="form-group">
                <div class="col-lg-11">
                    <?= Html::submitButton('Add Lesson', ['class' => 'btn btn-success'])?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
            <p class="text-warning">Only files of <strong>PNG extension</strong> is possible to use for Icons!</p>
        </div>
        <div  class="col-md-9">
            <table class="table table-hover table-striped table-bordered">
                <tr><td colspan="7" style="color: #2e498b; font-size: 18px; border-bottom-width: 2px; border-bottom-color: #2e498b;">Table of Lessons</td></tr>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Icon</th>
                    <th class="text-center">Lesson Name</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Delete</th>
                </tr>
                <?php
                $number = 1;
                foreach ($lessonsList as $key => $value){

                    echo '<tr>';
                    echo '<td style="vertical-align: middle">'.$number.'</td>';
                    echo '<td style="vertical-align: middle"><img src="/images/icons/'.$value['icon'].'" class="icon_table"></td>';
                    echo '<td style="vertical-align: middle">'.$value['instr_name'].'</td>';
                    echo '<td  style="vertical-align: middle" class="text-center">'.
                        Html::a('<i class="fa fa-pencil-square-o fa-lg text-warning" aria-hidden="true"></i>',
                        Yii::$app->urlManager->createAbsoluteUrl([
                            '/master/instrument',
                        ]),
                            [
                            'class'       => 'popup-update linkaction',
                            'data-id' => $key,
                            'data-name' => $value['instr_name'],
                            'id'          => 'popup-update',
])
                        .'</td>';

                    echo '<td style="vertical-align: middle" class="text-center">'.Html::a('<i class="fa fa-trash-o fa-lg text-danger" aria-hidden="true"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                            '/master/instrument',
                        ]), [
                            'class'       => 'popup-delete linkaction',
                            'data-target' => '#modal',
                            'data-id' => $key,
                            'data-name' => $value['instr_name'],
                            'data-icon' => $value['icon'],
                            'id'          => 'popup-delete',]).'</td>';
                    echo '</tr>';
                    $number++;
                }
                ?>
            </table>
        </div>
    </div>
    <div>
        <?php

        var_dump(Yii::$app->user->identity->getId());
        var_dump($list);
        var_dump($list2);
        var_dump($lessonsList);

        ?>


    </div>
    <?php Modal::begin([
        'header' => '<h3 class="text-warning"><i class="icon fa fa-exclamation-triangle"></i> Warning!</h3>',
        'id'     => 'modal-delete',
        'size' => 'modal-sm',
        'footer' => Html::a('Delete', '', ['class' => 'btn btn-danger', 'id' => 'delete-confirm']),
    ]); ?>

        <p class="modal-message">Do you really want to delete <strong class='text-danger modal-name'></strong>?</p>

    <?php Modal::end(); ?>



    <?php Modal::begin([
        'header' => '<h3 class="text-warning"><i class="icon fa fa-exclamation-triangle"></i> Warning!</h3>',
        'id'     => 'modal-update',
        'size' => 'modal-sm',
//        'footer' => Html::a('Update', '', ['class' => 'btn btn-danger', 'id' => 'update-confirm']),
//        'footer' => false,
    ]);

    $form = ActiveForm::begin([
    'id' => 'upinstr-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    <p>Enter the new name of the lesson:</p>
    <?= $form->field($modelUpdate, 'lessonUpName')->label(false)->textInput([
//        'value' => 'Enter the name of the lesson',
        'class' => 'upfield form-control',
    ])?>

    <?= Html::activeHiddenInput($modelUpdate,'idUpName', [
        'class' => 'upfieldId',
    ]);?>

    <div class="form-group">
        <div class="col-lg-11">
            <?= Html::submitButton('Update', ['class' => 'btn btn-success', 'id' => 'update-confirm'])?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Modal::end(); ?>

</div><!-- master-instrument -->