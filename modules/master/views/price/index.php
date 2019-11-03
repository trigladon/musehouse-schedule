<?php

use app\models\User;
use app\modules\master\models\Instrument;
use app\modules\master\models\Userschedule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\web\JsExpression;

/** @var $unsetPriceLessons array */
/** @var $allSetPrices array */
/** @var $pages integer */
/** @var $priceObj \app\modules\master\models\StudentTeacherPricing */
/* @var array $studentList */
/* @var array $teacherList */
/* @var array $lessonList */

$this->title = 'Price Management';
$escape = new JsExpression("function(m) { return m; }");
?>
<h1><?=$this->title ?></h1>

<?php require Yii::getAlias('@app').'/templates/errorsTemplate.php'; ?>

<?php if($unsetPriceLessons): ?>
    <h3 class="text-danger">Unset prices for lessons</h3>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Teacher</th>
            <th>Student</th>
            <th>Lesson</th>
            <th class="text-center">Lesson Date</th>
            <th class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $counter = 0 ?>
        <?php foreach ($unsetPriceLessons as $item): ?>
        <?php
            $lessonDate = date('d-m-Y', $item['lessonTimeStart']);
            $dateObj = new DateTime($lessonDate);
            $dateObj->modify('first day of this month');
            $clickDate = $dateObj->format('d-m-Y');
        ?>
        <tr>
            <td class="verticalAl"><?= ++$counter ?></td>
            <td class="verticalAl"><?= $teacherList[$item['user_id']] ?></td>
            <td class="verticalAl"><?= $studentList[$item['student_id']] ?></td>
            <td class="verticalAl"><?= $lessonList[$item['instricon_id']]?></td>
            <td class="verticalAl text-center"><?= $lessonDate ?></td>
            <td class="verticalAl text-center">
                <span class="btn btn-success"
                      role="button"
                      onclick="setPricePolicy(<?=$item['user_id'].', '.$item['student_id'].', '.$item['instricon_id'].', \''.$clickDate.'\'' ?>)">
                    Add
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<hr>
<?php endif; ?>
    <span class="btn btn-success" role="button" onclick="showPricePolicy()">Add new <i class="fa fa-plus" aria-hidden="true"></i></span>
<hr>
    <h3 class="text-success">Set prices for lessons</h3>
    <div class="row">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => Url::toRoute('/pricing'),
        ]); ?>
        <div class="col-md-3 col-xs-12">
            <?= Select2::widget([
                'name' => 'filterTeacher',
                'data' => $teacherList,
                'value' => Yii::$app->request->get('filterTeacher')?:'',
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'Choose the Teacher'],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]) ?>
        </div>
        <div class="col-md-3 col-xs-12">
            <?= Select2::widget([
                'name' => 'filterStudent',
                'data' => $studentList,
                'theme' => Select2::THEME_BOOTSTRAP,
                'value' => Yii::$app->request->get('filterStudent')?:'',
                'options' => ['placeholder' => 'Choose the Student'],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]) ?>
        </div>
        <div class="col-md-3 col-xs-12">
            <?= Select2::widget([
                'name' => 'filterLesson',
                'data' => $lessonList,
                'value' => Yii::$app->request->get('filterLesson')?:'',
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'Choose the Instrument'],
                'pluginOptions' => [
                    'escapeMarkup' => $escape,
                    'allowClear' => true
                ]
            ]) ?>
        </div>
        <div class="col-md-3 text-left">
            <?= Html::submitButton('Apply filter <i class="fa fa-search"></i>', ['id' => 'button-search', 'class' => 'btn btn-success']) ?>
            <a href="/pricing" class="btn btn-primary" role="button">Clear filter</a>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php if ($allSetPrices): ?>
