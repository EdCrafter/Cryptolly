<?php
    include_once("createDB.php");
    $randstr = substr(md5(rand()), 0, 7);
    function showProfile($user)
    {
      global $mysqli, $randstr;
        if (file_exists("../profile/img/$user.jpg")){
        echo "<img src='../profile/img/$user.jpg' style='float:left;'>";
        
        $result = $mysqli->find("profiles")->select(['*'])
            ->where('user','=', $user)
            ->sql();
        $r = $mysqli->query($result);
        echo "<a class='member' href='members.php?view=$user&r=$randstr'>$user</a>";
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