<?php
include_once("../class/class.helper.php");
$userId = isset($_GET['userId']) ? $_GET['userId'] : '';
$phone = isset($_GET['phone']) ? $_GET['phone'] : '';
$versionCode = isset($_GET['versionCode']) ? $_GET['versionCode'] : '';
$versionName = isset($_GET['versionName']) ? $_GET['versionName'] : '';
$androidVersion = isset($_GET['androidVersion']) ? $_GET['androidVersion'] : '';
$device = isset($_GET['device']) ? $_GET['device'] : '';
$deviceType = isset($_GET['deviceType']) ? $_GET['deviceType'] : '';
$manufacturer = isset($_GET['manufacturer']) ? $_GET['manufacturer'] : '';
$screenDensity = isset($_GET['screenDensity']) ? $_GET['screenDensity'] : '';
$screenSize = isset($_GET['screenSize']) ? $_GET['screenSize'] : '';

    $helper = new helper();
    $result = $helper->addDeviceData($userId,$phone  ,  $versionCode  ,  $versionName  ,  $androidVersion  ,  $device  ,  $deviceType  ,  $manufacturer  ,  $screenDensity  ,  $screenSize);

    echo json_encode($result);
    exit;