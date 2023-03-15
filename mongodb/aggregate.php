<?php
require_once __DIR__."/../vendor/autoload.php";
$client = new MongoDB\Client('mongodb://localhost:27017');

$db = $client->lmp;


//select table/collection
$userTable  = $db->lottery_numbers;


$res = $userTable->aggregate([
    ['$group' => [
        '_id' => '$_id',
        'LotteryNumber' => ['$first' => '$LotteryNumber' ],
        'count' => ['$sum' => 1]
        ]
    ],
    ['$sort' => ['count' => -1]]
]);
echo json_encode(iterator_to_array($res));


