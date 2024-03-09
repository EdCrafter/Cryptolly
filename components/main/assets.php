<?php
include_once("include/db.php");
include_once("include/image.php");
?>

<div id="assets-container">
    <div class="assets narrow-content">
        <?php
        $mysqli = new DB(
            [
                "host" => "localhost",
                "user" => "root",
                "password" => "IPZ221Verdev",
                "db" => "cryptolly",
            ]
        );
        $sql = $mysqli->find("prices")->select([
            'MAX(img_svg) as img', 'MAX(ticker) as ticker',
            'MAX(name) as name', 'MAX(currency_name) as currency', 'MAX(price) as price'
        ])
            ->join('cryptocurrencies', [[
                'field' => 'prices.crypto_id',
                'condition' => '=',
                'value' => 'cryptocurrencies.crypto_id'
            ]])
            ->join('currency', [[
                'field' => 'prices.currency_id',
                'condition' => '=',
                'value' => 'currency.currency_id'
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
            foreach ($rows as $row) {
                echo "<div>";
                echo "<div>" . $row['img'] . "</div>";
                echo "<div><span>" . $row['ticker'] . "</span><span>" . $row['name'] . "</span></div>";
                echo "<div><span><span>" . $row['currency'] . "</span><span>" . $row['price'] . '</span></span><span class="appreciation">2.43%</span></div>';
                echo "</div>";
            }
        }
        ?>
    </div>
    <div class="assets wide-content">
        <table>
            <thead>
                <tr>

                    <th>Assets</th>
                    <th>Last price</th>
                    <th>Change</th>
                    <th>Chart</th>
                    <th>Trade</th>
                </tr>
            </thead>
            <tbody class="assets-content">
                <?php
                $mysqli = new DB(
                    [
                        "host" => "localhost",
                        "user" => "root",
                        "password" => "IPZ221Verdev",
                        "db" => "cryptolly",
                    ]
                );
                $sql = $mysqli->find("cryptocurrencies c")->select([
                    'c.img_svg as img', 'c.ticker',
                    'c.name', 'cu.currency_name as currency', 'p.price'
                ])
                    ->join(' max_datetime', [[
                        'field' => 'c.crypto_id',
                        'condition' => '=',
                        'value' => 'max_datetime.crypto_id'
                    ]],(
                        $mysqli->find('prices')
                        ->select(['crypto_id',"MAX(CONCAT(date, ' ', time)) as max_datetime"])
                        ->groupBy("crypto_id")->sql()
                    ))
                    ->join('prices p ', [[
                        'field' => 'p.crypto_id',
                        'condition' => '=',
                        'value' => 'c.crypto_id'
                    ],
                    [
                        'field' => "CONCAT(p.date, ' ', p.time)",
                        'condition' => '=',
                        'value' => 'max_datetime.max_datetime'
                    ]
                    ])
                    ->join('currency cu', [[
                        'field' => 'p.currency_id',
                        'condition' => '=',
                        'value' => 'cu.currency_id'
                    ]])
                    ->orderBy("ABS(TIMESTAMPDIFF(SECOND, max_datetime.max_datetime, NOW()))")
                    ->limit(7);
                $rows = $sql->rows();
                if ($rows === false) {
                    echo 'Error select';
                } else {
                    foreach ($rows as $row) {
                        $change=0;
                        $sign = '';
                        $class = null;
                        $visibility = '';
                    
                        $timeBefore= '1 day';  
                        $crypto_id = $mysqli->find('cryptocurrencies')->select('cryptocurrencies.crypto_id')
                        ->where('ticker','=',$row['ticker']);
                        $crypto_id = $mysqli->query($crypto_id->sql())[0]['crypto_id'];
                        $sql=$mysqli->find('prices')->select('price')->where('prices.crypto_id','=',$crypto_id)
                        ->orderBy("ABS(TIMESTAMPDIFF(SECOND, 
                        CONCAT(prices.date, ' ',prices.time), 
                        DATE_SUB(NOW(), INTERVAL $timeBefore)))")->limit(12);
        
                        $arrCh=[];
                        $i=0;
                        $price = $row['price'];
                        $sumCh=0;
                        while ($i<12){
                            $priceBefore = $mysqli->query($sql->sql())[$i]['price'];
                            
                            if($priceBefore<0.001){
                                if($price<0.001){
                                    $priceBefore= $price=1;
                                }
                                else{

                                    $priceBefore= 0.001;
                                }
                            }
                            $change=(100*$price)/$priceBefore-100 ;
                            
                            $arrCh[] = $change;
                            $sumCh+=$change;
                            $price = $priceBefore;
                            $i++;
                        }
                    
                        if ($arrCh[0]>100) {
                            $sign = '>';
                            $arrCh[0] =100;
                        }
                        if ($arrCh[0]<-100) {
                            $sign= '<';
                            $arrCh[0] =-100;
                        }
                        if ($arrCh[0]>0) {
                            $class = 'appreciation';
                        }
                        if ($arrCh[0]<0) {
                            $class = 'depreciation';
                        }
                        $change = number_format(($arrCh[0]) ,2);
                    
                        echo '<tr>';
                        echo "<td><div>" . ($num + 1) . "</div></td>";
                        echo "<td class='table-assets'>";
                        echo "<div>";
                        echo "<div>";
                        echo $row['img'];
                        echo "<div>" . $row['ticker'] . "</div>";
                        echo "</div>";
                        echo "<div>" . $row['name'] . "</div>";
                        echo "</div>";
                        echo "</td>";
                        echo "<td><div><span><span>" . $row['currency'] . "</span><span>" . $row['price'] . '</span></span></div></td>';
                        echo "<td>";
                        echo '<div><span class='.$class.'>'.$sign.$change.'%</span></div>';
                        echo "</td>";
                        echo "<td class='chart'><div>";
                        $diagr = new Diagram();
                        $diagr->setWidth(180);
                        $diagr->setHeight(70);
                        $diagr->setMin(0);
                        $diagr->setBgColor(30,33,50);
                        $diagr->setAxisColor(255,255,255);
                        $diagr->setColors([
                            [255, 0, 0],
                            [0, 255, 0],
                        ]);
                        if (! $sumCh){

                            $arrCh = [0.1,0.1,0.1,0.1,0.1,0.1,0.1,0.1,0.1,0.1,0.1,10];
                        }
                        $arrCh = array_reverse($arrCh);
                        $diagr->setData($arrCh);
                        ob_start();
                        $diagr->draw();
                        $imageData = ob_get_clean();
                        $imagePath = 'img/image.png';
                        $image = imagecreatefromstring($imageData);
                        imagepng($image, $imagePath);
                        $imageDataEncoded = base64_encode(file_get_contents($imagePath));
                        echo '<img src="data:image/png;base64,' . $imageDataEncoded . '" alt="chart">';
                        echo "</div></td>";
                        echo "<td><div class='button--buy'>";
                        echo '<a href="#">Buy</a>';
                        echo "</div></td>";
                        echo "</tr>";
                        $num++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <a href="/sytes/morePrices.php">
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
    </a>
</div>









<?php

/*
 SELECT
  c.img_svg as img,
  c.ticker,
  c.name,
  cu.currency_name as currency,
  p.price
FROM cryptocurrencies c
JOIN (
  SELECT
    crypto_id,
    MAX(CONCAT(date, ' ', time)) as max_datetime
  FROM prices
  GROUP BY crypto_id
) max_datetime ON c.crypto_id = max_datetime.crypto_id
JOIN prices p ON c.crypto_id = p.crypto_id AND CONCAT(p.date, ' ', p.time) = max_datetime.max_datetime
JOIN currency cu ON p.currency_id = cu.currency_id
ORDER BY ABS(TIMESTAMPDIFF(SECOND, max_datetime.max_datetime, NOW()));
LIMIT 7;
 */
?>