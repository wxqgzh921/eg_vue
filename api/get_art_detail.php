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
$id = intval($_GET['id']);
if(empty($pageno)) $pageno = 0;
$id = mysql_real_escape_string($id);

header("Content-type: application/json");  //统一输出json格式
// echo json_encode(getvideoinfo_withplayURL($album_id, $pageno, $pagesize));
//echo json_encode(tagvideoindex( 1576 , 200 , 0, 0, 1 , 180 , 0 ,20));


$data=getNews($id,0);

$array = array();
if($data['valid']==1)
{
	$array["title"] = $data['subject'];
  $array["description"] = $data['description'];
  $array["message"] = $data['message'];
  $array["author"] = $data['author'];
	$array["username"] = $data['username'];
	$array["authorword"] = $data['authorword'];
	$array["newsfrom"] = $data['newsfrom'];
	$array["user_id"] = $data['user_id'];
	$array["catname"] = $data['catname'];
	$array["comment"] = $data['comment'];
	$array["keywords"] = $data['keywords'];
	$array["dateline"] = $data['dateline'];
}
echo json_encode($array);
?>
