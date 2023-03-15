<?php
require "../connection.php";
if (!empty($_POST)) {
    $id = isset($_POST['id']) ?  $_POST['id'] : "";

    $conn = new Connection();
    $db = $conn->getInstance();

    $stmt = $db->prepare("SELECT * FROM service_package WHERE id='$id' AND active=1 LIMIT 1");

    if($stmt->execute()){
        if($stmt->rowCount()>0){
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        }
    }else{

    }

}
    