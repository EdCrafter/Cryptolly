<?php
include_once '../../../include/db.php';
include_once '../../../include/pagination.php';
include_once '../../../include/request.php';
$db = new DB([
    'host' => 'localhost',
    'user' => 'maxim',
    'password' => 'IPZ221Verdev',
    'db' => 'Cryptolly'
]);

Header('Content-Type: application/json');

$x = $db->find('prices')->select(['*'])->where('price','<>','NULL');

$sql = $db->find('cryptocurrencies c')->select([
    'crypto_id',
    'c.name as crypto_name',
])->sql();
$rows = $db->query($sql);

$r=[];
foreach ($rows as $row){
    array_push($r, [
        'crypto_id' => $row['crypto_id'],
        'crypto_name' => $row['crypto_name'],
        ]);
    
}

echo json_encode($r);

?>