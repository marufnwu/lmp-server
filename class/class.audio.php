<?php
require_once(__DIR__."/../connection.php");
require_once("class.helper.php");
class audio{
    const perPage = 10;
    private $db;
    public function __construct(){
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    public function getAudio($name){
        if(!helper::isAuth()){
            //return helper::errorResponse("Request Authentication Failed");
        }
 
        $stmt = $this->db->prepare("SELECT * FROM audio_file WHERE name=(:name) AND active=1 LIMIT 1");
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        
        if($stmt->execute()){
            if($stmt->rowCount()>0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return array(
                    "error"=>false,
                    "msg"=>"Audio Found",
                    "audio"=>$row
                 );
            }else{
                return array(
                    "error"=>true,
                    "msg"=>"Audio Not Found",
                   
                );
            }
        }else{
            return array(
                "error"=>true,
                "msg"=>$stmt->errorInfo()
            );
        }
    }

    public function addAudioTutorial($title, $audio, $thumb){
        $stmt = $this->db->prepare("INSERT INTO audio_tutorial (tittle, audio, thumbnail, active) VALUES('$title', '$audio', '$thumb', 1) ");
        if($stmt->execute()){
            return helper::successResponse();
        }else{
            return helper::errorResponse($stmt->errorInfo());
        }
    }

    function getAudios($page)
    {

        return array(
            "error"=>true,
            "msg"=>"Something went wrong"
        );

        $perPage = audio::perPage;
        $startAt = $perPage * ($page - 1);
        $count = $this->db->prepare("SELECT COUNT(*) as total FROM audio_tutorial WHERE active = 1");
        $count->execute();

        $total =  $count->fetch()['total'];

        $totalPages = ceil($total / $perPage);

        $stmt = $this->db->prepare("SELECT * FROM audio_tutorial WHERE active = 1  ORDER BY id DESC LIMIT $startAt, $perPage");
        $stmt->execute();
        
        $items = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            $row['audio']=helper::rootUrl().$row['audio'];
            $row['thumbnail']=helper::rootUrl().$row['thumbnail'];
            array_push(
                $items, $row
            );
        }

        return array(
            "error"=>false,
            "msg"=>"Success",
            "currentPage"=>$page,
            "totalPages"=>$totalPages,
            "audios"=>$items
        );
    }

    function getRandomVoiceMessage(){
        $stmt = $this->db->prepare("SELECT * FROM audio_tutorial ORDER BY RAND() LIMIT 15");
        try{
            $stmt->execute();
            return helper::successResponseWithData("Success", "audios", $stmt->fetchAll(PDO::FETCH_ASSOC));
        }catch(Exception $e){
            return helper::errorResponse($e->getMessage());
        }
    }
}