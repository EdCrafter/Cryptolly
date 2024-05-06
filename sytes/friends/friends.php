<?php
include_once("../../include/session.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../components/css/members.css">

</head>

<body>
    <div class="main_container">
        <div class="container">
            <header>

                <?php
                include("../home/header.php");
                if (!$loggedin) {
                    die("<h1>You need to be logged in to view this page. </h1>
                        <button class='button-container' onclick=\"window.location.href = 'login.php';\"
                        style=\" font-size: x-large; border-radius: 0; margin-top: 30px;\">
                        Get Started
                    </button> ");
                }
                ?>

            </header>
            <?php
            include_once("../../include/showProfile.php");
            include_once("../../include/dataProcessor.php");
            if (isset($_GET['view'])) $view = DataProcessor::sanitizeString($_GET['view']);
            else                      $view = $user;

            if ($view == $user) {
                $name1 = $name2 = "Your";
                $name3 =          "You are";
            } else {
                $name1 = "<a class='member' data-transition='slide'
                                href='members.php?view=$view&r=$randstr'>$view</a>'s";
                $name2 = "$view's";
                $name3 = "$view is";
            }

            // Uncomment this line if you wish the user�s profile to show here
            // showProfile($view);

            $followers = array();
            $following = array();

            $result = $mysqli->find("friends")->select(['*'])
                ->where('user', '=', $view)->where('status', '=', 'friends')
                ->sql();
            $mutual    = $mysqli->query($result);

            $result = $mysqli->find("friends")->select(['*'])
                ->where('user', '=', $view)->where('status', '=', 'follower')
                ->sql();
            $followers = $mysqli->query($result);

            $result = $mysqli->find("friends")->select(['*'])
                ->where('friend', '=', $view)->where('status', '=', 'follow')
                ->sql();
            $following = $mysqli->query($result);

            $friends   = FALSE;

            echo "<br>";

            if (sizeof($mutual)) {
                echo "<h1 class='subhead'>$name2 mutual friends</h1><ul>";
                foreach ($mutual as $friend)
                    $friend = $friend['friend'];
                echo "<li><a class='member' data-transition='slide'
                              href='members.php?view=$friend&r=$randstr'>$friend</a>";
                echo "</ul>";
                $friends = TRUE;
            }

            if (sizeof($followers)) {
                echo "<h1 class='subhead'>$name2 followers</h1><ul>";
                foreach ($followers as $friend)
                    $friend = $friend['friend'];
                echo "<li><a class='member' data-transition='slide'
                              href='members.php?view=$friend&r=$randstr'>$friend</a>";
                echo "</ul>";
                $friends = TRUE;
            }

            if (sizeof($following)) {
                echo "<h1 class='subhead'>$name3 following</h1><ul>";
                foreach ($following as $friend)
                    $friend = $friend['friend'];
                echo "<li><a class='member' data-transition='slide'
                              href='members.php?view=$friend&r=$randstr'>$friend</a>";
                echo "</ul>";
                $friends = TRUE;
            }

            if (!$friends) echo "<br>You don't have any friends yet.";
            ?><br>
            <?php
            include("../home/footer.php");
            ?>
        </div>
    </div>
</body>

</html>