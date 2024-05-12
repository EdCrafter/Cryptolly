<?php
session_start();
include_once("../../include/dataProcessor.php");
include_once("../../include/db.php");
include_once("../../include/createDB.php");

$admin = "";
if (isset($_POST['adminPass'])) {
    $admin = DataProcessor::sanitizeString($_POST['adminPass']);
    $fail['admin'] = validate_admin($admin, $_SESSION['username']);
}

if ($fail != "") {

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = $mysqli->find("members")
        ->update(
            ['admin'],
            [1]
        )->where("username", "=", $_SESSION['username']);
    $mysqli->executeQuery($sql->sql());
    $sql = $mysqli->find("admin")
        ->delete()->where("user", "=", $_SESSION['username']);
    $mysqli->executeQuery($sql->sql());
    $fail['success'] = "true";
    echo json_encode($fail);
} else {
    echo json_encode($fail);
}

function validate_admin($field1, $field2)
{
    global $mysqli;
    if ($field1 == "") return "No Password was entered.\n";
    else if (strlen($field1) < 6) return "Passwords must be at least 6 characters.\n";
    $sql = $mysqli->find("admin")->select('admin_pass')->where("user", "=", $field2)->sql();
    $f = fopen('edit2.txt', 'a+');
    fputs($f, $sql);
    fclose($f);
    $result = $mysqli->queryOne($sql);
    if (!password_verify($field1, $result['admin_pass'])) return "Wrong password.\n";
    return "";
}
