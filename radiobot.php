<?php
/// FILL This 2 String :
$API = "123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11";   // Telegram Bot Token 
$radiobotfileurl = "https://hamed.irtv3.online/radiobot.php"; // Address Of This File On Your Domain With HttpS ! 
///// Run Your Link After Copy File And After That Remove Line Below .
file_get_contents("https://api.telegram.org/bot" . $API . "/setWebhook?url=" . $radiobotfileurl);
//////////////////////////////////////////////////////////
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$chatID = $update["message"]["chat"]["id"];
$chatfirst_name = $update["message"]["chat"]["first_name"];
$chatlast_name = $update["message"]["chat"]["last_name"];
$uusername = $update["message"]["chat"]["username"];
$chattext = $update["message"]["text"];
$newline = urlencode("\n");
file_get_contents("https://api.telegram.org/bot" . $API . "/sendChatAction?chat_id=" . $chatID . "&action=typing");
//////////////
if (strpos($chattext, "/start") !== false) {
    file_get_contents("https://api.telegram.org/bot" . $API . "/sendmessage?chat_id=" . $chatID . "&text=لطفا لینک پادکست یا آهنگ یا ریمیکس را ارسال کنین ");
} else if (strpos($chattext, "play.radiojavan.com") !== false || strpos($chattext, "rj.app/") !== false) {
    if (strpos($chattext, "rj.app/") !== false) {
        $chattext = str_replace("rj.app", "play.radiojavan.com", $chattext);
    }
    $ch = curl_init();
    $url = $chattext;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
    curl_close($ch);
    $start = preg_quote('<script id="__NEXT_DATA__" type="application/json">', '/');
    $end = preg_quote('</script>', '/');
    preg_match("/$start(.*?)$end/", $response, $matches);
    $json = json_encode(json_decode($matches[1]), JSON_PRETTY_PRINT);
    $array = json_decode($json, true);
    /////////////////////////////////////////////////////////////////
    if (strpos($url, "radiojavan.com/song") !== false || strpos($url, "radiojavan.com/m") !== false) {
        $playlistitems = $array["props"]["pageProps"]["media"];
        file_get_contents("https://api.telegram.org/bot" . $API . "/sendAudio?chat_id=" . $chatID . "&audio=" . $playlistitems["link"] . "&caption=" . $playlistitems["link"]);
    }
    ///
    if (strpos($url, "radiojavan.com/playlist") !== false || strpos($url, "radiojavan.com/pm") !== false) {
        $playlistitems = $array["props"]["pageProps"]["playlist"]["items"];
        foreach ($playlistitems as $item) {
            file_get_contents("https://api.telegram.org/bot" . $API . "/sendAudio?chat_id=" . $chatID . "&audio=" . $item["link"] . "&caption=" . $item["link"]);
        }
    }
    ///
    if (strpos($url, "radiojavan.com/podcast") !== false || strpos($url, "radiojavan.com/p/") !== false) {
        $playlistitems = $array["props"]["pageProps"]["media"];
        file_get_contents("https://api.telegram.org/bot" . $API . "/sendmessage?chat_id=" . $chatID . "&text=" . $playlistitems["link"]);
        file_get_contents("https://api.telegram.org/bot" . $API . "/sendmessage?chat_id=" . $chatID . "&text=به دلیل بالا بودن حجم فایل و محدودیت تلگرام از ارسال پادکست به صورت فایل معذوریم");
    }
    ////// RJ.APP Links :
} else {
    file_get_contents("https://api.telegram.org/bot" . $API . "/sendmessage?chat_id=" . $chatID . "&text=لطفا لینک مورد نظر را ارسال کنین");
}
