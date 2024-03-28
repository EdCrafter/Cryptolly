<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prices</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../components/css/registration.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../components/js/registration.js"></script>
</head>

<body>

    <?php
    include_once("../../include/db.php");
    include_once("../../include/pagination.php");
    include_once("../../include/html.php");
    include_once("../../include/request.php");
    include_once("../../include/image.php");
    ?>
    <div class="main_container">
        <div class="container">
            <header>
                <?php
                include("../home/header.php");
                ?>
            </header>
            <?php
            $pagination = new Pagination();
            $pagination->limits = [3, 10, 50];
            $pagination->setLimit(Request::get('limit', 3));
            $mysqli = new DB(
                [
                    "host" => "localhost",
                    "user" => "root",
                    "password" => "IPZ221Verdev",
                    "db" => "cryptolly",
                ]
            );
            ?>

            <h1 id="prices">
                Cryptocurrencies
            </h1>
            <div class="registration">
                <div class="registration__top">
                    <button>Client</button>
                    <button>Admin</button>
                </div>
                <form action="register.php" method="POST" onsubmit="return validateForm(event)">
                    <h2>Registration Form</h2>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <br>
                    <label for="password2">Repeat password:</label>
                    <input type="password" id="password2" name="password2" required>
                    <input type="submit" value="Register">
                </form>
                <button class="btn">Log in</button>
            </div>
            <?php
            include("../home/footer.php");
            ?>
        </div>
    </div>
</body>

</html>