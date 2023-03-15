<?php
require_once("../connection.php");
class thumb
{
    private $db;

    function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    function upload($thumb, $thumbId)
    {
        $thumbName  =  $thumb['name'];
        $thumbtempPath  =  $thumb['tmp_name'];
        $thumbSize  =  $thumb['size'];

        $legalName = $thumbId .".". $this->getExt($thumbName);

        if ($this->isValidFile($thumbName)) {
            if (move_uploaded_file($thumbtempPath, helper::baseUrl()."uploads/images/audio_thumb/" . $legalName)) {
                return helper::successResponse("uploads/images/audio_thumb/{$legalName}");
            } else {
                return helper::errorResponse($thumb['error']);
            }
        } else {
            return helper::errorResponse("Invalid thumbnail");
        }
    }

    function isValidFile($fileName)
    {
        $valid_extensions = array('png', 'jpeg', 'jpg');
        if (in_array($this->getExt($fileName), $valid_extensions)) {
            return true;
        }

        return false;
    }

    function getExt($fileName)
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }
}
