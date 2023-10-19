<?php

$mysqli = new mysqli("localhost", "root", "IPZ221Verdev", "cryptolly");

// Проверка соединения
if ($mysqli->connect_error) {
    die("Ошибка подключения к базе данных: " . $mysqli->connect_error);
}

// Получение текущей цены биткоина
$url = 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd';
$response = file_get_contents($url);
$data = json_decode($response, true);

foreach ($data as $cryptoId => $cryptoData) {
    $current_price = $cryptoData['usd'];
    $sql = "INSERT INTO Prices (name, price_usd, date, time) VALUES ((SELECT name FROM Cryptocurrencies WHERE ticker = '$cryptoId'), $current_price, CURRENT_DATE, CURRENT_TIME)";
    $mysqli->query($sql);
}

// Закрытие соединения
$mysqli->close();
?>
