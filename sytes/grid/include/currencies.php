<?php
include_once 'db.php';
include_once 'pagination.php';
include_once 'request.php';
$db = new DB([
    'host' => 'localhost',
    'user' => 'maxim',
    'password' => 'IPZ221Verdev',
    'db' => 'Cryptolly'
]);

Header('Content-Type: application/json');

$x = $db->find('prices')->select(['*'])->where('price','<>','NULL');

$sql = $db->find('currency c')->select([
    'currency_id',
    'currency_name',
])->sql();
$rows = $db->query($sql);

$r=[];
foreach ($rows as $row){
    array_push($r, [
        'currency_id' => $row['currency_id'],
        'currency_name' => $row['currency_name'],
        ]);
    
}

echo json_encode($r);

?>