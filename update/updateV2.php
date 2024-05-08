<?php

include_once("../include/db.php");
include_once("../include/dataProcessor.php");
$mysqli = new DB([
    "host" => "localhost", 
    "user" => "root", 
    "password" => "IPZ221Verdev", 
    "db" => "cryptolly",]
);
$response['status'] = 'success';

if ($mysqli->isConnect()) {
    $sql = $mysqli->find("prices")->select(["*"])->where("date","=",date("Y-m-d"))->sql();
    $r = $mysqli->query($sql);
    if ($r) {
        $response['status'] = 'success';
        $response['message'] = 'Data already updated';
        echo json_encode($response);
        exit();
    }
    $sql = $mysqli ->find("cryptocurrencies")
    ->select(['ticker']) ;
    $cryptocurrencies = $sql->rows() ;
    $userTimeZone = 'Europe/Kiev'; 
    $currentDateTime = new DateTime();
    $currentDateTime->setTimezone(new DateTimeZone($userTimeZone));
    foreach ($cryptocurrencies as $arr) {
        foreach ($arr as $item=>$value) {
            $crypto_id = $mysqli ->find("cryptocurrencies")
            ->select('crypto_id')->where("ticker","=",$value)->rows();
            $crypto_id=$crypto_id[0]['crypto_id'];
            $url = "https://min-api.cryptocompare.com/data/price";
            $time = time();
            
            $params = array(
                "fsym"=>"$value",
                "tsyms"=>"USD",
                "toTs"=>"$time",
            );
            $data = $mysqli -> update($url,$params);
            $data = DataProcessor::htmlEntitiesRecursive($data);
            if ($data === NULL || empty($data['USD'])) {
                $error_time  = $currentDateTime->format("Y-m-d H:i:s");
                $error_message = "Error when retrieving data";
                $error_sql  = $mysqli ->find("errorlog")
                ->insert(['error_message','error_timestamp','crypto_id'],
                [$error_message,$error_time,$crypto_id])
                ->sql();
                $mysqli->executeQuery($error_sql);
            }
            else{
                $date  = $currentDateTime->format("Y-m-d");
                $time  = $currentDateTime->format("H:i:s");
                $current_price = htmlentities($data['USD']);
                $error_time  = $currentDateTime->format("Y-m-d H:i:s");
                $errorFields = ['error_timestamp','crypto_id'];
                $errorFieldsValue = [$error_time,$crypto_id];
                $tableFields = ['crypto_id', 'date','time'];
                $tableFieldsValue = [$crypto_id, $date,$time];
                $tableFieldName = 'price';
                $mysqli->updateV1($current_price,"errorlog",$errorFields, $errorFieldsValue,'error_message',"prices",$tableFields,$tableFieldsValue,$tableFieldName);
                
            }
            
        }
    }
    $time -= 24 * 60 * 60;
    $errorDateTime = $currentDateTime;
    $currentDateTime->modify('-1 day');
    foreach ($cryptocurrencies as $arr) {
        foreach ($arr as $item=>$value) {
            $crypto_id = $mysqli ->find("cryptocurrencies")
            ->select('crypto_id')->where("ticker","=",$value)->rows();
            $crypto_id=$crypto_id[0]['crypto_id'];
            $url = "https://min-api.cryptocompare.com/data/price";
            $time = time();
            $params = array(
                "fsym"=>"$value",
                "tsyms"=>"USD",
                "toTs"=>"$time",
            );
            $data = $mysqli -> update($url,$params);
            $data = DataProcessor::htmlEntitiesRecursive($data);
            if ($data === NULL || empty($data['USD'])) {
                $error_time  = $errorDateTime->format("Y-m-d H:i:s");
                $error_message = "Error when retrieving data";
                $error_sql  = $mysqli ->find("errorlog")
                ->insert(['error_message','error_timestamp','crypto_id'],
                [$error_message,$error_time,$crypto_id])
                ->sql();
                $mysqli->executeQuery($error_sql);
            }
            else{
                $date  = $currentDateTime->format("Y-m-d");
                $time  = $currentDateTime->format("H:i:s");
                $current_price = htmlentities($data['USD']);
                $error_time  = $errorDateTime->format("Y-m-d H:i:s");
                $errorFields = ['error_timestamp','crypto_id'];
                $errorFieldsValue = [$error_time,$crypto_id];
                $tableFields = ['crypto_id', 'date','time'];
                $tableFieldsValue = [$crypto_id, $date,$time];
                $tableFieldName = 'price';
                $mysqli->updateV1($current_price,"errorlog",$errorFields, $errorFieldsValue,'error_message',"prices",$tableFields,$tableFieldsValue,$tableFieldName);
                
            }
            
        }
    }
    $response['message'] = 'Data updated successfully';
    echo json_encode($response);
}
else{
    $response['status'] = 'error';
    $response['message'] = 'Database connection error';
    echo json_encode($response);
}

/*
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
*/
?>