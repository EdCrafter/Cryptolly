<?php

include_once("../../include/createDB.php");
include_once("../../include/dataProcessor.php");

if (isset($_POST["user"])) {
    $user = $_POST["user"];
    $sql = $mysqli ->find("members")
    ->select(["username"])->where("username","=",$user)->sql();
    $query = $mysqli->query($sql);
    if ($query) {
        echo "<span class='taken'>&nbsp;&#x2718; Username '$user' is taken</span>";
    } else {
        echo "<span class='available'>&nbsp;&#x2714; Username '$user' is available</span>";
    }
}

if (isset($_POST["email"])) {
    $email = $_POST["email"];
    $sql = $mysqli ->find("members")
    ->select(["email"])->where("email","=",$email)->sql();
    $query = $mysqli->query($sql);
    if ($query) {
        echo "<span class='taken'>&nbsp;&#x2718; Email '$email' is taken</span>";
    } else {
        echo "<span class='available'>&nbsp;&#x2714; Email '$email' is available</span>";
    }
}

?>