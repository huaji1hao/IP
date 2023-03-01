<?php
header("Content-type: image/JPEG");
use UAParser\Parser;
require_once 'vendor/autoload.php';
$im = imagecreatefromjpeg("ip.jpg"); 
$ip = $_SERVER["REMOTE_ADDR"];
$ua = $_SERVER['HTTP_USER_AGENT'];
$get = $_GET["s"];
$get = base64_decode(str_replace(" ","+",$get));
$weekarray = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Firday","Saturday"); 
//ua
$parser = Parser::create();
$result = $parser->parse($ua);
$os = $result->os->toString(); // Mac OS X
$browser = $result->device->family.'-'.$result->ua->family;// Safari 6.0.2 

//地址

$url = 'https://api.vore.top/api/IPdata?ip='.$ip;
$data = json_decode(curl_get($url), true);

$info1 = $data['ipdata']['info1'];
$info2 = $data['ipdata']['info2'];
$info3 = $data['ipdata']['info3'];
$info = $info1 . $info2 . $info3;

$url_t = 'https://fanyi.youdao.com/translate?&doctype=json&type=AUTO&i='.$info;
$data_json = curl_get($url_t);
$data_t = json_decode(curl_get($url_t), true);
$trans = substr_replace($data_t['translateResult'][0][0]['tgt'], "", -1);

//定义颜色

$black = ImageColorAllocate($im, 0,0,0);//定义黑色的值
$red = ImageColorAllocate($im, 255,0,0);//红色
$white = ImageColorAllocate($im, 255,255,255);
$font = 'Spyced.ttf';//加载字体
//输出
imagettftext($im, 50, 0, 10, 70, $white, $font,'We Are Watching You.'); 
imagettftext($im, 50, 0, 10, 154, $white, $font,'Mortal from '.$trans.' !');
imagettftext($im, 50, 0, 10, 238, $white, $font, 'Today is '.$weekarray[date("w")].','.date(' F d, Y.'));//当前时间添加到图片
imagettftext($im, 50, 0, 10, 322, $white, $font,'Your IP address is '.$ip);//ip和温度
imagettftext($im, 50, 0, 10, 406, $white, $font,'You are using an '.$os.' OS');
imagettftext($im, 50, 0, 10, 490, $white, $font,'Your browser is '.$browser);
imagettftext($im, 50, 0, 10, 574, $white, $font,'Worship Silicon, Sacrifice for Computation !'); 
ImageGif($im);
ImageDestroy($im);


function curl_get($url, array $params = array(), $timeout = 6){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}
?>