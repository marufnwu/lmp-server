<?php 
require_once 'connect.php';

$type = $_REQUEST["UserId"];
$PlanType = $_REQUEST["PlanType"];

        $query = "SELECT * FROM paid_license WHERE user_id='$type' AND plan_type='$PlanType'";
        $result = mysqli_query($connn, $query);
        $row = mysqli_fetch_assoc($result);
        $response = array();
       
       $startedDate = date("m-d-Y", $row['started_date']/1000);
       $expiredDate = date("m-d-Y", $row['expire_date']/1000);
       
            array_push($response, 
            array( 
                'id'=>$row['id'], 
                'license_number'=>$row['license_number'],
                'started_date'=>$startedDate,
                'expire_date'=>$expiredDate) 
            );
        
      echo json_encode($response);   
   

?>