<?php

$forename = $surname = $username = $password = $password2 = $age = $email = "";
if (isset($_POST['name'])) $forename = fix_string($_POST['name']);
if (isset($_POST['surname'])) $surname = fix_string($_POST['surname']);
if (isset($_POST['username'])) $username = fix_string($_POST['username']);
if (isset($_POST['password']) && isset($_POST['password2'])) {
    $password = fix_string($_POST['password']);
    $password2 = fix_string($_POST['password2']);
}
if (isset($_POST['adminPass'])) $admin = fix_string($_POST['adminPass']);
if (isset($_POST['age'])) $age = fix_string($_POST['age']);
if (isset($_POST['email'])) $email = fix_string($_POST['email']);
$fail = validate_forename($forename);
$fail .= validate_surname($surname);
$fail .= validate_username($username);
$fail .= validate_password($password,$password2);
$fail .= validate_admin($admin);
$fail .= validate_age($age);
$fail .= validate_email($email);

if ($fail == "") {
    header("Location: adduser.php");
    exit;
} else {
    echo "<pre>".$fail."</pre>";
}

function fix_string($string) {
    echo "string : ".$string;
    $string = stripslashes($string);
    return htmlentities($string);
}

function validate_forename($field) {
    if ($field == "" ) return "No Forename was entered.\n";
    else if (strlen($field) < 2) return "Forenames must be at least 2 characters.\n";
    else if (preg_match("/[^a-zA-Z]/", $field)) return "Only letters in forenames.\n";
    return "";
}

function validate_surname($field) {
    if ($field == "") return "No Surname was entered.\n";
    else if (strlen($field) < 2) return "Surnames must be at least 2 characters.\n";
    else if (preg_match("/[^a-zA-Z]/", $field)) return "Only letters in surnames.\n";
    return "";
}

function validate_username($field) {
    if ($field == "") return "No Username was entered.\n";
    else if (strlen($field) < 5) return "Usernames must be at least 5 characters.\n";
    else if (preg_match("/[\W_-]/", $field)) return "Only letters, numbers, - and _ in usernames.\n";
    return "";
}

function validate_password($field1, $field2) {
    if ($field1 == "" || $field2 == "") return "No Password was entered.\n";
    else if (strlen($field1) < 6) return "Passwords must be at least 6 characters.\n";
    else if ($field1 != $field2) return "Passwords do not match.\n";
    else if (!preg_match("/[a-z]/", $field1) || !preg_match("/[A-Z]/", $field1) || !preg_match("/[0-9]/", $field1)) return "Passwords require 1 each of a-z, A-Z and 0-9.\n";
    return "";

}

function validate_admin($field1) {
    if ($field1 == "") return "No Password was entered.\n";
    else if (strlen($field1) < 6) return "Passwords must be at least 6 characters.\n";
    else if (!preg_match("/[a-z]/", $field1) || !preg_match("/[A-Z]/", $field1) || !preg_match("/[0-9]/", $field1)) return "Passwords require 1 each of a-z, A-Z and 0-9.\n";
    return "";
}

function validate_age($field) {
    if ($field == "") return "No Age was entered.\n";
    else if ($field < 18 || $field > 110) return "Age must be between 18 and 110.\n";
    return "";
}

function validate_email($field) {
    if ($field == "") return "No Email was entered.\n";
    else if (!((strpos($field, ".") > 0) && (strpos($field, "@") > 0)) || ! preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/", $field)) return "The Email address is invalid.\n";
    return "";
}

?>
