<?php

/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <?php if (Yii::$app->session->hasFlash('error_time_expired')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-close"></i>Error!</h4>
            <?= Yii::$app->session->getFlash('error_time_expired') ?>
        </div>
    <?php elseif (Yii::$app->session->hasFlash('error_key')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-close"></i>Error!</h4>
            <?= Yii::$app->session->getFlash('error_key') ?>
        </div>
    <?php elseif (Yii::$app->session->hasFlash('error_key_not_found')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-close"></i>Error!</h4>
            <?= Yii::$app->session->getFlash('error_key_not_found') ?>
        </div>
    <?php elseif (Yii::$app->session->hasFlash('reg_succ')): ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-close"></i></h4>
            <?= Yii::$app->session->getFlash('reg_succ') ?>
        </div>
    <?php endif;?>

    <div class="jumbotron">
        <h1><img src="/images/musehouse_logo.jpg"></h1>

<!--        <p class="lead">Welcome to our music school!</p>-->

    </div>

    <div class="body-content">

        <div class="col-lg-12 text-center">
            <img src="/images/il_570xN.920698074_m80m.jpg" style="height: 500px">
        </div>

    </div>
</div>
