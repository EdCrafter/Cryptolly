<?php
include_once("createDB.php");
$randstr = substr(md5(rand()), 0, 7);
function showProfile($user)
{
    global $mysqli, $randstr;

    $result = $mysqli->find("profiles")->select(['*'])
        ->where('user', '=', $user)
        ->sql();
    $r = $mysqli->query($result);
    $t = 0;
    if (file_exists("../profile/img/$user")) {
        echo "<div class='gallery-container'>";
        $files = glob("../profile/img/$user/*");
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($file == "../profile/img/$user/$user" . "_" . "1.jpg"){
                    echo "<img src='$file' style='float:left;' class='gallery-img gallery-active'>";
                }
                else{
                    echo "<img src='$file' style='float:left;' class='gallery-img'>";
                }
            }
        }
        echo "</div>";
        $t = 1;
    }
    echo "<a class='member' href='members.php?view=$user&r=$randstr'>$user</a>";
    if ($r) {
        foreach ($r as $row) {
            echo ("<div class='about'>" . $row['text'] . "</div><br style='clear:left;'><br>");
        }
        $t = 1;
    }
    if ($t == 0) {
        echo "<p>Nothing to see here, yet</p><br>";
    }
    $result1 = $mysqli->find("members")->select(['admin'])->where('username', '=', $user)->sql();
    $r1 = $mysqli->queryOne($result1);
    if ($r1['admin'] == '1' && $user == $_SESSION['username']) {
        echo "<button class='button-container' onclick=\"window.location.href = 'admin.php';\">Admin page</button>";
    }
}