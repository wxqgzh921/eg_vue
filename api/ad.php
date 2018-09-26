<?
require("./config/connect_148.php");
require("./config/config.php");

// 跨域请求保护。保证跨域请求的时候不会出问题
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE');
header('Access-Control-Allow-Headers:accept,authorization');

// 只有在真实请求的时候才需要返回数据。
// OPTIONS请求的时候不能做任何检查。
if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {exit();}

$pageno = intval($_GET['page']);
$pagesize = 10;
if(empty($pageno)) $pageno = 0;
$k = isset($_GET['k']) ? $_GET['k'] : "";
$k = mysql_real_escape_string($k);
// $key = mysql_real_escape_string($_GET['k']);

header("Content-type: application/json");  //统一输出json格式
// echo json_encode(getvideoinfo_withplayURL($album_id, $pageno, $pagesize));
//echo json_encode(tagvideoindex( 1576 , 200 , 0, 0, 1 , 180 , 0 ,20));

// 查询广告
$sql = "select * from ad_list where class_id='321' and valid=1 order by displayorder desc,id  limit 0,8";
$result = mysql_query($sql);
$array = array();
$i = 0;
while ($arr = mysql_fetch_array($result)) {
  $array[$i]["url"] = $arr['url'];
  $array[$i]["image"] = dealimage($arr['image']);
  $i++;
}
echo json_encode($array);
?>
