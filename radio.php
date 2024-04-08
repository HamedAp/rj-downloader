<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ERROR);
/// 
// RadioJavan Downloader By HamedAp
$ch = curl_init();
$url = $_GET["url"];
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$response = curl_exec($ch);
curl_close($ch);
///
$start = preg_quote('<script id="__NEXT_DATA__" type="application/json">', '/');
$end = preg_quote('</script>', '/');
preg_match("/$start(.*?)$end/", $response, $matches);
$json = json_encode(json_decode($matches[1]), JSON_PRETTY_PRINT);
$array = json_decode($json, true);
///
if (str_contains($url, "radiojavan.com/song")) {
  $playlistitems = $array["props"]["pageProps"]["media"];
  echo '<a href="' . $playlistitems["link"] . '">' . $playlistitems["song"] . '</a><br>';
}
///
if (str_contains($url, "radiojavan.com/playlist")) {
  $playlistitems = $array["props"]["pageProps"]["playlist"]["items"];
  foreach ($playlistitems as $item) {
    echo '<a href="' . $item["link"] . '">' . $item["song"] . '</a><br>';
  }
}
///
if (str_contains($url, "radiojavan.com/video")) {
  $playlistitems = $array["props"]["pageProps"]["media"];
  echo $playlistitems["song"] . "<br>";
  echo '<a href="' . $playlistitems["lq_link"] . '">Low</a><br>';
  echo '<a href="' . $playlistitems["hq_link"] . '">High</a><br>';
  echo '<a href="' . $playlistitems["hd_4k_link"] . '">4K</a><br>';
}
