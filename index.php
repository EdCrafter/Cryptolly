<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css?family=Sofia:regular" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Inter:,regular,medium" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Sora:bold" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:regular,semibold,medium" rel="stylesheet" />
    <script src="./components/js/home.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <div class="main_container">
        <div class="container">
            <header>
            <?php
            include("./sytes/home/header.php");
            include("./sytes/home/hero.php");
            ?>
            </header>
            <?php
            include("./sytes/home/main/assets.php");
            include("./sytes/home/footer.php");
            ?>
        </div>
    </div>
</body>

</html>


