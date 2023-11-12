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
        $sql = "INSERT INTO Prices (crypto_id, price_usd, date, time) VALUES ((SELECT crypto_id FROM Cryptocurrencies WHERE ticker = '$ticker'), $current_price, CURRENT_DATE, CURRENT_TIME)";
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


        //$date = date("d-m-Y H:i:s",time());
        // $fh  = fopen("errors.txt",'a');
        
        // if ($fh){
        //     fwrite($fh,"Error $date : $current_price \n");
        //     fclose($fh);
        // }
    }
}

// Закрытие соединения
$mysqli->close();
?>
