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


?>
<table  class="table">
<thead>
    <tr>
        <th colspan="2" class="text-left">
            <div class="btn btn-info pull-left" onclick="onClickChangeMonth('<?=$monthsToShow['currentMonth']?>', ' -1 month')">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </div>
            <div class="pull-left text-center" style="width: 200px; line-height: 33px; vertical-align: middle">
                <?=$monthsToShow['info']?>
            </div>
            <div class="btn btn-info pull-left" onclick="onClickChangeMonth('<?=$monthsToShow['currentMonth']?>', ' +1 month')">
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </div>
        </th>


        <?php for($r=1; $r<=3; $r++):?>
        <th style="width: 65px"><div class="dropBoxStatus img-rounded pull-left" style="background-color:#78909c; width: 100%"></div><br><div class="text-center" style="font-size: 10px">FreeTime<div></th>
        <th style="width: 65px"><div class="dropBoxStatus img-rounded pull-left" style="background-color:#42a5f5; width: 100%"></div><br><div class="text-center" style="font-size: 10px">Planned</div></th>
        <th style="width: 65px"><div class="dropBoxStatus img-rounded pull-left" style="background-color:#66bb6a; width: 100%"></div><br><div class="text-center" style="font-size: 10px">Finished</div></th>
        <th rowspan="2" class="text-center" style="background-color: #f8f8ff; border-right: 2px solid #ddd;"><img src="/images/sum-sign.png" style="width: 20px;"></th>
        <?php endfor;?>
    </tr>
    <tr>
        <th class="text-center">Teachers</th>
        <th class="text-center">Lessons</th>
        <?php foreach ($monthsToShow['months'] as $months => $years):?>
            <th colspan="3" class="text-center"><?=$months?> <?=$years?></th>
        <?php endforeach;?>
    </tr>
</thead>
<tbody>

<?php foreach ($statisticsData as $name => $info1):?>
    <?php
    $userName = $name;
    $rowQnt = count($info1);
    $i=1; // to calculate row qnt
    ?>
    <?php $statusQnt = [
        1 => [
            1 => 0,
            2 => 0,
            3 => 0,
        ],
        2 => [
            1 => 0,
            2 => 0,
            3 => 0,
        ],
        3 => [
            1 => 0,
            2 => 0,
            3 => 0,
        ]
    ];?>
    <?php foreach ($info1 as $lesson => $info2):?>
        <tr>
            <?php if ($i == 1):?>
                <td rowspan="<?=$rowQnt?>" style="vertical-align: middle"><?=$userName?></td>
            <?php endif;?>
            <?php if ($info2['name']): // todo fail array key=>value; same data, but key works and value not?>
                <td class="text-right"><?=$info2['name']?><img src="/images/icons/<?=$info2['icon']?>" class="icon_reg" style="margin: 0 5px"></td>
            <?php else:?>
                <td class="text-right">Free<i class="fa fa-question fa-lg icon_reg_action" aria-hidden="true" style="width: 20px;height: 20px;color: #78909c;margin: 0 5px; padding: 3px 5px 0 0"></i></td>
            <?php endif;?>

            <?php $monthIt = 1;?><!-- months iterations for total line calculation-->
            <?php foreach ($info2['data'] as $year => $info3):?>
                <?php foreach ($info3 as $month => $info4):?>
                    <?php foreach ($info4 as $results => $info5):?>
                        <?php $totalQnt = 0?>
                        <?php foreach ($info5 as $info6):?>
                            <td class="text-center" style="color: <?=$info6['color']?>; vertical-align: middle;"><?=$info6['qnt_lessons']?></td>
                            <?php $totalQnt += $info6['qnt_lessons']?>
                            <?php $statusQnt[$monthIt][$info6['id']] += $info6['qnt_lessons']?>
                        <?php endforeach;?>
                        <td class="text-center" style="color: #ff714f; vertical-align: middle; font-size: 15px; background-color: #f8f8ff; border-right: 2px solid #ddd;"><?=$totalQnt?></td>
                        <?php $monthIt++;?>
                    <?php endforeach;?>
                <?php endforeach;?>
            <?php endforeach;?>
        </tr>
        <?php $i++?>
    <?php endforeach;?>
    <tr class="text-center" style="color: #ff714f; vertical-align: middle; font-size: 15px; background-color: #f8f8ff; border-bottom: 2px solid #ddd;">
        <td colspan="2"><img src="/images/sum-sign.png" style="width: 20px;"></td>
        <?php foreach ($statusQnt as $qnt):?>
            <td><?=$qnt[1]?></td>
            <td><?=$qnt[2]?></td>
            <td><?=$qnt[3]?></td>
            <td style="border-right: 2px solid #ddd; font-size: 16px"><strong><?=$qnt[1]+$qnt[2]+$qnt[3]?></strong></td>
        <?php endforeach;?>
    </tr>
<?php endforeach;?>
</tbody>
</table>