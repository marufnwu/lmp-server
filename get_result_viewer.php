<?php
require_once 'connect.php';
require_once 'connection.php';
require_once __DIR__."/class/mongodbRepo.php";

$UserId = $_REQUEST["userId"];
$WinTime = $_REQUEST["WinTime"];
$WinDate = $_REQUEST["WinDate"];

$response = array();
        // array_push($response, array( 
        //     'viewer_count'=>1000000
        // ));
        // echo json_encode($response);

        // exit();

        $ConnectionObj = new Connection();
        $conn = $ConnectionObj->getInstance();

        $key = $WinDate."_".$WinTime;
        
    
        $stmt = $conn->prepare("INSERT INTO tbl_result_viewer (WinDate, WinTime, user_id) VALUES('$WinDate', '$WinTime', '$UserId' ) ");
       // $stmt->execute();

       $response = array();
        array_push($response, array(
            'viewer_count'=>rand(88888, 111111)
        ));

     
           echo json_encode($response);

       die();



        $stmt = $conn->prepare("SELECT COUNT(1) AS views FROM tbl_result_viewer WHERE WinDate='$WinDate' AND WinTime='$WinTime'");
        //$stmt = $conn->prepare("SELECT views AS views FROM result_view WHERE windateTime= '$key' LIMIT 1");
        if($stmt->execute() && $stmt->rowCount() > 0){

        

            $response = array();
            array_push($response, array( 
                'viewer_count'=>$stmt->fetch(PDO::FETCH_ASSOC)['views']
            ));


            // $stmt = $conn->prepare("UPDATE result_view SET views = views+1 WHERE winDateTime='$key' LIMIT 1");
            // $stmt->execute();

            echo json_encode($response);
        }else{

            // $stmt = $conn->prepare("INSERT INTO result_view (windateTime, views, windate, wintime) VALUES('$key', 1, '$WinDate', '$WinTime')");
            // $stmt->execute();
    

            $response = array();
            array_push($response, array( 
                'viewer_count'=>0
            ));
            echo json_encode($response);
        }
