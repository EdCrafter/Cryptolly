<?php
    include_once("include/db.php");
?>

<div id="assets-container">
    <div class="assets narrow-content">
        <?php
            $mysqli = new DB([
                "host" => "localhost", 
                "user" => "root", 
                "password" => "IPZ221Verdev", 
                "db" => "cryptolly",]
            );
            $sql = $mysqli ->find("prices")->select(['MAX(img_svg) as img','MAX(ticker) as ticker',
            'MAX(name) as name','MAX(currency_name) as currency','MAX(price) as price'])
            ->join('cryptocurrencies',[[
                'field' => 'prices.crypto_id',
                'condition'=>'=',
                'value'=> 'cryptocurrencies.crypto_id'
            ]]) 
            ->join('currency',[[
                'field' => 'prices.currency_id',
                'condition'=>'=',
                'value'=> 'currency.currency_id'
            ]]) 
            ->groupBy("cryptocurrencies.crypto_id")
            ->orderBy("ABS(TIMESTAMPDIFF(SECOND, 
            CONCAT(MAX(prices.date), ' ',MAX(prices.time)), 
            NOW()))")
            ->limit(7);
            $rows = $sql->rows();
            if ($rows === false) {
                echo 'Error select';
            } else {
                foreach($rows as $row){
                    echo "<div>";
                    echo"<div>".$row['img']."</div>";
                    echo"<div><span>".$row['ticker']."</span><span>".$row['name']."</span></div>";
                    echo"<div><span><span>".$row['currency']."</span><span>".$row['price'].'</span></span><span class="appreciation">2.43%</span></div>';
                    echo"</div>"; 
                }
            }
        ?>
    </div>
    <div class="assets wide-content">
        <?php
            $mysqli = new DB([
                "host" => "localhost", 
                "user" => "root", 
                "password" => "IPZ221Verdev", 
                "db" => "cryptolly",]
            );
            $sql = $mysqli ->find("prices")->select(['MAX(img_svg) as img','MAX(ticker) as ticker',
            'MAX(name) as name','MAX(currency_name) as currency','MAX(price) as price'])
            ->join('cryptocurrencies',[[
                'field' => 'prices.crypto_id',
                'condition'=>'=',
                'value'=> 'cryptocurrencies.crypto_id'
            ]]) 
            ->join('currency',[[
                'field' => 'prices.currency_id',
                'condition'=>'=',
                'value'=> 'currency.currency_id'
            ]]) 
            ->groupBy("cryptocurrencies.crypto_id")
            ->orderBy("ABS(TIMESTAMPDIFF(SECOND, 
            CONCAT(MAX(prices.date), ' ',MAX(prices.time)), 
            NOW()))")
            ->limit(7);
            $rows = $sql->rows();
            if ($rows === false) {
                echo 'Error select';
            } else {
                foreach($rows as $row){
                    echo "<div>";
                    echo"<div>".$row['img']."</div>";
                    echo"<div><span>".$row['ticker']."</span><span>".$row['name']."</span></div>";
                    echo"<div><span><span>".$row['currency']."</span><span>".$row['price'].'</span></span><span class="appreciation">2.43%</span></div>';
                    echo"</div>"; 
                }
            }
        ?>
    </div>
    <div class="view_more">
        <span>View more prices</span>
        <div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="13" viewBox="0 0 15 13" fill="none">
                    <path d="M13.458 6.27132L0.958008 6.27132" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M8.41699 1.25116L13.4587 6.27116L8.41699 11.292" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>
    </div>
</div>









<?php

/*
 SELECT
  MAX(cryptocurrencies.name) as name,
  MAX(cryptocurrencies.img_svg) as img,
  MAX(cryptocurrencies.ticker) as ticker,
  MAX(currency.currency_name) as currency,
  MAX(prices.price) as price
FROM
  prices
JOIN
  cryptocurrencies ON prices.crypto_id = cryptocurrencies.crypto_id
JOIN
  currency ON prices.currency_id = currency.currency_id
GROUP BY
  cryptocurrencies.crypto_id
ORDER BY
  ABS(TIMESTAMPDIFF(SECOND, CONCAT(MAX(prices.date), ' ', MAX(prices.time)), NOW()))
LIMIT 7;
 */
?>