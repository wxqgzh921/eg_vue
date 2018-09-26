<?
require("./config/conn.php");
require("./config/config.php");

header("Content-type: text/plant");  //统一输出json格式

// 查询视频
$now = time();
$sql = "SELECT a.id, a.title, a.image, a.indexcontent, a.len, a.pv, a.starttime FROM `view_base_publish_video_list` a ORDER BY `a`.`starttime` DESC limit 0,3000";
$result = mysql_query($sql);

$i = 0;
while ($arr = mysql_fetch_array($result)) {
  $array[$i]["title"] = $arr['title'];
  $array[$i]["image"] = dealimage($arr['image']);
  $array[$i]["vdesc"] = $arr['indexcontent'];
  $array[$i]["len"] = timeformat($arr['len']);
  $array[$i]["pv"] = $arr['pv'];
  $array[$i]["id"] = $arr['id'];
  $array[$i]["date"] = date('Y-m-d', $arr['starttime']);
  $i++;
	echo ("http://tv.gamefy.cn/#/video/".$arr['id']."\r\n");
}

?>
