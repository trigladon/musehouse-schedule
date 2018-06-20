<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

?>
<?php Modal::begin([
    'header' => '<h3 class="text-warning"><i class="icon fa fa-exclamation-triangle"></i> Warning!</h3>',
    'id'     => 'modal-delete',
    'size' => 'modal-sm',
    'footer' => Html::a('Delete', '', ['class' => 'btn btn-danger', 'id' => 'delete-confirm']),
]); ?>

    <p class="modal-message">Do you really want to delete <strong class='text-danger modal-name'></strong>?</p>

<?php Modal::end(); ?>