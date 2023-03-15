<?php
class aws
{
    public $servername = "lmp-aws-db.cmzqtlazrybz.ap-south-1.rds.amazonaws.com";
    public $databaseName = "lmp";
    public $username = "admin";
    public $password = "LetMeDown";
    public $conn;

    public function connectDatabase()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->databaseName; charset=utf8", $this->username, $this->password);
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

