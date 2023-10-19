<?php
require 'vendor/autoload.php';
use Sunra\PhpSimple\HtmlDomParser;

// Подключение к базе данных
$mysqli = new mysqli("localhost", "root", "IPZ221Verdev", "cryptolly");

// Проверка соединения
if ($mysqli->connect_error) {
    die("Ошибка подключения к базе данных: " . $mysqli->connect_error);
}

// Символы криптовалют и их идентификаторы (ticker => name)
$cryptocurrencies = array(
    'BTC' => 'Bitcoin', 
    'ETH' => 'Ethereum',
    'XRP' => 'XRP',
    'OGN' => 'Origin Protocol',
    'ACT' => 'Achain',
    'USDT' => 'Tether',
    'BNB' => 'Binance Coin',
);

foreach ($cryptocurrencies as $ticker => $name) {
    // URL для запроса
    $url = "https://min-api.cryptocompare.com/data/v2/histoday?fsym=$ticker&tsym=USD&limit=33";

    // Выполнение запроса
    $response = file_get_contents($url);

    // Декодирование JSON
    $data = json_decode($response, true);

    // Проверка на наличие ошибок при выполнении запроса
    if ($data === NULL || empty($data['Data']['Data'])) {
        die("Ошибка при получении данных для $ticker.");
    }

    // Внесение исторических цен в базу данных
    foreach ($data['Data']['Data'] as $priceData) {
        $date = date('Y-m-d', $priceData['time']);
        $price = $priceData['close'];

        $sql = "INSERT IGNORE INTO Prices (name, price_usd, date,time) VALUES ('$name', $price, '$date','12:00:00')";
        $mysqli->query($sql);
    }
}

// Закрытие соединения
$mysqli->close();
?>
