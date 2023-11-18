<?php

include_once("../include/db.php");
include_once("../include/dataProcessor.php");
$mysqli = new DB([
    "host" => "localhost", 
    "user" => "root", 
    "password" => "IPZ221Verdev", 
    "db" => "cryptolly",]
);

if ($mysqli->isConnect()) {
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
                $tableFieldName = 'price_usd';
                $mysqli->updateV1($current_price,"errorlog",$errorFields, $errorFieldsValue,'error_message',"prices",$tableFields,$tableFieldsValue,$tableFieldName);
                
            }
        }
    }
}

?>