<hr>
<table class="table">
    <thead>
    <tr>
        <th class="text-center">#</th>
        <th class="text-center">Teacher</th>
        <th class="text-center">Student</th>
        <th class="text-center">Lesson</th>
        <th class="text-center">Target qnt</th>
        <th class="text-center">Info valid from</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php $counter = 0 ?>
    <?php foreach ($allSetPrices as $priceObj): ?>
    <tr style="border-top: 2px #2e498b solid;">
        <td class="text-center verticalAl text-muted" rowspan="4"><strong><?=++$counter ?></strong></td>
        <td><?=$teacherList[$priceObj->teacher_id] ?></td>
        <td><?=$studentList[$priceObj->student_id] ?></td>
        <td><?=$lessonList[$priceObj->instrument_id] ?></td>
        <td class="text-center"><?=$priceObj->target_qnt_lessons ?></td>
        <td class="text-center"><?=$priceObj->getDateFrom() ?></td>
        <td style="font-size: larger;" class="text-center verticalAl">
            <i onclick="editPrice(<?=$priceObj->id ?>)" class="fa fa-pencil-square-o text-warning cursor" aria-hidden="true"></i> /
            <a href="" class="popup-delete-price" data-id = "<?=$priceObj->id ?>"><i class="fa fa-trash-o text-danger cursor" style="vertical-align: 7%;" aria-hidden="true"></i></a>
        </td>
    </tr>
    <tr style="border-top: 2px solid #d3d3d3;">
        <th class="text-center" colspan="2" style="width:33%;">
            <?=Userschedule::LESSON_SHORT ?> minutes lesson (short)
        </th>
        <th class="text-center" colspan="2" style="width:33%">
            <?=Userschedule::LESSON_MIDDLE ?> minutes lesson (middle)
        </th>
        <th class="text-center" colspan="2" style="width:33%">
            <?=Userschedule::LESSON_LONG ?> minutes lesson (long)
        </th>
    </tr>
    <tr>
        <td rowspan="2" class="text-center verticalAl text-primary" style="font-weight: bold">
            <span style="font-size: small">(full: clean rate + tax)</span><br>
            <span style="font-size: large;">
                <?=$priceObj->short_full_money ?>
            </span> <small>CZK</small>
        </td>
        <td class="text-center text-info">
            <span style="font-size: small">(clean rate)</span><br>
            <span>
                <?=$priceObj->short_clean_money ?>
            </span> <small>CZK</small>
        </td>
        <td rowspan="2" class="text-center verticalAl text-primary" style="font-weight: bold">
            <span style="font-size: small">(full: clean rate + tax)</span><br>
            <span style="font-size: large;">
                <?=$priceObj->middle_full_money ?>
            </span> <small>CZK</small>
        </td>
        <td class="text-center text-info">
            <span style="font-size: small">(clean rate)</span><br>
            <span>
                <?=$priceObj->middle_clean_money ?>
            </span> <small>CZK</small>
        </td>
        <td rowspan="2" class="text-center verticalAl text-primary" style="font-weight: bold">
            <span style="font-size: small">(full: clean rate + tax)</span><br>
            <span style="font-size: large;">
                <?=$priceObj->long_full_money ?>
            </span> <small>CZK</small>
        </td>
        <td class="text-center text-info">
            <span style="font-size: small">(clean rate)</span><br>
            <span>
                <?=$priceObj->long_clean_money ?>
            </span> <small>CZK</small>
        </td>
    </tr>
    <tr>
        <td class="text-center text-info">
            <span style="font-size: small">(tax)</span><br>
            <span>
                <?=$priceObj->short_tax_money ?>
            </span> <small>CZK</small>
        </td>
        <td class="text-center text-info">
            <span style="font-size: small">(tax)</span><br>
            <span>
                <?=$priceObj->middle_tax_money ?>
            </span> <small>CZK</small>
        </td>
        <td class="text-center text-info">
            <span style="font-size: small">(tax)</span><br>
            <span>
                <?=$priceObj->long_tax_money ?>
            </span> <small>CZK</small>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="text-center">
    <?php echo \yii\widgets\LinkPager::widget([
        'pagination' => $pages,
        'nextPageLabel' => 'Следующая',
        'prevPageLabel' => 'Предыдущая',
        'lastPageLabel' => 'В конец',
        'firstPageLabel' => 'В начало',
    ]); ?>
</div>
<hr>
<?php else:?>
    <p>No data found...</p>
<?php endif; ?>
<?php require Yii::getAlias('@app').'/templates/priceManagementFormModal.php'?>
<?php require Yii::getAlias('@app').'/templates/deleteConfirmationModal.php'?>