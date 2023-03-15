<?php
require_once("../connection.php");
require_once("class.helper.php");
class ad{
    private $db;
    public function __construct(){
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    public function loadFullScreenAd($userId, $activity){

        try{
            $stmt = $this->db->prepare("SELECT * FROM audio_tutorial ORDER BY RAND() LIMIT 10");
            if($stmt->execute()){
                if($stmt->rowCount()>0){
                    $item = $stmt->fetch(PDO::FETCH_ASSOC);


                    $audioLink = "https://lotterymasterpro.com/".$item['audio'];
                    $image = "https://lotterymasterpro.com/".$item['thumbnail'];
                    $title = $item['tittle'];
                    $actionType = 0; //0 => nothing, 1=>open url, 2=> open activity
                    $ad = array(
                        "actionType"=>$actionType,
                        "targetUrl"=>"",
                        "title"=>$title,
                        "description"=>"",
                        "cancelTime"=>5,
                        "video"=>null,
                        "image"=>$image,
                        "audio"=>$audioLink
                    );

                    return helper::successResponseWithData("Ad loaded", "data", $ad);

                }else{
                    return helper::errorResponse("Ad not available");
                }
            }
        }catch(Exception $e){
            return helper::errorResponse($e->getMessage());
        }
    }
}