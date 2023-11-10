<?php

$mysqli = new mysqli("localhost", "root", "IPZ221Verdev", "cryptolly");

if ($mysqli->connect_error) {
    die("Ошибка подключения к базе данных: " . $mysqli->connect_error);
}
$cryptocurrencies = array(
    'BTC', 
    'ETH' ,
    'XRP' ,
    'OGN',
    'ACT' ,
    'USDT' ,
    'BNB' ,
);

foreach ($cryptocurrencies as $ticker) {
    $url = "https://min-api.cryptocompare.com/data/price?fsym=$ticker&tsyms=USD";
    $response = file_get_contents($url);

    // Декодирование JSON-ответа
    $data = json_decode($response, true);

    // Получение текущей цены в USD
    $current_price = htmlentities($data['USD']);
    if (is_numeric($current_price)){
        echo($current_price)." ";
        $sql = "INSERT INTO Prices (name, price_usd, date, time) VALUES ((SELECT name FROM Cryptocurrencies WHERE ticker = '$ticker'), $current_price, CURRENT_DATE, CURRENT_TIME)";
        $mysqli->query($sql);
    }
    else{
        echo("Error");
    }
}

// Закрытие соединения
$mysqli->close();
?>
