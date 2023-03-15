<?php 
require_once 'connect.php';


        $query_premium = "SELECT * FROM tbl_special_number WHERE id='1' ";
        $result_premium = mysqli_query($connn, $query_premium);
        $row_premium = mysqli_fetch_assoc($result_premium); 
        
        $response = array();
            array_push($response, 
            array( 
                'number_one'=>$row_premium['number_one'], 
                'number_two'=>$row_premium['number_two'],
                'number_three'=>$row_premium['number_three'],
                'number_four'=>$row_premium['number_four'],
                'number_five'=>$row_premium['number_five'], 

                'number_six'=>$row_premium['number_six'], 
                'number_seven'=>$row_premium['number_seven'], 
                'number_eight'=>$row_premium['number_eight'], 
                'number_nine'=>$row_premium['number_nine'], 
                'number_ten'=>$row_premium['number_ten'], 

                'upload_date'=>$row_premium['upload_date']) 
            );
        
        echo json_encode($response);   
   

?>