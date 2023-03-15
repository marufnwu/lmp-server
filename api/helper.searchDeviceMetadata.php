<?php
include_once("../class/class.helper.php");
$androidVersion = isset($_GET['androidVersion']) ? $_GET['androidVersion'] : '';
$device = isset($_GET['device']) ? $_GET['device'] : '';
$manufacturer = isset($_GET['manufacturer']) ? $_GET['manufacturer'] : '';
$screenDensity = isset($_GET['screenDensity']) ? $_GET['screenDensity'] : '';
$screenSize = isset($_GET['screenSize']) ? $_GET['screenSize'] : '';

$helper = new helper();
$result = $helper->searchDeviceData($androidVersion,  $device,    $manufacturer,  $screenDensity,  $screenSize);

echo json_encode($result);
exit;
