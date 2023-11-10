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
        $sql = "INSERT INTO Prices (name, price_usd, date, time) 
            VALUES ((SELECT name FROM Cryptocurrencies WHERE ticker = '$ticker'), $current_price, CURRENT_DATE, CURRENT_TIME)";
        $mysqli->query($sql);
    }
    else{
        echo("Error1");
        echo($current_price);
    }
    if (is_numeric($hour_ago_price) || $hour_ago_price==null){
        $hour_ago_sql = "INSERT INTO Prices (name, price_usd, date, time) VALUES ((SELECT name FROM Cryptocurrencies WHERE ticker = '$ticker'), $hour_ago_price, CURRENT_DATE, CURRENT_TIME - INTERVAL 1 HOUR)";
        $mysqli->query($hour_ago_sql);
    }
    else{
        echo("Error2");
        echo($hour_ago_price);
    }
}

$mysqli->close();
?>