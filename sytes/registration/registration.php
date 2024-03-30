<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../components/css/registration.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../components/js/registration.js"></script>
</head>

<body>

    <?php
    include_once("../../include/session.php");
    ?>
    <div class="main_container">
        <div class="container">
            <header>
                <?php
                include("../home/header.php");
                ?>
            </header>
            <?php
            if (isset($_SESSION["user"])) {
                destroySession();
            }
            ?>

            <h1 id="prices">
                Cryptocurrencies
            </h1>
            <div class="registration">
                <div class="registration__top">
                    <button class="active">Client</button>
                    <button>Admin</button>
                </div>
                <form id="form" method="POST" action="validate.php">
                    <h2>Registration Form</h2>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    <br>
                    <label for="surname">Surname:</label>
                    <input type="text" id="surname" name="surname" required>
                    <br>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required >
                    <br>
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" required>
                    <br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <br>
                    <label for="password" z>Password:</label>
                    <input type="password" id="password" name="password" autocomplete="off" required>
                    <br>
                    <label for="password2">Repeat password:</label>
                    <input type="password" id="password2" name="password2" autocomplete="off" required>
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