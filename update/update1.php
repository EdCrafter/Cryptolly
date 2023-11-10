<?php
$mysqli = new mysqli("localhost", "root", "IPZ221Verdev", "cryptolly");

if ($mysqli->connect_error) {
    die("Ошибка подключения к базе данных: " . $mysqli->connect_error);
}

$cryptocurrencies = array(
    'BTC',
    'ETH',
    'XRP',
    'OGN',
    'ACT',
    'USDT',
    'BNB',
);

foreach ($cryptocurrencies as $ticker) {
    $current_url = "https://min-api.cryptocompare.com/data/price?fsym=$ticker&tsyms=USD";
    $current_response = file_get_contents($current_url);
    $current_data = json_decode($current_response, true);
    $current_price = htmlentities($current_data['USD']);
    $one_hour_ago = time() - 3600; 

    $hour_ago_url = "https://min-api.cryptocompare.com/data/pricehistorical?fsym=$ticker&tsyms=USD&ts=$one_hour_ago";
    $hour_ago_response = file_get_contents($hour_ago_url);
    $hour_ago_data = json_decode($hour_ago_response, true);
    $hour_ago_price =htmlentities($hour_ago_data[$ticker]['USD']);
    if (is_numeric($current_price)){
        $sql = "INSERT INTO Prices (crypto_id, price_usd, date, time) 
            VALUES ((SELECT crypto_id FROM Cryptocurrencies WHERE ticker = '$ticker'), $current_price, CURRENT_DATE, CURRENT_TIME)";
        $mysqli->query($sql);
    }
    else{
        $error_message = "Invalid numeric value for price";

        $stmt_crypto_id = $mysqli->prepare("SELECT crypto_id FROM Cryptocurrencies WHERE ticker = ?");
        $stmt_crypto_id->bind_param("s", $ticker);
        $stmt_crypto_id->execute();
        $stmt_crypto_id->bind_result($crypto_id);
        $stmt_crypto_id->fetch();
        $stmt_crypto_id->close();

        $stmt_error_log = $mysqli->prepare("INSERT INTO ErrorLog (error_message, crypto_id) VALUES (?, ?)");
        $stmt_error_log->bind_param("si", $error_message, $crypto_id);
        $stmt_error_log->execute();
        $stmt_error_log->close();
    }
    if (is_numeric($hour_ago_price) || $hour_ago_price==null){
        $hour_ago_sql = "INSERT INTO Prices (crypto_id, price_usd, date, time) VALUES ((SELECT crypto_id FROM Cryptocurrencies WHERE ticker = '$ticker'), $hour_ago_price, CURRENT_DATE, CURRENT_TIME - INTERVAL 1 HOUR)";
        $mysqli->query($hour_ago_sql);
    }
    else{
        $stmt_crypto_id = $mysqli->prepare("SELECT crypto_id FROM Cryptocurrencies WHERE ticker = ?");
        $stmt_crypto_id->bind_param("s", $ticker);
        $stmt_crypto_id->execute();
        $stmt_crypto_id->bind_result($crypto_id);
        $stmt_crypto_id->fetch();
        $stmt_crypto_id->close();

        $userTimeZone = 'Europe/Kiev'; 

        $currentDateTime = new DateTime();
        $currentDateTime->setTimezone(new DateTimeZone($userTimeZone));

        $currentDateTime->modify('-1 hour');

        $newDateTime = $currentDateTime->format('Y.m.d H:i:s');

        $error_message = "Invalid numeric value for price"; 

        $sql = "INSERT INTO ErrorLog (error_message, error_timestamp, crypto_id) VALUES (?, ?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssi", $error_message, $newDateTime, $crypto_id);
        if (!$stmt->execute()) {
            echo("Ошибка при выполнении запроса: " . $stmt->error);
            echo "<br>";
        } 
        $stmt->close();


    }
}

$mysqli->close();
?>