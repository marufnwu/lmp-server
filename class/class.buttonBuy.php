<?php
class ButtonBuy{
    private $db;

    function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    
}