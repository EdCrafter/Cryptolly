<?php
include("../include/db.php");
include("../include/dataProcessor.php");


$mysqli = new DB([
    "host" => "localhost", 
    "user" => "root", 
    "password" => "IPZ221Verdev", 
    "db" => "cryptolly",]
);

if ($mysqli->isConnect()) {
    $userTimeZone = 'Europe/Kiev'; 
    $currentDateTime = new DateTime();
    $currentDateTime->setTimezone(new DateTimeZone($userTimeZone));
    $sql = $mysqli ->find("cryptocurrencies")
    ->select(['ticker']) ;
    $cryptocurrencies = $sql->rows() ;

    foreach ($cryptocurrencies as $arr) {
        foreach ($arr as $item=>$value) {
            $crypto_id = $mysqli ->find("cryptocurrencies")
            ->select('crypto_id')->where("ticker","=",$value)->rows();
            $crypto_id=$crypto_id[0]['crypto_id'];
            $url = "https://min-api.cryptocompare.com/data/v2/histoday";
            $time = time();
            $params = array(
                "fsym"=>"$value",
                "tsym"=>"USD",
                "toTs"=>"$time",
                "limit"=>"33",
            );
            $data = $mysqli -> update($url,$params);
            $data = DataProcessor::htmlEntitiesRecursive($data);
            if ($data === NULL || empty($data['Data']['Data'])) {
                $error_time  = $currentDateTime->format("Y-m-d H:i:s");
                $error_message = "Error when retrieving data";
                $error_sql  = $mysqli ->find("errorlog")
                ->insert(['error_message','error_timestamp','crypto_id'],
                [$error_message,$error_time,$crypto_id])
                ->sql();
                $mysqli->executeQuery($error_sql);
            }
            else{
                foreach ($data['Data']['Data'] as $priceData) {
                    $Y = date('Y', $priceData['time']);
                    $m = date('m', $priceData['time']);
                    $d = date('d', $priceData['time']);
                    $h = date('H', $priceData['time']);
                    $i = date('i', $priceData['time']);
                    $s = date('s', $priceData['time']);
                    if (!is_numeric($priceData['time']) || !(checkdate($m,$d,$Y)) || $h < 0 || $h > 23 || $i < 0 || $i > 59 || $s < 0 || $s > 59){
                        $time  = $currentDateTime->format("Y-m-d H:i:s");
                        $error_message = "Invalid value for date";
                        $error_sql  = $mysqli ->find("errorlog")->insert(['error_message','error_timestamp','crypto_id'],[$error_message,$time,$crypto_id])
                        ->sql();
                        $mysqli->executeQuery($error_sql);
                    }
                    else{
                        $date = date("Y-m-d",$priceData['time']); 
                        $time = date("H:i:s",$priceData['time']); 
                        $price = $priceData['close'];
                        if (!(is_numeric($price))){
                            $error_time  = $currentDateTime->format("Y-m-d H:i:s");
                            $error_message = "Invalid numeric value for price"."(".$date." ".$time.")";
                            $error_sql  = $mysqli ->find("errorlog")
                            ->insert(['error_message','error_timestamp','crypto_id'],
                            [$error_message,$error_time,$crypto_id])
                            ->sql();
                            $mysqli->executeQuery($error_sql);
                        }
                        else{
                            
                            $sql = $mysqli ->find("prices")
                            ->insert(['crypto_id', 'price_usd', 'date','time'],[$crypto_id, $price, $date,$time])->sql() ;
                            $mysqli->executeQuery($sql);
                        
                        }
                    }
                    
                }
            }
        }
    }
}
?>
