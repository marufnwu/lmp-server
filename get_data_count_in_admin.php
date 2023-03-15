<?php 
require_once 'connect.php';

        $currentDate = round(microtime(true) * 1000);

        $query_premium = "SELECT * FROM paid_license WHERE plan_type='1' AND expire_date > '$currentDate' AND status='1' ";
        $result_premium = mysqli_query($connn, $query_premium);
        
        $query_standerd = "SELECT * FROM user_info_table WHERE ac_position='1' ";
        $result_standerd = mysqli_query($connn, $query_standerd);
        
        $query_logged_in = "SELECT * FROM user_info_table WHERE ActiveStatus='1' AND ac_position='1' ";
        $result_logged_in = mysqli_query($connn, $query_logged_in);
        
        $query_logged_out = "SELECT * FROM user_info_table WHERE ActiveStatus='0' AND ac_position='1' ";
        $result_logged_out = mysqli_query($connn, $query_logged_out);
        
        $query_expire_license = "SELECT * FROM paid_license WHERE (plan_type='1' OR plan_type='2') AND expire_date < '$currentDate' AND status='1' ";
        $result_expire_license = mysqli_query($connn, $query_expire_license);
        
        $query_disable = "SELECT * FROM user_info_table WHERE ac_position='0' ";
        $result_disable = mysqli_query($connn, $query_disable);
        
        $query_pro = "SELECT * FROM paid_license WHERE plan_type='2' AND expire_date > '$currentDate' AND status='1' ";
        $result_pro = mysqli_query($connn, $query_pro);

        $query_4digit = "SELECT * FROM paid_license WHERE plan_type='3' AND expire_date > '$currentDate' AND status='1' ";
        $result_4digit = mysqli_query($connn, $query_4digit);

        $query_last_digit = "SELECT * FROM paid_license WHERE plan_type='4' AND expire_date > '$currentDate' AND status='1' ";
        $result_last_digit = mysqli_query($connn, $query_last_digit);

        $query_middle_part = "SELECT * FROM paid_license WHERE plan_type='5' AND expire_date > '$currentDate' AND status='1' ";
        $result_middle_part = mysqli_query($connn, $query_middle_part);

        $query_middle_plays_more = "SELECT * FROM paid_license WHERE plan_type='6' AND expire_date > '$currentDate' AND status='1' ";
        $result_middle__plays_more = mysqli_query($connn, $query_middle_plays_more);
      
        
        $response = array();
            array_push($response, 
            array( 
                'premium_users'=>mysqli_num_rows($result_premium), 
                'standerd_users'=>mysqli_num_rows($result_standerd),
                'logged_in_users'=>mysqli_num_rows($result_logged_in),
                'logged_out_users'=>mysqli_num_rows($result_logged_out),
                'logged_expire_users'=>mysqli_num_rows($result_expire_license), 
                'disabled_users'=>mysqli_num_rows($result_disable),
                'pro_users'=>mysqli_num_rows($result_pro),
                'four_digit_users'=>mysqli_num_rows($result_4digit),
                'last_digit_users'=>mysqli_num_rows($result_last_digit),
                'middle_part_users'=>mysqli_num_rows($result_middle_part),
                'middle_plays_more_users'=>mysqli_num_rows($result_middle__plays_more),
                ) 
            );
        
        echo json_encode($response);   
   

?>