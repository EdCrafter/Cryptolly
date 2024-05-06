<?php
include('../../../include/db.php');
$db = new DB([
    'host' => 'localhost',
    'user' => 'maxim',
    'password' => 'IPZ221Verdev',
    'db' => 'Cryptolly'
]);


if (isset($_POST['oper'])) {
    if ($_POST['oper'] == 'add') {
        $sql = $db->find('prices')-> insert([
            'price', 'date', 'time', 'crypto_id', 'currency_id'
        ], [
            $_POST['price'],
            "'".$_POST['date']."'",
            "'".$_POST['time']."'",
            $_POST['crypto_id'],
            $_POST['currency_id']
        ])->sql();
        $db->executeQuery($sql);

        if ($sql ==false) {
            Header("HTTP/1.1 422 Can not add");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }
    }
    else if ($_POST['oper'] == 'edit') {
        $sql = $db->find('prices')-> update([
            'price', 'date', 'time', 'crypto_id', 'currency_id'
        ], [
            $_POST['price'],
            $_POST['date'],
            $_POST['time'],
            $_POST['crypto_id'],
            $_POST['currency_id']
        ])->where('id', '=', $_POST['id'])
        ->sql();
        // $f = fopen("edit.txt", "a+");
        // fputs($f, "sql:\n");
        // fputs($f, $sql);
        // fclose($f);

        $db->executeQuery($sql);

        if ($sql ==false) {
            Header("HTTP/1.1 422 Can not edit");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }
    }
    else if ($_POST['oper'] == 'del') {
        $sql = $db->find('prices')-> delete()->where('id', '=', $_POST['id'])
        ->sql();
        $f = fopen("edit.txt", "a+");
        fputs($f, "sql:\n");
        fputs($f, $sql);
        fclose($f);

        $db->executeQuery($sql);

        if ($sql ==false) {
            Header("HTTP/1.1 422 Can not edit");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }
    }
} else {
    Header("HTTP/1.1 400 Bad params");
    echo json_encode(['success' => false]);
}

$f = fopen("edit.txt", "a+");
fputs($f, "POST:\n");
fputs($f, var_export($_POST, true));
fputs($f, "GET:\n");
fputs($f, var_export($_GET, true));
fclose($f);

