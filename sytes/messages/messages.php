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
    <link rel="stylesheet" href="../../components/css/messages.css">
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
            if (isset($_POST['text'])) {
                $text = $_POST['text'];
                $text = preg_replace('/\n/', '//n', $text);
                $text = DataProcessor::sanitizeString($text);
                $text = preg_replace('/\s\s+/', ' ', $text);
                $text = preg_replace('/\/\/n/', '\n', $text);
                if ($text != "") {
                    $private   = substr(DataProcessor::sanitizeString($_POST['private']), 0, 1);
                    $time = time();
                    $sql = $mysqli->find("messages")
                        ->insert(['auth', 'recip', 'private', 'time', 'message'],
                            [$user, $view, $private, $time, $text])
                        ->sql();
                    $mysqli->executeQuery($sql);
                }
            }
            if ($view != "") {

                echo "<h1>Messages for</h1>";
                showProfile($view);

                echo <<<_END
      <form method='post' action='messages.php?view=$view&r=$randstr'>
        <fieldset data-role="controlgroup" data-type="horizontal">
          <legend>Type here to leave a message</legend>
          <input type='radio' name='private' id='public' value='0' checked='checked'>
          <label for="public">Public</label>
          <input type='radio' name='private' id='private' value='1'>
          <label for="private">Private</label>
        </fieldset>
      <textarea autocorrect="on" maxlength="1000" name='text' cols='50' rows='5'></textarea>
      <input data-transition='slide' type='submit' value='Post Message'>
    </form><br>
_END;

                date_default_timezone_set('UTC');

                if (isset($_GET['erase'])) {
                    $erase = DataProcessor::sanitizeString($_GET['erase']);
                    $sql=$mysqli->find("messages")
                        ->delete()
                        ->where('id', '=', $erase)
                        ->where('recip', '=', $user)
                        ->sql();
                    $mysqli->executeQuery($sql);
                }

                $result=$mysqli->find("messages")
                    ->select(['*'])
                    ->where('recip', '=', $view)
                    ->orderBy('time', 'DESC')
                    ->sql();
                $r = $mysqli->query($result);

                foreach ($r as $row) {
                    
                    if ($row['private'] == "0" || $row['auth'] == $user || $row['recip'] == $user) {// || ($row['private'] == 1 && $row['auth'] == $user)) {
                        echo date('M jS \'y g:ia:', $row['time']);
                        echo "<div class='message'> <a class='member'  href='messages.php?view=" . $row['auth'] .
                            "&r=$randstr'>" . $row['auth'] . "</a> ";

                        if ($row['private'] == "0"){
                            echo "-wrote: &quot;" . $row['message'] . "&quot; ";
                        }
                        else{
                            echo "-whispered: <span class='whisper'>&quot;" .
                                $row['message'] . "&quot;</span>";
                        }
                        if ($row['auth'] == $user)
                            echo "[<a class='depreciation' href='messages.php?view=$user" .
                                "&erase=" . $row['id'] . "&r=$randstr'>erase</a>]";
                        echo "</div>";

                        echo "<br>";
                    }
                }
            }

            if (!$r)
                echo "<br><span class='info'>No messages yet</span><br><br>";

            echo "<div class='button-container'><a data-role='button'
        href='messages.php?view=$view&r=$randstr' class='refresh'>Refresh messages</a></div>";
            ?>
            <?php
            include("../home/footer.php");
            ?>
        </div>
    </div>
</body>

</html>