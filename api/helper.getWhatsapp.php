<?php
include_once("../class/class.helper.php");
$place = isset($_GET['place']) ? $_GET['place'] : '';

    $helper = new helper();
    $result = $helper->getWhatsappNum($place);

    echo json_encode($result);
    exit;