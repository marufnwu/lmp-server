<?php
require_once("../connection.php");
require_once("class.license.php");
session_start();
class lottery
{
    private $db;
    public function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    public function getLotteryResultTime($isActiveCheck)
    {
        if (!helper::isAuth()) {
            return helper::errorResponse("Request Authentication Failed");
        }

        if ($isActiveCheck) {
            $stmt = $this->db->prepare("SELECT * FROM lottery_result_time WHERE active=1 ORDER BY id ASC");
        } else {
            $stmt = $this->db->prepare("SELECT * FROM lottery_result_time  ORDER BY id ASC");
        }

        if ($stmt->execute()) {
            $resultSlots = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $item = array(
                    "id" => (int)$row['id'],
                    "time" => $row['time'],
                    "active" => (bool)$row['active'],
                    "name" => $row['name'],
                );

                array_push($resultSlots, $item);
            }


            return array(
                "error" => false,
                "msg" => "Success",
                "resultSlots" => $resultSlots
            );
        } else {
            return helper::errorResponse($stmt->errorInfo());
        }
    }

    public function getLotteryUsingDateAndSlotId($date, $slotId)
    {
        if (!helper::isAuth()) {
            return helper::errorResponse("Request Authentication Failed");
        }
    }

    public function updateResultTimeSlot($id, $name, $time, $active)
    {
        if (!helper::isAuth()) {
            return helper::errorResponse("Request Authentication Failed");
        }

        $stmt = $this->db->prepare("UPDATE lottery_result_time SET time='$time', name='$name', active='$active' WHERE id='$id'");

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return helper::successResponse("Result Time Updated");
            } else {
                return helper::errorResponse("Result Time Not Updated");
            }
        } else {
            return helper::errorResponse($stmt->errorInfo());
        }
    }

    public function get10DaysOldNumber($startNumber, $endNumber)
    {
        if (!helper::isAuth()) {
            return helper::errorResponse("Request Authentication Failed");
        }

        if (strlen($startNumber) < 4 || strlen($endNumber) < 4) {
            return helper::errorResponse("Search Input Not Valid");
        }

        $startNumber = substr($startNumber, -4);
        $endNumber = substr($endNumber, -4);

        if (substr($startNumber, -4, 2) != substr($endNumber, -4, 2)) {
            return helper::errorResponse("Middle Not Matched");
        }

        $start = substr($startNumber, -2);
        $end = substr($endNumber, -2);
        $middle = substr($endNumber, -4, 2);

                $stmt = $this->db->prepare("SELECT LotteryNumber, SUBSTRING(LotteryNumber, -2, 2) AS last
                FROM lottery_number_table GROUP BY LotteryNumber 
                HAVING SUBSTRING(LotteryNumber, -4, 2) = '$middle' 
                AND SUBSTRING(LotteryNumber, -2, 2) >$start AND SUBSTRING(LotteryNumber, -2, 2) <$end 
                AND COUNT(CASE WHEN DATEDIFF(CURDATE() ,STR_TO_DATE(WinDate, '%d-%m-%Y')) <4 THEN 0 END)=0
                AND LENGTH(LotteryNumber)=4
                ORDER BY Id ASC");

        if ($stmt->execute()) {

            $seed = rand();

            if (isset($_SESSION['userId'])) {
                $seed = $_SESSION['userId'];
            }


            $numberList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            srand($seed);
            shuffle($numberList);

            $finalNumberList = array();

            foreach ($numberList as $key => $value) {
                $item = array(
                    "Id"=>"0",
                    "LotterySerialNumber"=>"0",
                    "WinType"=>"0",
                    "WinDate"=>"0",
                    "WinTime"=>"0",
                    "SlotId"=>"0",
                    "Name"=>"0",
                    "LotteryNumber"=>$value['LotteryNumber'],
                );

                array_push($finalNumberList, $item);
            }



            return array(
                "error" => false,
                "msg" => "" . $seed,
                "number" => $finalNumberList
            );
        } else {
            return helper::errorResponse($stmt->errorInfo());
        }
    }


    
    public function get10DaysOldNumberV2($startNumber, $endNumber, $userId)
    {
        if (!helper::isAuth()) {
            return helper::errorResponse("Request Authentication Failed");
        }

        if (strlen($startNumber) < 4 || strlen($endNumber) < 4) {
            return helper::errorResponse("Search Input Not Valid");
        }

        $startNumber = substr($startNumber, -4);
        $endNumber = substr($endNumber, -4);

        if (substr($startNumber, -4, 2) != substr($endNumber, -4, 2)) {
            return helper::errorResponse("Middle Not Matched");
        }

        $start = substr($startNumber, -2);
        $end = substr($endNumber, -2);
        $middle = substr($endNumber, -4, 2);

        $stmt = $this->db->prepare("SELECT LotteryNumber, SUBSTRING(LotteryNumber, -2, 2) AS last
                FROM lottery_number_table GROUP BY LotteryNumber 
                HAVING SUBSTRING(LotteryNumber, -4, 2) = '$middle' 
                AND SUBSTRING(LotteryNumber, -2, 2) >$start AND SUBSTRING(LotteryNumber, -2, 2) <$end 
                AND COUNT(CASE WHEN DATEDIFF(CURDATE() ,STR_TO_DATE(WinDate, '%d-%m-%Y')) <4 THEN 0 END)=0
                AND LENGTH(LotteryNumber)=4
                ORDER BY LotteryNumber ASC");

        if ($stmt->execute()) {

            $seed = $startNumber.$endNumber.$userId;

            

            //$seed = (int)$userId;


            $slot = array();


            $numberList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            srand($seed);

          

            ////

            $size = sizeof($numberList);
            $colrSize = ($size*20)/100;

            for($i=0; $i<$size; $i++){
                if($i%5==0){
                    $rnds = rand(1, 4);
                    $pos = $i+$rnds;

                    if($pos>-1 && $pos<$size){
                        $numberList[$pos]['Select']=true;
                    }
                }
            }

            /////



            //shuffle($numberList);

            $lastSelect = rand(0, 3);

            //echo $lastSelect;

            // foreach ($numberList as $key => $value) {
            //     $last = (int)$value['last'];
            //     $slotNo = 0;
            //     if($last>=00 && $last<=9){
            //         //slot 1
            //         $slotNo = 1;

            //     }else if($last>=10 && $last<=19){
            //         //slot 2
            //         $slotNo = 2;
            //     }else if($last>=20 && $last<=29){
            //         //slot 3
            //         $slotNo = 3;
            //     }else if($last>=30 && $last<=39){
            //         //slot 4
            //         $slotNo = 4;
            //     }else if($last>=40 && $last<=49){
            //         //slot 5
            //         $slotNo = 5;
            //     }else if($last>=50 && $last<=59){
            //         //slot 6
            //         $slotNo = 6;
            //     }else if($last>=60 && $last<=69){
            //         //slot 7
            //         $slotNo = 7;
            //     }else if($last>=70 && $last<=79){
            //         //slot 8
            //         $slotNo = 8;
            //     }else if($last>=80 && $last<=89){
            //         //slot 9
            //         $slotNo = 9;
            //     }else if($last>=90 && $last<=99){
            //         //slot 10
            //         $slotNo = 10;
            //     }

            //     if(array_key_exists($slotNo, $slot)){

            //         if($lastSelect>7){
            //             $value['Select']=true;
            //             $numberList[$key] = $value;
            //             $slot[$slotNo] = true;
            //             $lastSelect=1;
            //         }else{
            //             $value['Select']=false;
            //             $numberList[$key] = $value;
            //             $lastSelect++;
            //         }


            //     }else{


            //         if($lastSelect>=5){
            //             $value['Select']=true;
            //             $numberList[$key] = $value;
            //             $slot[$slotNo] = true;
            //             $lastSelect=1;
            //         }else{
            //             $value['Select']=false;
            //             $numberList[$key] = $value;
            //             $lastSelect++;
            //         }
            //     }
            // }
            //array_multisort(array_column($numberList, 'LotteryNumber'), SORT_ASC, $numberList);
            return array(
                "error" => false,
                "msg" => "" . $seed,
                "number" => $numberList
            );
        } else {
            return helper::errorResponse($stmt->errorInfo());
        }
    }


    public function getFirstPrizeLastDigitNumber()
    {
        try {

            $searchStm = $this->db->prepare("SELECT * , COUNT(Id) AS count FROM lottery_number_table WHERE WinType='1st'  GROUP BY SUBSTR(LotteryNumber, -1) ORDER BY count DESC");

            $searchStm->execute();
            $searchResult = $searchStm->fetchAll(PDO::FETCH_ASSOC);
            if (count($searchResult) === 0) {
                return helper::errorResponse("No data found");
            } else if (count($searchResult) >= 1) {
                return helper::successResponseWithData("Success", "lotteryNumbers", $searchResult);
            }
        } catch (PDOException $e) {
            return helper::errorResponse($e->getMessage());
        }
    }

    public function getFirstPrizelastDigitDetailsByDigit($lastDigit, $pageNumber, $itemCount)
    {
        $perPage = 30;
        $startAt = $perPage * ($pageNumber - 1);

        if (empty($itemCount) || $itemCount < 1) {
            $count = $this->db->prepare("SELECT  COUNT(id) AS total FROM `lottery_number_table` WHERE SUBSTR(LotteryNumber, -1) = '$lastDigit'  AND WinType ='1st' ");
            $count->execute();

            $totalItem =  $count->fetch()['total'];
            $itemCount = ceil($totalItem / $perPage);
        }


        try {

            $searchStm = $this->db->prepare("SELECT * FROM `lottery_number_table` WHERE SUBSTR(LotteryNumber, -1) = '$lastDigit'  AND WinType ='1st' ORDER BY STR_TO_DATE(WinDate, '%d-%m-%Y') DESC, WinTime LIMIT $startAt, $perPage");

            $searchStm->execute();
            $searchResult = $searchStm->fetchAll(PDO::FETCH_ASSOC);

            return array(
                "error" => false,
                "msg" => 'Success',
                "numbers" => $searchResult,
                "totalPages" => $itemCount
            );
        } catch (PDOException $e) {
            return helper::errorResponse($e->getMessage());
        }
    }

    public function getPartByMiddle($userId, $middle)
    {
        if (empty($userId)) {
            return helper::errorResponse("User not logged");
        }

        if (!helper::isAuth()) {
            return helper::errorResponse("Request Authentication Failed");
        }

        $lisence = new license($userId);

        $lisenceRes = $lisence->checkLicense(5);


        if (!$lisenceRes["error"]) {
            $stmt = $this->db->prepare("SELECT concat(20*floor((SUBSTR(LotteryNumber, -2,2) )/20), '-', 20*floor((SUBSTR(LotteryNumber, -2,2) )/20) + 19) as `range`,
            count(*) as `count` from lottery_number_table WHERE SUBSTR(LotteryNumber, -4, 2)=$middle AND DATEDIFF(CURDATE(), STR_TO_DATE(WinDate,'%d-%m-%Y') )<61  group by 1 ORDER BY count DESC");


            if ($stmt->execute()) {
                return helper::successResponseWithData(
                    "Success",
                    "lastPart",
                    $stmt->fetchAll(PDO::FETCH_ASSOC)
                );
            } else {
                return helper::errorResponse($stmt->errorInfo());
            }
        } else {
            return $lisenceRes;
        }
    }

    public function getMiddleWithPartLotteryNumber($middle, $range)
    {

        $splitText = explode("-", $range);

        $start = $splitText[0];
        $end = $splitText[1];


        $this->allPosts = array();

        try {
            $searchStm = $this->db->prepare("SELECT * FROM `lottery_number_table` WHERE SUBSTR(LotteryNumber, -4, 2) = $middle AND SUBSTR(LotteryNumber, -2, 2) >= $start AND  SUBSTR(LotteryNumber, -2, 2) <=$end AND DATEDIFF(CURDATE(), STR_TO_DATE(WinDate,'%d-%m-%Y') )<61 ORDER BY SUBSTR(LotteryNumber, -2, 2) ASC, Id DESC");
            $searchStm->execute();
            $searchResult = $searchStm->fetchAll(PDO::FETCH_ASSOC);
            if (count($searchResult) === 0) {
                $this->response["status"] = "not exist";
                $this->response["message"] = "No information found about this lottery number.";

                return $this->response;
            } else if (count($searchResult) >= 1) {
                $this->response["status"] = "success";
                $this->response["message"] = "Lottery number information found successfully.";
                $this->response["data"] = $searchResult;
                return $this->response;
            }
            $this->conn = null;
        } catch (PDOException $e) {
            $this->response["status"] = "failed";
            $this->response["message"] = "Sorry, Result checking failed for " . $e->getMessage();
            return $this->response;
            $this->conn = null;
        }
    }

    public function getMiddleMaxByDays($days)
    {
        $searchStm = $this->db->prepare("SELECT *, SUBSTR(LotteryNumber,-4,2) AS Middlle, COUNT(Id) As count
                                             FROM lottery_number_table WHERE DATEDIFF(CURDATE(), STR_TO_DATE(WinDate, '%d-%m-%Y'))<=$days
                           GROUP BY SUBSTR(LotteryNumber, -4, 2) ORDER BY count DESC LIMIT 30");



        

        if($searchStm->execute()){
            $searchResult = array();
            while ($row = $searchStm->fetch()) {

                $item = array(
                    "Id" => $row['Id'],
                    "LotteryNumber" => $row['Middlle'],
                    "LotterySerialNumber" => $row['LotterySerialNumber'],
                    "WinType" => $row['WinType'],
                    "WinDate" => $row['WinDate'],
                    "WinTime" => $row['WinTime'],
                    "WinDayName" => $row['WinDayName'],
                );

                array_push($searchResult, $item);
                
            }

            return array(
                "error"=>false,
                "msg"=>"Success",
                "data"=>$searchResult
            );

        }else{
            return array(
                "error"=>true,
                "msg"=>$searchStm->errorInfo()
            );
        }

    }

    public function getNotPlayedNumber5th($pageNumber = 1, $itemCount = 0){
        $perPage = 10;

        $startAt = $perPage * ($pageNumber - 1);

        if(empty($itemCount) || $itemCount<1){
//            $count = $this->db->prepare("SELECT COUNT(id) as count, SUM(CASE WHEN  DATEDIFF(CURDATE(), STR_TO_DATE(WinDate, '%d-%m-%Y'))<180 AND WinType='4th' OR WinType='3rd' THEN 1 ELSE 0 END ) AS App FROM `lottery_number_table`  GROUP BY LotteryNumber HAVING LENGTH(LotteryNumber)=4 AND App=0");
//            $count->execute();


            //$totalItem =  $count->fetch(PDO::FETCH_ASSOC)['count'];
            $totalItem =  0;

            $itemCount = ceil($totalItem / $perPage);
        }


        $searchStm = $this->db->prepare("SELECT *, SUM(CASE WHEN  DATEDIFF(CURDATE(), STR_TO_DATE(WinDate, '%d-%m-%Y'))<180 AND WinType='5th'  THEN 1 ELSE 0 END ) AS App FROM `lottery_number_table`  GROUP BY LotteryNumber HAVING LENGTH(LotteryNumber)=4 AND  App=0 ORDER BY LotteryNumber Asc ");
        $searchStm->execute();
        $searchResult = $searchStm->fetchAll(PDO::FETCH_ASSOC);
        if (count($searchResult) === 0) {
            $response["status"]="not exist";
            $response["message"]="No information found about this lottery number.";
            return $response;
        } else if (count($searchResult) >= 1) {
            $response["status"]="success";
            $response["message"]="Lottery number information found successfully.";
            $response["data"]=$searchResult;
            $response["currentPage"]=$pageNumber;
            $response["totalPages"]=$itemCount;
            return $response;
        }
    }
    public function getNotPlayedNumber3rd4th($pageNumber = 1, $itemCount = 0){
        $perPage = 10;

        $startAt = $perPage * ($pageNumber - 1);

        if(empty($itemCount) || $itemCount<1){
//            $count = $this->db->prepare("SELECT COUNT(id) as count, SUM(CASE WHEN  DATEDIFF(CURDATE(), STR_TO_DATE(WinDate, '%d-%m-%Y'))<180 AND WinType='4th' OR WinType='3rd' THEN 1 ELSE 0 END ) AS App FROM `lottery_number_table`  GROUP BY LotteryNumber HAVING LENGTH(LotteryNumber)=4 AND App=0");
//            $count->execute();


            //$totalItem =  $count->fetch(PDO::FETCH_ASSOC)['count'];
            $totalItem =  0;

            $itemCount = ceil($totalItem / $perPage);
        }


        $searchStm = $this->db->prepare("SELECT *, SUM(CASE WHEN  DATEDIFF(CURDATE(), STR_TO_DATE(WinDate, '%d-%m-%Y'))<365 AND WinType='4th' OR WinType='3rd' THEN 1 ELSE 0 END ) AS App FROM `lottery_number_table`  GROUP BY LotteryNumber HAVING LENGTH(LotteryNumber)=4 AND App=0 ORDER BY LotteryNumber Asc ");
        $searchStm->execute();
        $searchResult = $searchStm->fetchAll(PDO::FETCH_ASSOC);
        if (count($searchResult) === 0) {
            $response["status"]="not exist";
            $response["message"]="No information found about this lottery number.";
            return $response;
        } else if (count($searchResult) >= 1) {
            $response["status"]="success";
            $response["message"]="Lottery number information found successfully.";
            $response["data"]=$searchResult;
            $response["currentPage"]=$pageNumber;
            $response["totalPages"]=$itemCount;
            return $response;
        }
    }

    public function getPlanListByUserId($userId){
        try {
            $stmt =$this->db->prepare("SELECT plan.*, CASE WHEN paid_license.expire_date >= UNIX_TIMESTAMP(NOW(3))*1000 AND status=1 THEN 1 ELSE 0 END as status FROM `plan` LEFT JOIN paid_license ON plan.planId = paid_license.plan_type AND paid_license.user_id = $userId ORDER BY planId ASC");
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return helper::successResponseWithData("Success", "data", $data);
        }catch (Exception $e){
            return helper::errorResponse();
        }


    }


    public function getPlanList(){
        try {
            $stmt =$this->db->prepare("SELECT *   FROM `plan`");
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return helper::successResponseWithData("Success", "data", $data);
        }catch (Exception $e){
            return helper::errorResponse();
        }


    }

}
