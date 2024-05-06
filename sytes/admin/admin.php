<?php
include_once("../../include/session.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin</title>
        <link rel="stylesheet" href="../../css/index.css">
        <link rel="stylesheet" href="../../components/css/profile.css">
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
                    echo "<h1>Admin page</h1><br>";
                ?>
                    <?php
            include("../home/footer.php");
            ?>
            </div>
        </div>
    </body>

</html>
