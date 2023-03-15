<?php
require_once("../connection.php");
class plan{
    private $db;
    function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
        
    }

    public function setLisence($planId, $userId, $validity=0){
        $dayInMills = 86400000;
        $stmtCheckLisc = $this->db->prepare("SELECT id FROM paid_license WHERE user_id=(:userId) AND plan_type=(:planId) LIMIT 1");
        
        $stmtCheckLisc->bindParam(":planId", $planId, PDO::PARAM_INT);
        $stmtCheckLisc->bindParam(":userId", $userId, PDO::PARAM_INT);

        $startedDate = round(microtime(true) * 1000);
        $validityInMills = $dayInMills*$validity;
        $expiredDate = $startedDate+$validityInMills;

        $error = true;
        file_put_contents('license_error.log', PHP_EOL . $userId." setLisence", FILE_APPEND);

        if($stmtCheckLisc->execute()){
            if($stmtCheckLisc->rowCount()>0){
                //update
                file_put_contents('license_error.log', PHP_EOL . $userId." Update callled", FILE_APPEND);

                $update = $this->db->prepare("UPDATE paid_license set started_date=(:sdate), expire_date=(:edate), status=1 WHERE user_id=(:userId) AND plan_type=(:planId)");
                
                $update->bindParam(":sdate", $startedDate, PDO::PARAM_STR);
                $update->bindParam(":edate", $expiredDate, PDO::PARAM_STR);
                $update->bindParam(":userId", $userId, PDO::PARAM_INT);
                $update->bindParam(":planId", $planId, PDO::PARAM_INT);

                if($update->execute()){
                    file_put_contents('license_error.log', PHP_EOL . $userId." Update true", FILE_APPEND);

                    $error = false;
                }else{
                    file_put_contents('license_error.log', PHP_EOL . $userId." Update error", FILE_APPEND);

                    return array(
                        "error"=>true,
                        "msg"=>"Error in update"
                    );
                }
            }else{
                //insert
                file_put_contents('license_error.log', PHP_EOL . $userId." Inser called", FILE_APPEND);

                $random_chars = '0123456789';
                $keyOne = substr(str_shuffle($random_chars), 0, 4);
                $keyTwo = substr(str_shuffle($random_chars), 0, 4);
                $keyThree = substr(str_shuffle($random_chars), 0, 4);

                $final_license = $keyOne."-".$keyTwo."-".$keyThree;
                $insert = $this->db->prepare("INSERT INTO paid_license (license_number, started_date, expire_date, status, user_id, plan_type) 
                VALUES(:license_number, :started_date, :expire_date, 1, :user_id, :plan_type)");

                $insert->bindParam(":license_number", $final_license, PDO::PARAM_STR);
                $insert->bindParam(":started_date", $startedDate, PDO::PARAM_STR);
                $insert->bindParam(":expire_date", $expiredDate, PDO::PARAM_STR);
                $insert->bindParam(":user_id", $userId, PDO::PARAM_INT);
                $insert->bindParam(":plan_type", $planId, PDO::PARAM_INT);

                if($insert->execute()){
                    file_put_contents('license_error.log', PHP_EOL . $userId."Insert true", FILE_APPEND);

                    $error = false;
                }else{
                    file_put_contents('license_error.log', PHP_EOL . $userId." Insert error", FILE_APPEND);

                    return array(
                        "error"=>true,
                        "msg"=>"Error in insert"
                    );
                }
            }

            if(!$error){

                file_put_contents('license_error.log', PHP_EOL . $userId."Not in error", FILE_APPEND);


                //1=paid_license/vip
                //2=pro
                //3=4digit
                //4=last_digit_license
                //5=middle_part_license
                //6=middle_plays_more_license


                if($planId==1){
                    $stmt = $this->db->prepare("UPDATE user_info_table SET paid_license=1 WHERE Id=(:userId)");
                }else if($planId == 2){
                    $stmt = $this->db->prepare("UPDATE user_info_table SET pro_license=1 WHERE Id=(:userId)");
                }else if($planId == 3){
                    $stmt = $this->db->prepare("UPDATE user_info_table SET 4digit_license=1 WHERE Id=(:userId)");
                }else if($planId == 4){
                    $stmt = $this->db->prepare("UPDATE user_info_table SET last_digit_license=1 WHERE Id=(:userId)");
                }else if($planId == 5){
                    $stmt = $this->db->prepare("UPDATE user_info_table SET middle_part_license=1 WHERE Id=(:userId)");
                }else if($planId == 6){
                    $stmt = $this->db->prepare("UPDATE user_info_table SET middle_plays_more_license=1 WHERE Id=(:userId)");
                }
                $stmt->bindParam(":userId", $userId, PDO::PARAM_STR);

                if($stmt->execute()){

                    file_put_contents('license_error.log', PHP_EOL . $userId."User info updated", FILE_APPEND);


                    return array(
                        "error"=>false,
                        "msg"=>"License Added"
                    );
                }else{

                    file_put_contents('license_error.log', PHP_EOL . $userId." ".$stmt->errorInfo(), FILE_APPEND);


                    return array(
                        "error"=>true,
                        "msg"=>"Final"
                    );
                }
            }else{
                return array(
                    "error"=>true,
                    "msg"=>"Something went wrong"
                );
            }
        }else{
            return array(
                "error"=>true,
                "msg"=>"sss"
            );
        }
    }
}