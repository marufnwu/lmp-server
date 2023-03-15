<?php
include_once("../class/class.helper.php");

$helper = new helper();
$result = $helper->getFbSharecontent();

echo json_encode($result);
exit;