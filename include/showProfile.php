<?php
    include_once("createDB.php");
    function showProfile($user)
    {
      global $mysqli;
        if (file_exists("$user.jpg")){
        echo "<img src='$user.jpg' style='float:left;'>";

        $result = $mysqli->find("profiles")->select(['*'])
            ->where('user','=', $user)
            ->sql();
        $r = $mysqli->query($result);
        echo "<h2>$user</h2>";
        foreach ($r as $row)
        {
        echo ("<div class='about'>".$row['text']."</div><br style='clear:left;'><br>");
        }
    }
    else
    {
        echo "<p>Nothing to see here, yet</p><br>";
    }
    }
?>