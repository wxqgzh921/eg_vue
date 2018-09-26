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
// $key = mysql_real_escape_string($_GET['k']);

header("Content-type: application/json");  //统一输出json格式
// echo json_encode(getvideoinfo_withplayURL($album_id, $pageno, $pagesize));
//echo json_encode(tagvideoindex( 1576 , 200 , 0, 0, 1 , 180 , 0 ,20));


// 查询视频
$now = time();
$pageno = $pageno*$pagesize;
if ($k != "")
	$sql = "select a.id, a.title, b.image, a.vdesc, a.vkey, a.len, a.pv, a.dateline from video_list a, base_publish b,base_taglist c,video_list d where a.id = b.target_id and c.tag_id='2' and c.target_type=1 and c.target_id=b.target_id and d.id=c.target_id and b.target_type=c.target_type and b.valid=1 and b.publishtype=1  and a.title like '%$k%' order by b.toprank desc,b.starttime desc limit $pageno,$pagesize";
else
	$sql = "select a.id, a.title, b.image, a.vdesc, a.vkey, a.len, a.pv, a.dateline from video_list a, base_publish b,base_taglist c,video_list d where a.id = b.target_id and c.tag_id='2' and c.target_type=1 and c.target_id=b.target_id and d.id=c.target_id and b.target_type=c.target_type and b.valid=1 and b.publishtype=1 order by b.toprank desc,b.starttime desc limit $pageno,$pagesize";
$result = mysql_query($sql);
$array = array();
$i = 0;
while ($arr = mysql_fetch_array($result)) {
  $array[$i]["title"] = $arr['title'];
  $array[$i]["image"] = dealimage($arr['image']);
  $array[$i]["vdesc"] = $arr['vdesc'];
  $array[$i]["vkey"] = $arr['vkey'];
  $array[$i]["len"] = timeformat($arr['len']);
  $array[$i]["pv"] = $arr['pv'];
  $array[$i]["id"] = $arr['id'];
  $array[$i]["date"] = date('Y-m-d', $arr['dateline']);
  $i++;
}
echo json_encode($array);

?>
