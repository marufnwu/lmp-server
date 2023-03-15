<?php 
require_once 'connect.php';

$UserID = $_REQUEST["UserId"];
$ACposition = $_REQUEST["ACposition"];
$PlanType = $_REQUEST["PlanType"];



if($ACposition != "0"){
$random_chars = '0123456789';
$keyOne = substr(str_shuffle($random_chars), 0, 4);
$keyTwo = substr(str_shuffle($random_chars), 0, 4);
$keyThree = substr(str_shuffle($random_chars), 0, 4);

$final_license = $keyOne."-".$keyTwo."-".$keyThree;

        $CheckLogin = "SELECT * FROM paid_license WHERE user_id='$UserID' AND plan_type=$PlanType ";
        $ResultLogin = mysqli_fetch_array(mysqli_query($connn,$CheckLogin));
        if(isset($ResultLogin)){
        $queryLice = "UPDATE paid_license SET status='1' WHERE user_id='$UserID' AND plan_type=$PlanType";
        if(mysqli_query($connn, $queryLice)){
            $response = array();
            array_push($response,
                array('status'=>"success"));
            echo json_encode($response);
        }
        }else{
           
           $startedDate = round(microtime(true) * 1000);
           $expiredDate = round(microtime(true) * 1000)+31556952000;
            
        $licenseSql = "INSERT INTO paid_license (license_number, started_date, expire_date, user_id,plan_type) VALUES ('$final_license', '$startedDate', '$expiredDate', '$UserID',$PlanType)";
if(mysqli_query($connn, $licenseSql)){
    $response = array();
    array_push($response,
        array('status'=>"success"));
    echo json_encode($response);
}

            
        }
    
}else{
  $licenseSql = "UPDATE paid_license SET status='0' WHERE user_id='$UserID' AND plan_type=$PlanType ";
      if(mysqli_query($connn, $licenseSql)){
          $response = array();
          array_push($response,
              array('status'=>"success"));
          echo json_encode($response);
      }  
}

?>