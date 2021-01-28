<?php

$successMsg = $this->session->flashdata('success-msg');
if ($successMsg) {
    echo'<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong>';
    echo ' ' . $successMsg;
    echo'</div>';
}
$errorMsg = $this->session->flashdata('error-msg');
if (isset($errorMsg)) {
    echo'<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!</strong>';
    echo ' ' . $errorMsg;
    echo'</div>';
}
