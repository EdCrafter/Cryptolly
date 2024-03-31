<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="../../css/index.css">


</head>

<body>

    <div class="main_container">
        <div class="container">
            <header>
                <?php
                include("../home/header.php");
                echo "</header>";
                include_once("../../include/session.php");

                if (isset($_SESSION['username'])) {
                    destroySession();
                    echo "<br><h1>You have been logged out. Please</h1><a class='btn' href='index.php'>click here</a><h1>to refresh the screen.</h1>";
                } else echo "<h1>You cannot log out because you are not logged in</h1>";
                include("../home/footer.php");
                ?>

        </div>
    </div>
</body>

</html>