<?php
require_once __DIR__ . "/../vendor/autoload.php";
try {
    $client = new MongoDB\Client('mongodb://localhost:27017');
    $dbo = $client->lmp;
}catch (Exception $e){
    throw new Exception('Mongodb connection failed');
}