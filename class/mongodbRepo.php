<?php

use MongoDB\BSON\Regex;

require_once __DIR__."/../mongodb/db.php";
require_once __DIR__ . "/../vendor/autoload.php";

class mongodbRepo {
    private $db;
    private $client;
    public function __construct(){
        $this->client = new MongoDB\Client('mongodb://localhost:27017');
        $dbo =  $this->client->lmp;

        $this->db= $dbo;
    }

    public function getResultByDateAndSlot($date, $slotId){
        try {
            //select table/collection
            $table = $this->db->lottery_numbers;


            $res = $table->find(['WinDate'=>$date, 'SlotId'=>intval($slotId)]);
            return iterator_to_array($res);
        }catch (Exception $e){
            return helper::errorResponse("Something Went Wrong");
        }
    }



    public function getOldResult($date, $slotId){
        try {
            //select table/collection
            $table = $this->db->lottery_numbers;


            $res = $table->find(['WinDate' => $date, 'SlotId' => "$slotId"]);
            return iterator_to_array($res);
        }catch (Exception $e){
            return helper::errorResponse("Something Went Wrong");
        }
    }

    public function setResultView($date, $time): int
    {

        try {
            $_id = $date."_".$time;


            $table = $this->db->result_view;
            $res = $table->findOneAndUpdate(['_id'=> "$_id"], ['$inc'=>['views'=>1]]);

            return $res->views;
        }catch (Exception $e){
            return 0;
        }

    }

    public function getDuplicateLotteryNumber($pageNumber=1, $perPage=50, $totalPage=0){
        $table = $this->db->lottery_numbers;
        if($totalPage==0){

            $res = $table->aggregate([['$group' => ['_id' => '$LotteryNumber']], ['$count' => 'count']]);
            $res = iterator_to_array($res, false);
            $totalPage = $res[0]->count;
        }

        $skip = $pageNumber>0 ? ($pageNumber-1)*$perPage : 0;

        $res = $table->aggregate([
            [
                '$group' => [
                    '_id' => '$LotteryNumber',
                    'count' => ['$sum' => 1],
                    'LotteryNumber' => ['$first' => '$LotteryNumber'],
                    'LotterySerialNumber' => ['$first' => '$LotterySerialNumber'],
                ]
            ],
            [
                '$sort' => ['count' => -1]
            ],
            [
                '$skip' => $skip
            ],
            [
                '$limit' => $perPage
            ]
        ]);
        $res = iterator_to_array($res, false);

        $data["status"]="success";
        $data["message"]="Lottery number information found successfully. using mongodb";
        $data['data']=$res;
        $data['currentPage']=$pageNumber;
        $data['totalPages']=$totalPage;

        return $data;
    }

    public function searchLotteryNumber($number, $pageNumber=1, $perPage=50, $totalPage=0){

        $table = $this->db->lottery_numbers;
        if($totalPage==0){

            $res = $table->aggregate([['$group' => ['_id' => '$LotteryNumber']], ['$count' => 'count']]);
            $res = iterator_to_array($res, false);
            $totalPage = $res[0]->count;
        }

        $skip = $pageNumber>0 ? ($pageNumber-1)*$perPage : 0;

        $res = $table->aggregate([
            [
                '$match' => [
                    'LotteryNumber' => ['$regex' => new Regex("^$number")]
                ]
            ],
            [
                '$project' => [
                    'date' => ['$dateFromString' => ['dateString' => '$WinDate']],
                    'WinDate' => 1,
                    'WinTime' => 1,
                    'LotteryNumber' => 1,
                    'SlotId' => 1,
                    'LotterySerialNumber'=>1,
                    'WinType'=>1
                ]
            ],
            [
                '$sort' => [
                    'date' => -1,
                    'WinTime'=>1
                ]

            ],
            [
                '$limit'=>$perPage
            ]
        ]);

        $res = iterator_to_array($res, false);

        $this->response["status"]="success";
        $this->response["message"]="Lottery number information found successfully. Using mongo";
        $this->response["data"]=$res;
        return $this->response;
    }


}







