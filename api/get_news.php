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
// $key = mysql_real_escape_string($_GET['k']);

header("Content-type: application/json");  //统一输出json格式
// echo json_encode(getvideoinfo_withplayURL($album_id, $pageno, $pagesize));
//echo json_encode(tagvideoindex( 1576 , 200 , 0, 0, 1 , 180 , 0 ,20));

function getData(){
	// 查询视频
	$now = time();
	$sql = "select * from (
					select * from (select * from view_mryb_video_list where from_unixtime(starttime) > DATE_SUB(now(),INTERVAL 5 DAY)) as a
					union
					select * from (select * from view_xxjq_video_list where from_unixtime(starttime) > DATE_SUB(now(),INTERVAL 5 DAY)) as b
					union
					select * from (select * from view_yxdt_video_list where from_unixtime(starttime) > DATE_SUB(now(),INTERVAL 5 DAY)) as c
					) as f order by starttime desc ";
	$result = mysql_query($sql);
	$array = array();
	$i = 0;
	while ($arr = mysql_fetch_array($result)) {
		$array[$i]["title"] = $arr['title'];
	  $array[$i]["image"] = dealimage($arr['image']);
	  $array[$i]["vdesc"] = $arr['indexcontent'];
	  $array[$i]["len"] = timeformat($arr['len']);
	  $array[$i]["pv"] = $arr['pv'];
	  $array[$i]["id"] = $arr['target_id'];
		$array[$i]["tag_id"] = $arr['tag_id'];
	  $array[$i]["date"] = date('Y-m-d', $arr['starttime']);
	  $i++;
	}
	return json_encode($array);
}

if(file_exists("cache/get_news.cache.php"))
{
	$start = stat("cache/get_news.cache.php");
	$ex_time = time()-$start['mtime'];
	if($ex_time>1800)
	{
		$data = getData();
		file_put_contents("cache/get_news.cache.php",$data);
		echo $data;
	} else {
		echo file_get_contents("cache/get_news.cache.php");
	}
} else {
	$data = getData();
	file_put_contents("cache/get_news.cache.php",$data);
	echo $data;
}
?>
