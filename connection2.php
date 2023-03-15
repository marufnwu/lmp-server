<?php

class Connection2
{
   public $servername = "localhost";
   public $databaseName = "sikderit_lmp";
   public $username = "sikderit_lmp";
   public $password = "SikderITHub00@@";
   public $conn;


    // public $servername = "lmp-aws-db.cmzqtlazrybz.ap-south-1.rds.amazonaws.com";
    // public $databaseName = "lmp";
    // public $username = "admin";
    // public $password = "LetMeDown";
    // public $conn;


    //  public $servername = "localhost";
    //  public $databaseName = "lotterym_lottery_master_pro";
    //  public $username = "lotterym_lottery_master_pro";
    //  public $password = "SDITHub00";
    //  public $conn;

    public function connectDatabase()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->databaseName; charset=utf8", $this->username, $this->password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
             echo "Connection failed: " . $e->getMessage();
        }

    }

    public function getInstance()
    {
        $this->connectDatabase();
        return $this->conn;
    }
}

