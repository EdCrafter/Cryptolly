<?php

include_once("../../include/dataProcessor.php");
include_once("../../include/db.php");
include_once("../../include/createDB.php");
$forename = $surname = $username = $password = $password2 = $age = $email = $admin = "";
if (isset($_POST['name'])) $forename = DataProcessor::sanitizeString($_POST['name']);
if (isset($_POST['surname'])) $surname = DataProcessor::sanitizeString($_POST['surname']);
if (isset($_POST['username'])) $username = DataProcessor::sanitizeString($_POST['username']);
if (isset($_POST['password']) && isset($_POST['password2'])) {
    $password = DataProcessor::sanitizeString($_POST['password']);
    $password2 = DataProcessor::sanitizeString($_POST['password2']);
}
if (isset($_POST['adminPass'])) {
    $admin = DataProcessor::sanitizeString($_POST['adminPass']);
    $fail['admin'] = validate_admin($admin);
}
if (isset($_POST['age'])) $age = DataProcessor::sanitizeString($_POST['age']);
if (isset($_POST['email'])) $email = DataProcessor::sanitizeString($_POST['email']);
$fail['name'] = validate_forename($forename);
$fail['surname'] = validate_surname($surname);
$fail['username'] = validate_username($username);
$fail['password'] = validate_password($password, $password2);
$fail['age'] = validate_age($age);
$fail['email'] = validate_email($email);

if (empty(array_filter($fail))) {
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $adminBool = (bool)$admin ? 1 : 0;
    $sql = $mysqli->find("members")
        ->insert(
            ['name', 'surname', 'username', 'password', 'email', 'admin', 'age'],
            [$forename, $surname, $username, $hash, $email, $adminBool, (int)$age]
        );
    $mysqli->executeQuery($sql->sql());
    $fail['success'] = "true";
    echo json_encode($fail);
} else {
    echo json_encode($fail);
}

function validate_forename($field)
{
    if ($field == "") return "No Forename was entered.\n";
    else if (strlen($field) < 2) return "Forenames must be at least 2 characters.\n";
    else if (!(preg_match("/[^a-zA-Z]/", $field)
        xor
        preg_match("/[^\x{0400}-\x{04FF}]/u", $field))) return "Only en or ua letters in forenames.\n";
    return "";
}

function validate_surname($field)
{
    if ($field == "") return "No Surname was entered.\n";
    else if (strlen($field) < 2) return "Surnames must be at least 2 characters.\n";
    else if (!(preg_match("/[^a-zA-Z]/", $field)
        xor
        preg_match("/[^\x{0400}-\x{04FF}]/u", $field))) return "Only en or ua letters in surnames.\n";
    return "";
}

function validate_username($field)
{
    if ($field == "") return "No Username was entered.\n";
    else if (strlen($field) < 5) return "Usernames must be at least 5 characters.\n";
    else if (preg_match("/[\W_-]/", $field)) return "Only letters, numbers, - and _ in usernames.\n";
    return "";
}

function validate_password($field1, $field2)
{
    if ($field1 == "" || $field2 == "") return "No Password was entered.\n";
    else if (strlen($field1) < 6) return "Passwords must be at least 6 characters.\n";
    else if ($field1 != $field2) return "Passwords do not match.\n";
    else if (!preg_match("/[a-z]/", $field1) || !preg_match("/[A-Z]/", $field1) || !preg_match("/[0-9]/", $field1)) return "Passwords require 1 each of a-z, A-Z and 0-9.\n";
    return "";
}

function validate_admin($field1)
{
    global $mysqli;
    if ($field1 == "") return "No Password was entered.\n";
    else if (strlen($field1) < 6) return "Passwords must be at least 6 characters.\n";
    $sql=$mysqli->find("adminPass")->select('pass')->sql();
    $result = $mysqli->queryOne($sql);
    //rTvE4t4H
    if (!password_verify($field1,$result['pass'])) return "Wrong password.\n";
    return "";
}

function validate_age($field)
{
    if ($field == "") return "No Age was entered.\n";
    else if ($field < 18 || $field > 110) return "Age must be between 18 and 110.\n";
    return "";
}

function validate_email($field)
{
    if ($field == "") return "No Email was entered.\n";
    else if (!((strpos($field, ".") > 0) && (strpos($field, "@") > 0)) || !preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/", $field)) return "The Email address is invalid.\n";
    return "";
}
