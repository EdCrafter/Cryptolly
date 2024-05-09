<?php
//echo '{"a" : 1 , "b" : 2}';
// $ar['a'] = 1;
// $ar['b'] = 2;
// echo json_encode($ar);
require_once 'login.php';
header("Content-Type: application/json; charset=UTF-8");
$response = ['status' => 'ok', 'rows' => [], 'rowsCount' => 0, 'page' => 1, 'limit' => 1];
$response = ['status' => true, 'rows' => []];

$wh = "WHERE `currency_name`<>''";
if (!empty($_REQUEST['search'])) {
    $wh = $wh . " AND currency_name LIKE '" . $_REQUEST['search'] . "%'";
}
$query = "Select `currency_name` , `currency_id` From `currency` " . $wh . " ORDER BY `currency_name`";
$result = $pdo->query($query);
if ($result) {
    $response['rows'] = $result;
}
if (!$result) {
    $response['status'] = 'error';
    $response['message'] = 'Error in query';
    exit();
}
$response['rows'] = $result->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($response);
// while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
//     foreach ($row as $key => $value) {
//         echo $key . ": " . $value . "<br>";
//     }
//     echo "<br>";
// }
