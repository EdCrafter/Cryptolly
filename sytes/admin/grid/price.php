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

$f = fopen('rrr.txt', 'a+');
fputs($f, "POST\n");
fputs($f, var_export($_POST, true));
fputs($f, var_export($_GET, true));

fclose($f);

$pagination = new Pagination();
$pagination->limits =  [10, 20, 30];
$x = $db->find('prices')->select(['*'])->where('price','<>','NULL')->join('cryptocurrencies c', [[
    'field' => 'c.crypto_id',
    'condition' => '=',
    'value' => 'prices.crypto_id'
]])->join('currency cu', [[
    'field' => 'cu.currency_id',
    'condition' => '=',
    'value' => 'prices.currency_id'
]]);

$sql = $db->find('prices')->select([
    'id',
    'price',
    'date',
    'time',
    'c.name as crypto_name',
    'cu.currency_name'
])->join('cryptocurrencies c', [[
    'field' => 'c.crypto_id',
    'condition' => '=',
    'value' => 'prices.crypto_id'
]])->join('currency cu', [[
    'field' => 'cu.currency_id',
    'condition' => '=',
    'value' => 'prices.currency_id'
]]);
if (isset($_POST['_search']) && $_POST['_search'] === 'true') {
    $fields = [
        'id' => 'id',
        'price' => 'price',
        'date' => 'date',
        'time' => 'time',
        'crypto_name' => 'c.name',
        'currency_name' => 'cu.currency_name'
    ];
    if ($_POST['filters']) {
        $filters = json_decode($_POST['filters'], true);
        foreach($filters['rules'] as $f) {
            $sql->where($fields[$f['field']], $f['op'], $f['data']);
            $x->where($fields[$f['field']], $f['op'], $f['data']);
        }
        $sql->setConcWhere($filters['groupOp']);
        $x->setConcWhere($filters['groupOp']);
    } else {
        $sql->where($fields[$_POST['searchField']],$_POST['searchOper'],$_POST['searchString']);
        $x->where($fields[$_POST['searchField']],$_POST['searchOper'],$_POST['searchString']);
    }
}
$response = [];
$response['xsql'] = $x->sql();
$count = $x->count();
$pagination->setLimit(DataProcessor::sanitizeString(Request::post('rows', 10)));
$pagination->setRowsCount($count);
$pagination->setPage(DataProcessor::sanitizeString(Request::post('page', 1)));

$sql->orderBy(['id'], DataProcessor::sanitizeString(Request::post('sord', 'desc')));
$sql->offset($pagination->getFirst())->limit($pagination->getLimit());
$sql = $sql->sql();
$rows = $db->query($sql);

$response = [
    'status' => 'true',
    'message' => '',
    'rows' => [],
    'page' => $pagination->getPage(),
    'total' => $pagination->getPageCount(),
    'records' => $pagination->getRowsCount(),   
    'sql' => $sql
];
$r=[];
foreach ($rows as $row){
    array_push($r, [
        'id' => $row['id'],
        'cell'=> [
            $row['id'],
            $row['price'],
            $row['date'],
            $row['time'],
            $row['crypto_name'],
            $row['currency_name']
        ],
    ]);
    
}


$response['rows'] = $r;
echo json_encode($response);
// $f = fopen('rrr.txt', 'a+');
// fputs($f, "POST\n");
// fputs($f, var_export($_POST, true));
// fputs($f, var_export($_GET, true));

// fclose($f);


// if ($db->isConnect()){
//     $sql = "SELECT * FROM price";
//     $rows = $db->query($sql);
//     if ($rows){
//         $response['rows'] = $rows;
//     } else {
//         $response['status'] = 'false';
//         $response['message'] = 'Error';
//     }
// } else {
//     $response['status'] = 'false';
//     $response['message'] = 'Error';
// }
// echo json_encode($response);
?>