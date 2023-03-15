<?php 
require_once 'connect.php';

$userID = $_REQUEST["userId"];

     
            $query = "SELECT * FROM paid_license WHERE user_id='$userID' AND plan_type='1' AND status='1' ";
            $result = mysqli_query($connn, $query);
            $row = mysqli_fetch_assoc($result);
            
            $queryPro = "SELECT * FROM paid_license WHERE user_id='$userID' AND plan_type='2' AND status='1' ";
            $resultPro = mysqli_query($connn, $queryPro);
            $rowPro = mysqli_fetch_assoc($resultPro);
            
            $query_paid = "SELECT * FROM tbl_service_plan WHERE id='1' ";
            $result_paid = mysqli_query($connn, $query_paid);
            $row_paid = mysqli_fetch_assoc($result_paid);
            
            $query_pro = "SELECT * FROM tbl_service_plan WHERE id='3' ";
            $result_pro = mysqli_query($connn, $query_pro);
            $row_pro = mysqli_fetch_assoc($result_pro);
            
            $query_free = "SELECT * FROM tbl_service_plan WHERE id='2' ";
            $result_free = mysqli_query($connn, $query_free);
            $row_free = mysqli_fetch_assoc($result_free);
            
            $startedDate = date("m-d-Y", $row['started_date']/1000);
            $expiredDate = date("m-d-Y", $row['expire_date']/1000);
            
            $startedDatePro = date("m-d-Y", $rowPro['started_date']/1000);
            $expiredDatePro = date("m-d-Y", $rowPro['expire_date']/1000);
            
            $response = array();
            array_push($response, 
            array( 
                'license_number_pro'=>$rowPro['license_number'],
                'started_date_pro'=>$startedDatePro,
                'expire_date_pro'=>$expiredDatePro,
                'license_number'=>$row['license_number'],
                'started_date'=>$startedDate,
                'expire_date'=>$expiredDate,
                'free_name'=>$row_free['name'],
                'free_price'=>$row_free['price'],
                'paid_name'=>$row_paid['name'],
                'paid_price'=>$row_paid['price'],
                'pro_name'=>$row_pro['name'],
                'pro_price'=>$row_pro['price']) 
            );
        
        echo json_encode($response);   
 
        
        
            

?>