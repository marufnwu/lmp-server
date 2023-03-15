<?php
require_once("../class/class.lotttery.php");
require_once("../class/class.helper.php");

if (!empty($_POST)) {
    $startNumber = isset($_POST['startNumber']) ? $_POST['startNumber'] : "";
    $endNumber = isset($_POST['endNumber']) ? $_POST['endNumber'] : "";
    $userId = isset($_POST['userId']) ? $_POST['userId'] : 1001;

    $lottery = new lottery();

    echo json_encode(
        $lottery->get10DaysOldNumberV2($startNumber, $endNumber, $userId)
    );
} else {
    echo json_encode(
        helper::errorResponse("Request Not Permitted")
    );
}
