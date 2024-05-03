<?php
include_once("../../include/session.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../components/css/members.css">

</head>

<body>
    <div class="main_container">
        <div class="container">
            <header>

                <?php
                include("../home/header.php");
                if (!isset($_SESSION['username'])) {
                    die("<h1>You need to be logged in to view this page. </h1>
                        <button class='button-container' onclick=\"window.location.href = 'login.php';\"
                        style=\" font-size: x-large; border-radius: 0; margin-top: 30px;\">
                        Get Started
                    </button> ");
                }
                ?>

            </header>
            <div>
                <h1>Members</h1>
                <?php
                include_once("../../include/showProfile.php");
                if (isset($_GET['view'])) {
                    $view = DataProcessor::sanitizeString($_GET['view']);

                    if ($view == $user) $name = "Your";
                    else                $name = "$view's";

                    echo "<h3>$name Profile</h3>";
                    showProfile($view);
                    echo "<a data-role='button' data-transition='slide' class='member'
                        href='messages.php?view=$view&r=$randstr'>View $name messages</a>";
                    die("</div></body></html>");
                }

                if (isset($_GET['add'])) {
                    $add = DataProcessor::sanitizeString($_GET['add']);

                    $result = $mysqli->find("friends")->select("status")->where('user', '=', $add)
                        ->where('friend', '=', $user)->sql();
                    $r = $mysqli->queryOne($result)['status'];
                    if ($r == 'follow') {
                        $sql = $mysqli->find("friends")->update("status", "friends")->where('user', '=', $user)
                            ->where('friend', '=', $add)->sql();
                        $mysqli->executeQuery($sql);
                        $sql = $mysqli->find("friends")->update("status", 'friends')->where('user', '=', $add)
                            ->where('friend', '=', $user)->sql();
                        $mysqli->executeQuery($sql);
                    } else if (!$r){
                        $sql = $mysqli->find("friends")->insert(['user', 'friend', 'status'], [$add, $user, 'followed'])
                            ->sql();
                        $mysqli->executeQuery($sql);
                        $sql = $mysqli->find("friends")->insert(['user', 'friend', 'status'], [$user, $add, 'follow'])
                            ->sql();
                        $mysqli->executeQuery($sql);
                    }
                } elseif (isset($_GET['remove'])) {
                    $remove = DataProcessor::sanitizeString($_GET['remove']);
                    $result = $mysqli->find("friends")->select("status")->where('user', '=', $user)
                        ->where('friend', '=', $remove)->sql();
                    $t = $mysqli->queryOne($result)['status'];
                    if ($t == 'friends') {
                        $sql = $mysqli->find("friends")->update("status", 'followed')->where('user', '=', $user)
                            ->where('friend', '=', $remove)->sql();
                        $mysqli->executeQuery($sql);
                        $sql = $mysqli->find("friends")->update("status", 'follow')->where('user', '=', $remove)
                            ->where('friend', '=', $user)->sql();
                        $mysqli->executeQuery($sql);
                    } elseif ($t == 'follow') {
                        $sql = $mysqli->find("friends")->delete()->where('user', '=', $user)
                            ->where('friend', '=', $remove)->sql();
                        $mysqli->executeQuery($sql);
                        $sql = $mysqli->find("friends")->delete()->where('user', '=', $remove)
                            ->where('friend', '=', $user)->sql();
                        $mysqli->executeQuery($sql);
                    }


                }

                $result = $mysqli->find("members")->select(['username'])
                    ->where('username', '!=', $user);
                $num = $result->count();
                $r = $mysqli->query($result->sql());
                foreach ($r as $row) {

                    if ($row['username'] == $user) continue;
                    echo "<div><a class='member' data-transition='slide' href='members.php?view=" .
                        $row['username'] . "&$randstr'>" . $row['username'] . "</a>";
                    $follow = "follow";

                    $result = $mysqli->find("friends")->select("status")->where('user', '=', $user)
                        ->where('friend', '=', $row['username'])->sql();

                    $t1 = $mysqli->queryOne($result)['status'];
                    if ($t1 == 'follow') {
                        $result1 = $mysqli->find("friends")->select("status")->where('user', '=', $row['username'])
                            ->where('friend', '=', $user)->sql();
                        $t2 = $mysqli->queryOne($result1)['status'];
                        if ($t2 == 'follow') {
                            $sql = $mysqli->find("friends")->update("status", 'friends')->where('user', '=', $row['username'])
                                ->where('friend', '=', $user)->sql();
                            $mysqli->executeQuery($sql);
                            $sql = $mysqli->find("friends")->update("status", 'friends')->where('user', '=', $user)
                                ->where('friend', '=', $row['username'])->sql();
                            $mysqli->executeQuery($sql);
                            $follow = "friends";
                        } else {
                            $follow = "followed";
                            echo "<span class='member_status'> &larr; you are following</span>";
                        }
                    } elseif ($t1 == 'friends') echo "<span class='member_status'> &harr; is a mutual friend</span>";
                    elseif ($t1 == 'followed') {
                        echo "<span class='member_status'> &rarr; is following you</span>";
                        $follow = "recip";
                    }

                    if (!$t1 || $t1 == 'followed') echo " [<a data-transition='slide' class='member_info'
                    href='members.php?add=" . $row['username'] . "&r=$randstr'>$follow</a>]";
                    else      echo " [<a data-transition='slide' class='member_info'
                    href='members.php?remove=" . $row['username'] . "&r=$randstr'>drop</a>]";
                    echo "</div>";
                }

                ?>
            </div>
            <?php
            include("../home/footer.php");
            ?>
        </div>
    </div>
</body>

</html>