<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prices</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="../components/css/morePrices.css">
    <script src="../components/js/morePrices.js"></script>
    <style>
        .pagination a {
            display: inline-block;
            border: 1px solid #00adff;
            padding: 9px;
            margin: 8px;
            background-color: #066952;
            color: #001359;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 23px;
        }

        .pagination a.active {
            background-color: burlywood;
            color: crimson;
        }
    </style>
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
                        $sql = $mysqli->find("cryptocurrencies")->select('COUNT(crypto_id)');
                        $count = $sql->rows()[0]['COUNT(crypto_id)'];
                        $pagination->setRowsCount($count);
                        $pagination->setPage(Request::get('page', 1));
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
                        $sql->groupBy("cryptocurrencies.crypto_id")
                            ->orderBy("ABS(TIMESTAMPDIFF(SECOND, 
                            CONCAT(MAX(prices.date), ' ',MAX(prices.time)), 
                            NOW()))")
                            ->offset($pagination->getFirst())->limit($pagination->getLimit());
                        $sql = $sql->sql();
                        $rows = $mysqli->query($sql);
                        if ($rows === false) {
                            echo 'Error select';
                        } else {
                            $pagination->setParams($params);
                            echo '<div class="pagination">';
                            echo $pagination->show();
                            echo '</div>';
                            $num = $pagination->getFirst();
                            foreach ($rows as $row) {
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
                                echo '<div><span class="appreciation">2.43%</span></div>';
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
            </div>
        </div>
    </div>
</body>

</html>