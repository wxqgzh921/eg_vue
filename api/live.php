<?
$url = "/live/0118_gamefy_app/playlist.m3u8";
$time = time();
$key = md5("86B29386-3A8F-8421-4310-693B89DCDB71".$url.strval($time));
$url = $url."?t=".strval($time)."&k=".$key;
$url = "http://mobile-hls.gamefy.cn".$url;
var_dump ($url);

header("Location: ".$url);
?>
