<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get admin</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../components/css/registration.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../components/js/getAdmin.js"></script>
</head>

<body>
    <div class="main_container">
        <div class="container">
            <header>
                <?php
                include("../home/header.php");
                ?>
                
            </header>
            <h1 id="prices">
                Get admin
            </h1>
            <div class="registration">
                <form id="form" method="POST" action="validate.php">
                    <h2>Verification Form</h2>
                    <label for="adminPass">Admin Password:</label>
                    <input type="text" id="adminPass" name="adminPass" required>
                    <input type="submit" value="Submit">
                </form>
            </div>
            <?php
            include("../home/footer.php");
            ?>
        </div>
    </div>
</body>

</html>