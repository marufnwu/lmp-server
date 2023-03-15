<?php
require_once 'connect.php';

$type = $_REQUEST["UserId"];
$PlanType = $_REQUEST["PlanType"];

$QueryCK = "SELECT * FROM paid_license WHERE user_id='$type' AND plan_type='$PlanType'";
$result = mysqli_query($connn, $QueryCK);
$row = mysqli_fetch_assoc($result);

if ($row) {
   $expiredDate = 2555949600000;
   $query = "UPDATE paid_license SET expire_date='$expiredDate' WHERE user_id='$type' AND plan_type='$PlanType' ";
   if (mysqli_query($connn, $query)) {
      
      echo json_encode(array(
         "error"=>false,
         "msg"=>"Success"
      ));
   }
} else {
   echo json_encode(array(
      "error"=>true,
      "msg"=>"Failed"
   ));
}
