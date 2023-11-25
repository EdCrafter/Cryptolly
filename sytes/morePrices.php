<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prices</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="../components/css/morePrices.css">
    <script src="../components/js/morePrices.js"></script>
</head>

<body>
    <?php
    include_once("../include/db.php");
    include_once("../include/pagination.php");
    include_once("../include/html.php");
    include_once("../include/request.php");
    ?>
    <div class="main_container">
        <div class="container">
            <?php
            include("../components/main/hero.php");
            ?>
            <?php
            $pagination = new Pagination();
            $pagination->limits = [3, 10, 50];
            $pagination->setLimit(Request::get('limit', 3));
            $mysqli = new DB(
                [
                    "host" => "localhost",
                    "user" => "root",
                    "password" => "IPZ221Verdev",
                    "db" => "cryptolly",
                ]
            );
            ?>

            <h1 id="prices">
                Cryptocurrencies
            </h1>
            <div class="form-sort">
                <h3>
                    Sort
                </h3>
                <form method="GET">
                    <div class="sort-field">
                        <?php
                        $tickers = $mysqli->query('SELECT crypto_id as id, ticker as name  FROM cryptocurrencies ORDER BY ticker');
                        $ticker = null;
                        if (Request::get('ticker') !== null) {
                            foreach ($tickers as $s) {
                                if ($s['id'] == Request::get('ticker')) {
                                    $ticker = Request::get('ticker');
                                }
                            }
                        }
                        ?>
                        <div>
                            <div>
                                Crypto name:
                            </div>
                            <?php
                            HtmlHelper::inputText("name", Request::get('name'));
                            ?>
                        </div>
                        <div>
                            <div>
                                Ticker:
                            </div>
                            <?php
                            HtmlHelper::select("ticker", $tickers, $ticker, true);
                            ?>
                        </div>
                        <div class="max-len">
                            <h4>
                                Price
                            </h4>
                            <div>
                                <div>
                                    From:
                                </div>
                                <?php
                                HtmlHelper::inputText("priceFrom", Request::get('priceFrom'));
                                ?>
                            </div>
                            <div>
                                <div>
                                    To:
                                </div>
                                <?php
                                HtmlHelper::inputText("priceTo", Request::get('priceTo'));
                                ?>
                            </div>
                        </div>
                        <div class="max-len">
                            <h4>
                                Change,%
                            </h4>
                            <div>
                                <div>
                                    From:
                                </div>
                                <?php
                                HtmlHelper::inputText("changeFrom", Request::get('changeFrom'));
                                ?>
                            </div>
                            <div>
                                <div>
                                    To:
                                </div>
                                <?php
                                HtmlHelper::inputText("changeTo", Request::get('changeTo'));
                                ?>
                            </div>
                        </div>
                        <div>
                            <div> Changes per:</div>
                            <?php
                            $timeType = ['1 hour','1 day','7 day','1 month'];
                            HtmlHelper::select("changes_per",$timeType ,Request::get('changes_per'));
                            ?>
                        </div>
                        <div>
                            <div> Terms:</div>
                            <?php
                            HtmlHelper::select("limit", $pagination->limits, $pagination->getLimit());
                            ?>
                        </div>
                    </div>
                    <input type="submit" value="Find">
                </form>
            </div>
            <div class="assets wide-content">
                
                <table>
                    
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Assets</th>
                            <th>Last price</th>
                            <th>Change</th>
                            <th>Chart</th>
                            <th>Trade</th>
                        </tr>
                    </thead>
                    <tbody class="assets-content">


                        <?php

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
                            ]]);
                        $params = [];
                        if ($ticker) {
                            $params['crypto_id'] = $ticker;
                            $sql->where('prices.crypto_id', '=', "$ticker");
                        }
                        if (Request::get('name') != null) {
                            $params['name'] = Request::get('name');
                            $sql->where('name', 'LIKE', "%" . Request::get('name') . "%");
                        }
                        if (Request::get('priceFrom') != null) {
                            $params['priceFrom'] = Request::get('priceFrom');
                            $sql->where('price', '>=',Request::get('priceFrom'));
                        }
                        if (Request::get('priceTo') != null) {
                            $params['priceTo'] = Request::get('priceTo');
                            $sql->where('price', '<=',Request::get('priceTo'));
                        }
                        
                        $sql->orderBy("ABS(TIMESTAMPDIFF(SECOND, max_datetime.max_datetime, NOW()))");
                        $rows = $mysqli->query($sql->sql());
                        $count = count($rows);
                        $sql->offset($pagination->getFirst())->limit($pagination->getLimit());
                        $sql = $sql->sql();
                        $rows = $mysqli->query($sql);
                        $pagination->setRowsCount($count);
                        $pagination->setPage(Request::get('page', 1));
                        echo '<div class="pagination">';
                        echo $pagination->show();
                        echo '</div>';
                        if ($rows === false) {
                            echo 'Error select';
                        } else {
                            $pagination->setParams($params);
                            $num = $pagination->getFirst();
                            foreach ($rows as $row) {
                                $change=0;
                                $sign = '';
                                $class = '';
                                if (Request::get('changes_per') !== null) {
                                    $timeBefore= Request::get('changes_per');  
                                    $params['changes_per'] = $timeBefore;
                                    $crypto_id = $mysqli->find('cryptocurrencies')->select('cryptocurrencies.crypto_id')
                                    ->where('ticker','=',$row['ticker']);
                                    $crypto_id = $mysqli->query($crypto_id->sql())[0]['crypto_id'];
                                    $sql=$mysqli->find('prices')->select('price')->where('prices.crypto_id','=',$crypto_id)
                                    ->orderBy("ABS(TIMESTAMPDIFF(SECOND, 
                                    CONCAT(prices.date, ' ',prices.time), 
                                    DATE_SUB(NOW(), INTERVAL $timeBefore)))")->limit(1);
                                    $priceBefore = $mysqli->query($sql->sql())[0]['price'];
                                    $price = $row['price'];
                                    if($priceBefore<0.001){
                                        if($price<0.001){
                                            $priceBefore= $price=1;
                                        }
                                        else{

                                            $priceBefore= 0.001;
                                        }
                                    }
                                    $change=(100*$price)/$priceBefore-100 ;
                                    if ($change>100) {
                                        $sign = '>';
                                        $change =100;
                                    }
                                    if ($change<-100) {
                                        $sign= '<';
                                        $change =-100;
                                    }
                                    if ($change>0) {
                                        $class = 'appreciation';
                                    }
                                    if ($change<0) {
                                        $class = 'depreciation';
                                        $change =-$change;
                                    }
                                    $change = number_format(($change) ,2);
                                    
                                }
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
                                echo '<span ><img src="/img/graphikEx.svg" alt="chart"></span>';
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
                <?php
                echo '<div class="pagination">';
                echo $pagination->show();
                echo '</div>';
                ?>
            </div>
        </div>
    </div>
</body>

</html>