<div>
    <?php if (Yii::$app->session->hasFlash('Error')): ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-exclamation-triangle"></i> Warning!</h4>
            <?= Yii::$app->session->getFlash('Error') ?>
        </div>
    <?php elseif (Yii::$app->session->hasFlash('Success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-hand-peace-o"></i> Success!</h4>
            <?= Yii::$app->session->getFlash('Success') ?>
        </div>
    <?php elseif (Yii::$app->session->hasFlash('Warning')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-close"></i> Error!</h4>
            <?= Yii::$app->session->getFlash('Warning') ?>
        </div>
    <?php endif;?>
</div>