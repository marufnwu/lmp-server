<?php
require_once("../class/class.helper.php");

$helper = new helper();
echo json_encode($helper->getVideoType());