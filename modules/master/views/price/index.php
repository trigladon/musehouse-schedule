<?php

$this->title = 'Price Management';

?>
<h1><?=$this->title ?></h1> <span class="btn btn-success" role="button" onclick="setPricePolicy()">Add <i class="fa fa-plus" aria-hidden="true"></i></span>

<?php require '../templates/priceManagementFormModal.php'?>