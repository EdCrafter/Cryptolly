<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prices</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../components/css/morePrices.css">
    <script src="../../components/js/morePrices.js"></script>
</head>

<body>
    <?php
    include_once("../../include/db.php");
    include_once("../../include/pagination.php");
    include_once("../../include/html.php");
    include_once("../../include/request.php");
    include_once("../../include/image.php");
    ?>
    <div class="main_container">
        <div class="container">
            <header>
                <?php
                include("../home/header.php");
                ?>
            </header>

            <?php
            $pagination = new Pagination();
            $pagination->limits = [3, 10, 50];
            $pagination->setLimit(Request::get('limit', 3));
            include_once("../../include/createDB.php");
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
                                HtmlHelper::inputText("priceFrom", Request::get('priceFrom'), 'number');
                                ?>
                            </div>
                            <div>
                                <div>
                                    To:
                                </div>
                                <?php
                                HtmlHelper::inputText("priceTo", Request::get('priceTo'), 'number');
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
                                HtmlHelper::inputText("changeFrom", Request::get('changeFrom'), 'number');
                                ?>
                            </div>
                            <div>
                                <div>
                                    To:
                                </div>
                                <?php
                                HtmlHelper::inputText("changeTo", Request::get('changeTo'), 'number');
                                ?>
                            </div>
                        </div>
                        <div>
                            <div> Changes per:</div>
                            <?php
                            $timeType = ['1 hour', '1 day', '7 day', '1 month'];
                            HtmlHelper::select("changes_per", $timeType, Request::get('changes_per'));
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
                            ]], (
                                $mysqli->find('prices')
                                ->select(['crypto_id', "MAX(CONCAT(date, ' ', time)) as max_datetime"])
                                ->groupBy("crypto_id")->sql()
                            ))
                            ->join('prices p ', [
                                [
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
                            $sql->where('p.crypto_id', '=', "$ticker");
                        }
                        if (!empty(Request::get('name'))) {
                            $name = htmlentities(Request::get('name'));
                            $params['name'] = $name;
                            $sql->where('name', 'LIKE', "%" . $name . "%");
                        }
                        if (!empty(Request::get('priceFrom'))) {
                            $params['priceFrom'] = Request::get('priceFrom');
                            $sql->where('price', '>=', Request::get('priceFrom'));
                        }
                        if (!empty(Request::get('priceTo'))) {
                            $params['priceTo'] = Request::get('priceTo');
                            $sql->where('price', '<=', Request::get('priceTo'));
                        }
                        if (!empty(Request::get('changes_per'))) {
                            $params['changes_per'] = Request::get('changes_per');
                        }
                        if (!empty(Request::get('changeFrom'))) {
                            $params['changeFrom'] = Request::get('changeFrom');
                        }
                        if (!empty(Request::get('changeTo'))) {
                            $params['changeTo'] = Request::get('changeTo');
                        }
                        $sql->orderBy("ABS(TIMESTAMPDIFF(SECOND, max_datetime.max_datetime, NOW()))");
                        $rows = $mysqli->query($sql->sql());
                        $count = count($rows);
                        $pagination->setRowsCount($count);
                        $pagination->setPage(Request::get('page', 1));
                        $sql->offset($pagination->getFirst())->limit($pagination->getLimit());
                        $sql = $sql->sql();
                        $rows = $mysqli->query($sql);

                        $pagination->setParams($params);
                        echo '<div class="pagination">';
                        echo $pagination->show();
                        echo '</div>';
                        if ($rows === false) {
                            echo 'Error select';
                        } else {
                            $num = $pagination->getFirst();
                            foreach ($rows as $row) {
                                $change = 0;
                                $sign = '';
                                $class = null;
                                $visibility = '';
                                if (Request::get('changes_per') !== null) {
                                    $timeBefore = Request::get('changes_per');
                                } else {
                                    $timeBefore = '1 hour';
                                }
                                $crypto_id = $mysqli->find('cryptocurrencies')->select('cryptocurrencies.crypto_id')
                                    ->where('ticker', '=', $row['ticker']);
                                $crypto_id = $mysqli->query($crypto_id->sql())[0]['crypto_id'];
                                $sql = $mysqli->find('prices')->select('price')->where('prices.crypto_id', '=', $crypto_id)
                                    ->orderBy("ABS(TIMESTAMPDIFF(SECOND, 
                                CONCAT(prices.date, ' ',prices.time), 
                                DATE_SUB(NOW(), INTERVAL $timeBefore)))")->limit(12);

                                $arrCh = [];
                                $i = 0;
                                $price = $row['price'];
                                $sumCh = 0;
                                while ($i < 12) {
                                    $priceBefore = $mysqli->query($sql->sql())[$i]['price'];

                                    if ($priceBefore < 0.001) {
                                        if ($price < 0.001) {
                                            $priceBefore = $price = 1;
                                        } else {

                                            $priceBefore = 0.001;
                                        }
                                    }
                                    $change = (100 * $price) / $priceBefore - 100;

                                    $arrCh[] = $change;
                                    $sumCh += $change;
                                    $price = $priceBefore;
                                    $i++;
                                }

                                if ($arrCh[0] > 100) {
                                    $sign = '>';
                                    $arrCh[0] = 100;
                                }
                                if ($arrCh[0] < -100) {
                                    $sign = '<';
                                    $arrCh[0] = -100;
                                }
                                if (
                                    ($arrCh[0] < $pagination->getArrParams()['changeFrom'] ||
                                        $arrCh[0] > $pagination->getArrParams()['changeTo'])
                                    &&
                                    (!empty($pagination->getArrParams()['changeFrom']) ||
                                        !empty($pagination->getArrParams()['changeTo']))
                                ) {
                                    $visibility = 'none';
                                    $num--;
                                }
                                if ($arrCh[0] > 0) {
                                    $class = 'appreciation';
                                }
                                if ($arrCh[0] < 0) {
                                    $class = 'depreciation';
                                }
                                $change = number_format(($arrCh[0]), 2);

                                echo '<tr ' . (($visibility) ? ('style="display: ' . $visibility . ';"') : $visibility) . '>';
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
                                echo '<div><span class=' . $class . '>' . $sign . $change . '%</span></div>';
                                echo "</td>";
                                echo "<td class='chart'><div>";
                                $diagr = new Diagram();
                                $diagr->setWidth(180);
                                $diagr->setHeight(70);
                                $diagr->setMin(0);
                                $diagr->setBgColor(30, 33, 50);
                                $diagr->setAxisColor(255, 255, 255);
                                $diagr->setColors([
                                    [255, 0, 0],
                                    [0, 255, 0],
                                ]);
                                if (!$sumCh) {

                                    $arrCh = [0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 10];
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
                <?php
                echo '<div class="pagination">';
                echo $pagination->show();
                echo '</div>';
                ?>
            </div>
            <?php
            include("../home/footer.php");
            ?>
        </div>
    </div>
</body>

</html>