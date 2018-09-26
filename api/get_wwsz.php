<?
require("./config/conn.php");
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
header("Content-type: application/json");  //统一输出json格式
// echo json_encode(getvideoinfo_withplayURL($album_id, $pageno, $pagesize));
//echo json_encode(tagvideoindex( 1576 , 200 , 0, 0, 1 , 180 , 0 ,20));


// 查询视频
$now = time();
$pageno = $pageno*$pagesize;
if ($k != "")
	$sql = "SELECT a.id, a.title, b.image, a.vdesc, a.vkey, a.len, a.oripv, a.pv, a.dateline FROM cmsnew.video_list a, cmsnew.base_publish b where a.id = b.target_id and a.id in (select target_id from cmsnew.base_taglist where tag_id = 15023) and a.isdel = 0 and (b.related_class_id is null or b.related_class_id = 1)  and a.valid = 1 and a.title like '%$k%' order by a.dateline desc limit $pageno,$pagesize";
else
	$sql = "SELECT a.id, a.title, b.image, a.vdesc, a.vkey, a.len, a.oripv, a.pv, a.dateline FROM cmsnew.video_list a, cmsnew.base_publish b where a.id = b.target_id and a.id in (select target_id from cmsnew.base_taglist where tag_id = 15023) and a.isdel = 0 and (b.related_class_id is null or b.related_class_id = 1)  and a.valid = 1 order by a.dateline desc limit $pageno,$pagesize";
$result = mysql_query($sql);
$array = array();
$i = 0;
while ($arr = mysql_fetch_array($result)) {
  $array[$i]["title"] = $arr['title'];
  $array[$i]["image"] = dealimage($arr['image']);
  $array[$i]["vdesc"] = $arr['vdesc'];
  $array[$i]["vkey"] = $arr['vkey'];
  $array[$i]["len"] = timeformat($arr['len']);
  $array[$i]["pv"] = (ceil($arr['oripv']/10)+$arr["pv"]*7);
  $array[$i]["id"] = $arr['id'];
  $array[$i]["date"] = date('Y-m-d', $arr['dateline']);
  $i++;
}
echo json_encode($array);

//
// // 查询视频
// $sql = "select * from video_list where id in ($tagetidStr) order by id desc";
// $result = mysql_query($sql);
// echo ($sql);
// while ($arr = mysql_fetch_array($result)) {
//    var_dump($arr);
// }


?>
