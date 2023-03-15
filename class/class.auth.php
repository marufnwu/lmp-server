<?php
class auth{
    private $db;

    function __construct($dbo = null)
    {
        $this->db = $dbo;
    }

    public static function authorize($dbo=null){
        $headers = getallheaders();

        if(isset($headers['Userid'])){

            $userId = $headers['Userid'];
            return $userId;

        }
        return false;
    }
}