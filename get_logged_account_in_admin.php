<?php 
require_once 'connect.php';

$UserID = $_REQUEST["UserId"];
$ACposition = $_REQUEST["ACposition"];

        $query = "UPDATE user_info_table SET ActiveStatus='$ACposition' WHERE Id='$UserID'";
        if(mysqli_query($connn, $query)){
           $response = array();
            array_push($response, 
            array('status'=>"success"));
         echo json_encode($response);  
        }
        
          
   

?>