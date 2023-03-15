<?php
require_once("../class/class.lotttery.php");
require_once("../class/class.helper.php");

if (!empty($_POST)) {
    $startNumber = isset($_POST['startNumber']) ? $_POST['startNumber'] : "";
    $endNumber = isset($_POST['endNumber']) ? $_POST['endNumber'] : "";

    $lottery = new lottery();

    echo json_encode(
        $lottery->get10DaysOldNumber($startNumber, $endNumber)
    );
} else {
    echo json_encode(
        helper::errorResponse("Request Not Permitted")
    );
}
