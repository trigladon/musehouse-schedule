<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 25.04.17
 * Time: 19:00
 */
ini_set('xdebug.var_display_max_depth', 15);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
use yii\helpers\Html;


?>
<div class="text-left col-md-12" style="margin-bottom: 10px">
    <div class="btn btn-info pull-left" onclick="onClickChangeMonth('<?=$monthsToShow['currentMonth']?>', ' -1 month')">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </div>
    <div class="pull-left text-center" style="width: 300px; line-height: 33px; vertical-align: middle; font-size: larger; font-weight: bold">
        <?=$monthsToShow['info']?>
    </div>
    <div class="btn btn-info pull-left" onclick="onClickChangeMonth('<?=$monthsToShow['currentMonth']?>', ' +1 month')">
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </div>
</div>

<?php if(empty($statisticsData)){echo 'No lessons for this Month - '.$monthsToShow['info'];}?>

<?php foreach ($statisticsData as $teacherId => $monthResult):?>
    <?php $teacherName = $teachersList[$teacherId]?>
    <?php $stQnt = count($statisticsData[$teacherId]['students'])?>

<table class="statTable" style="margin-top: 10px">
    <thead>
        <tr style="height: 35px; border-bottom: 2px solid #dbdbdb">
            <?php foreach ($statisticsData[$teacherId]['monthResult'] as $status):?>
            <th class="cellToMiddle cellTotalMonth" style="color:<?=$status['color']?>"><?=$status['qnt_lessons']?></th>
            <?php endforeach;?>
            <?php foreach ($lessonPerTeacher[$teacherId] as $lesson):?>
            <th class="cellToMiddle cellLesson" colspan="4"><img src="/images/icons/<?=$lesson['icon']?>" class="icon_reg"><?=$lesson['name']?></th>
            <th class="cellTotalUser cellToMiddle"><img src="/images/sum-sign.png" style="width: 20px;"></th>
            <?php endforeach;?>
        </tr>
    </thead>

    <tfoot>
        <tr style="height: 35px">
            <th colspan="4" class="cellToMiddle" style="background-color:#f8f8ff; width: 320px">Total:</th>
            <?php foreach ($statisticsData[$teacherId]['monthResultPerLesson'] as $lesson):?>
                <?php $summ = 0;?>
                <?php foreach ($lesson as $qntSt):?>
                    <td class="cellToMiddle cellLessonStatus cellTotalLesson"><?=$qntSt['qnt_lessons']?></td>
                    <?php $summ+=$qntSt['qnt_lessons']?>
                <?php endforeach;?>
                <td class="cellTotalUser cellToMiddle" style="font-size: larger"><?=$summ?></td>
            <?php endforeach;?>
        </tr>
    </tfoot>
    <tbody>
        <tr style="height: 35px">
            <td class="cellStTeach" colspan="2" rowspan="<?=$stQnt?>"><?=$teacherName?></td>
            <?php $trCl = 0?>
            <?php foreach ($statisticsData[$teacherId]['students'] as $studId => $student):?>
            <?php ++$trCl?>
            <?php if($trCl!=1){echo '<tr style="height: 35px">';}?>
                <td class="cellStTeach" colspan="2"><?=$studId?$studentsList[$studId]:'No Student'?></td>
                <?php foreach ($student as $lesson):?>
                    <?php $summ = 0;?>
                    <?php foreach ($lesson as $qntSt):?>
                        <td class="cellToMiddle cellLessonStatus" style="color:<?=$qntSt['color']?>"><?=$qntSt['qnt_lessons']?></td>
                        <?php $summ+=$qntSt['qnt_lessons']?>
                    <?php endforeach;?>
                    <td class="cellTotalUser cellToMiddle"><?=$summ?></td>
                <?php endforeach;?>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php endforeach;?>
<div style="margin-top: 10px">
    <?= Html::a('<i class="fa fa-file-pdf-o" aria-hidden="true"></i> To PDF', ['statistics/statopdf?toShow='.$monthsToShow['currentMonth']], [
        'class' => 'btn btn-primary',
        'target'=>'_blank',
//        'data-toggle'=>'tooltip',
        'title'=>'Generate PDF file with statistics',
    ]) ?>
</div>