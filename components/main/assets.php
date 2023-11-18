<?php
    include_once("include/db.php");
?>

<div>
    <div class="assets">
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
            ->orderBy("ABS(TIMESTAMPDIFF(SECOND, 
            CONCAT(prices.date, ' ',prices.time), 
            NOW()))")
            ->limit(7)
            ->sql();
            echo "$sql<br>";
            exit();
            /*
            for ($i=0;$i<7;$i++){
                echo "<div>";
                    <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M23.9783 11.996C23.9783 18.5906 18.6326 23.9363 12.038 23.9363C5.44357 23.9363 0.0976562 18.5906 0.0976562 11.996C0.0976562 5.40146 5.44357 0.0556641 12.038 0.0556641C18.6326 0.0556641 23.9783 5.40146 23.9783 11.996Z" fill="#1BA27A" />
                            <path d="M17.6422 6.07666H6.33594V8.80621H10.6243V12.8182H13.3538V8.80621H17.6422V6.07666Z" fill="white" />
                            <path d="M12.0144 13.2456C8.46695 13.2456 5.59089 12.6842 5.59089 11.9915C5.59089 11.299 8.46683 10.7374 12.0144 10.7374C15.5619 10.7374 18.4378 11.299 18.4378 11.9915C18.4378 12.6842 15.5619 13.2456 12.0144 13.2456ZM19.227 12.2006C19.227 11.3074 15.9978 10.5835 12.0144 10.5835C8.03113 10.5835 4.80176 11.3074 4.80176 12.2006C4.80176 12.9871 7.30576 13.6424 10.6238 13.7876V19.5468H13.3532V13.7898C16.6968 13.6492 19.227 12.9911 19.227 12.2006Z" fill="white" />
                        </svg></div>
                    <div><span>USDT</span><span>Tether</span></div>
                    <div><span><span>USD</span><span> 0.9999</span></span><span class="appreciation">2.43%</span></div>
                </div> 
            }*/
        ?>
        <div>
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M23.9783 11.996C23.9783 18.5906 18.6326 23.9363 12.038 23.9363C5.44357 23.9363 0.0976562 18.5906 0.0976562 11.996C0.0976562 5.40146 5.44357 0.0556641 12.038 0.0556641C18.6326 0.0556641 23.9783 5.40146 23.9783 11.996Z" fill="#1BA27A" />
                    <path d="M17.6422 6.07666H6.33594V8.80621H10.6243V12.8182H13.3538V8.80621H17.6422V6.07666Z" fill="white" />
                    <path d="M12.0144 13.2456C8.46695 13.2456 5.59089 12.6842 5.59089 11.9915C5.59089 11.299 8.46683 10.7374 12.0144 10.7374C15.5619 10.7374 18.4378 11.299 18.4378 11.9915C18.4378 12.6842 15.5619 13.2456 12.0144 13.2456ZM19.227 12.2006C19.227 11.3074 15.9978 10.5835 12.0144 10.5835C8.03113 10.5835 4.80176 11.3074 4.80176 12.2006C4.80176 12.9871 7.30576 13.6424 10.6238 13.7876V19.5468H13.3532V13.7898C16.6968 13.6492 19.227 12.9911 19.227 12.2006Z" fill="white" />
                </svg></div>
            <div><span>USDT</span><span>Tether</span></div>
            <div><span><span>USD</span><span> 0.9999</span></span><span class="appreciation">2.43%</span></div>
        </div>
        <div>
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M23.9783 11.996C23.9783 18.5906 18.6326 23.9363 12.038 23.9363C5.44357 23.9363 0.0976562 18.5906 0.0976562 11.996C0.0976562 5.40146 5.44357 0.0556641 12.038 0.0556641C18.6326 0.0556641 23.9783 5.40146 23.9783 11.996Z" fill="#1BA27A" />
                    <path d="M17.6422 6.07666H6.33594V8.80621H10.6243V12.8182H13.3538V8.80621H17.6422V6.07666Z" fill="white" />
                    <path d="M12.0144 13.2456C8.46695 13.2456 5.59089 12.6842 5.59089 11.9915C5.59089 11.299 8.46683 10.7374 12.0144 10.7374C15.5619 10.7374 18.4378 11.299 18.4378 11.9915C18.4378 12.6842 15.5619 13.2456 12.0144 13.2456ZM19.227 12.2006C19.227 11.3074 15.9978 10.5835 12.0144 10.5835C8.03113 10.5835 4.80176 11.3074 4.80176 12.2006C4.80176 12.9871 7.30576 13.6424 10.6238 13.7876V19.5468H13.3532V13.7898C16.6968 13.6492 19.227 12.9911 19.227 12.2006Z" fill="white" />
                </svg></div>
            <div><span>USDT</span><span>Tether</span></div>
            <div><span>USD 0.9999</span><span class="depreciation">2.43%</span></div>
        </div>
        <div>
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M23.9783 11.996C23.9783 18.5906 18.6326 23.9363 12.038 23.9363C5.44357 23.9363 0.0976562 18.5906 0.0976562 11.996C0.0976562 5.40146 5.44357 0.0556641 12.038 0.0556641C18.6326 0.0556641 23.9783 5.40146 23.9783 11.996Z" fill="#1BA27A" />
                    <path d="M17.6422 6.07666H6.33594V8.80621H10.6243V12.8182H13.3538V8.80621H17.6422V6.07666Z" fill="white" />
                    <path d="M12.0144 13.2456C8.46695 13.2456 5.59089 12.6842 5.59089 11.9915C5.59089 11.299 8.46683 10.7374 12.0144 10.7374C15.5619 10.7374 18.4378 11.299 18.4378 11.9915C18.4378 12.6842 15.5619 13.2456 12.0144 13.2456ZM19.227 12.2006C19.227 11.3074 15.9978 10.5835 12.0144 10.5835C8.03113 10.5835 4.80176 11.3074 4.80176 12.2006C4.80176 12.9871 7.30576 13.6424 10.6238 13.7876V19.5468H13.3532V13.7898C16.6968 13.6492 19.227 12.9911 19.227 12.2006Z" fill="white" />
                </svg></div>
            <div><span>USDT</span><span>Tether</span></div>
            <div><span>USD 0.9999</span><span class="depreciation">2.43%</span></div>
        </div>
        <div>
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M23.9783 11.996C23.9783 18.5906 18.6326 23.9363 12.038 23.9363C5.44357 23.9363 0.0976562 18.5906 0.0976562 11.996C0.0976562 5.40146 5.44357 0.0556641 12.038 0.0556641C18.6326 0.0556641 23.9783 5.40146 23.9783 11.996Z" fill="#1BA27A" />
                    <path d="M17.6422 6.07666H6.33594V8.80621H10.6243V12.8182H13.3538V8.80621H17.6422V6.07666Z" fill="white" />
                    <path d="M12.0144 13.2456C8.46695 13.2456 5.59089 12.6842 5.59089 11.9915C5.59089 11.299 8.46683 10.7374 12.0144 10.7374C15.5619 10.7374 18.4378 11.299 18.4378 11.9915C18.4378 12.6842 15.5619 13.2456 12.0144 13.2456ZM19.227 12.2006C19.227 11.3074 15.9978 10.5835 12.0144 10.5835C8.03113 10.5835 4.80176 11.3074 4.80176 12.2006C4.80176 12.9871 7.30576 13.6424 10.6238 13.7876V19.5468H13.3532V13.7898C16.6968 13.6492 19.227 12.9911 19.227 12.2006Z" fill="white" />
                </svg></div>
            <div><span>USDT</span><span>Tether</span></div>
            <div><span>USD 0.9999</span><span class="depreciation">2.43%</span></div>
        </div>
        <div>
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M23.9783 11.996C23.9783 18.5906 18.6326 23.9363 12.038 23.9363C5.44357 23.9363 0.0976562 18.5906 0.0976562 11.996C0.0976562 5.40146 5.44357 0.0556641 12.038 0.0556641C18.6326 0.0556641 23.9783 5.40146 23.9783 11.996Z" fill="#1BA27A" />
                    <path d="M17.6422 6.07666H6.33594V8.80621H10.6243V12.8182H13.3538V8.80621H17.6422V6.07666Z" fill="white" />
                    <path d="M12.0144 13.2456C8.46695 13.2456 5.59089 12.6842 5.59089 11.9915C5.59089 11.299 8.46683 10.7374 12.0144 10.7374C15.5619 10.7374 18.4378 11.299 18.4378 11.9915C18.4378 12.6842 15.5619 13.2456 12.0144 13.2456ZM19.227 12.2006C19.227 11.3074 15.9978 10.5835 12.0144 10.5835C8.03113 10.5835 4.80176 11.3074 4.80176 12.2006C4.80176 12.9871 7.30576 13.6424 10.6238 13.7876V19.5468H13.3532V13.7898C16.6968 13.6492 19.227 12.9911 19.227 12.2006Z" fill="white" />
                </svg></div>
            <div><span>USDT</span><span>Tether</span></div>
            <div><span>USD 0.9999</span><span class="depreciation">2.43%</span></div>
        </div>
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