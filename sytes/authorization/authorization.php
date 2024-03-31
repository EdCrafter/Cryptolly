<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../components/css/registration.css">
</head>
<body>
    <?php
    session_start();
    include_once("../../include/db.php");
    include_once("../../include/createDB.php");
    include_once("../../include/dataProcessor.php");
    ?>
    <div class="main_container">
        <div class="container">
            <header>
                <?php
                include("../home/header.php");
                ?>
            </header>
<?php
    if (isset($_POST['username'])) {
        $username = DataProcessor::sanitizeString($_POST['username']);
        $password = DataProcessor::sanitizeString($_POST['password']);
        $rows = $mysqli->query("Select * from members where username = '$username'");
    
        if ($rows) {
            var_dump($rows[0]);
            $row = $rows[0];
            if (password_verify($password, $row['password'])) {
                echo "<h1>You are logged in !!!</h1>";
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                echo $_SESSION['username']."<br>";
                echo $_SESSION['password']."<br>";
                echo "<a href='logout.php' class='btn'>Profile</a>";
            }

    }



    } else {
        echo <<<_END
        
        <div class="registration">
        <h2>Authorization Form</h2>
        <form action="authorization.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" placeholder="Username">
        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Password">
        <input type="submit" value="Submit">
        </form>
        </div>
_END;
    
}
?>
<?php
            include("../home/footer.php");
            ?>
</div>
</div>

</body>
</html>