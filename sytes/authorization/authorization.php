<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    session_start();
    include_once("../../include/db.php");
    include_once("../../include/createDB.php");

    $rows = $mysqli->query("Select * from members where username = '$_POST[username]'");

    if ($rows) {
        var_dump($rows[0]);
        $row = $rows[0];
        if (password_verify($_POST['password'], $row['password'])) {
            echo "You are logged in.";
            echo $_POST['username'];
            echo $_POST['password'];
            
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['surname'] = $row['surname'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['age'] = $row['age'];
            $_SESSION['admin'] = $row['admin'];
        }
        echo $_SESSION['username']."<br>";
        echo $_SESSION['password']."<br>";
        echo $_SESSION['name']."<br>";
        echo $_SESSION['surname']."<br>";
        echo $_SESSION['email']."<br>";
        echo $_SESSION['age']."<br>";
        echo $_SESSION['admin']."<br>";


    } else {
        echo "You are not logged in.";
    }
?>
</body>
</html>