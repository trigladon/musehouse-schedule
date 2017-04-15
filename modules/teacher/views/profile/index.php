<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 09.04.17
 * Time: 16:06
 */

use yii\helpers\Html;

$this->title = 'Profile';
?>

<div class="teacher-profile">
    <h1><?= Html::encode($this->title) ?></h1>

</div>
    <div class="container">
        <div class='col-md-5'>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker6'>
                    <input type='text' class="form-control" />
                    <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
                </div>
            </div>
        </div>
        <div class='col-md-5'>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker7'>
                    <input type='text' class="form-control" />
                    <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
                </div>
            </div>
        </div>
        <div class="totalTime"></div>
    </div>

<?php

?>