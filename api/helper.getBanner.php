<?php
include_once("../class/class.helper.php");
$bannerName = isset($_GET['bannerName']) ? $_GET['bannerName'] : '';

    $helper = new helper();
    $result = $helper->getActivityBanner($bannerName);

    echo json_encode($result);
    exit;