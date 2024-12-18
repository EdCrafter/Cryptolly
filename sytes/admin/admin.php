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
    <link rel="stylesheet" href="../../components/css/admin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../components/js/admin.js"></script>
    <script src="../../js/jquery-1.7.2.min.js"></script>
    <script src="../../js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="../../js/jqGrid/i18n/grid.locale-ua.js"></script>
    <script src="../../js/jqGrid/jquery.jqGrid.min.js"></script>
    
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
            echo "<h2>Prices of cryptocurrencies</h2>";
            echo "<iframe src='grid/index.php' class='myIframe' onload='resizeIframe(this)'></iframe>";
            ?>
            <button class="button-container" id="price-update">Update</button>
            <h2>Users</h2>
            <iframe src='usersGrid/index.php' class='myIframe' onload='resizeIframe(this)'></iframe>
            <?php
            include("../home/footer.php");
            ?>
        </div>
    </div>
</body>
<script>
    // Функция для изменения размеров iframe
    function resizeIframe() {
        var iframes = document.getElementsByClassName("myIframe");
        for (var i = 0; i < iframes.length; i++) {
            var iframe = iframes[i];
            var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            iframe.style.height = iframeDoc.body.scrollHeight + 20 + 'px';
        }
    }

    // Вызываем функцию каждую секунду
    setInterval(resizeIframe, 1000);
</script>

</html>