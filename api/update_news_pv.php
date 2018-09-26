<?
require("./config/conn.php");
require("./config/config.php");
?>
<?
// 跨域请求保护。保证跨域请求的时候不会出问题
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE');
header('Access-Control-Allow-Headers:accept,authorization');

// 只有在真实请求的时候才需要返回数据。
// OPTIONS请求的时候不能做任何检查。
if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {exit();}

$id=$_GET['id'];

function updatePV($id){
    $sql = "update cmsnew.news_subject set pv = pv +1 where id = $id";
    $result=  mysql_query($sql);
  	return $result;
}

function getDetail($id){
	return (getVideo($id));
}

function getCurrentTag($id){
 	$result = getTags($id, 1);
	$keys = "";
	for($i=0;$i<count($result);$i++){
		$keys = $keys.$result[$i]["tag"].",";
	}
	return $keys;
}

if (updatePV($id)) {
	$result = getDetail($id);
	$tages = getCurrentTag($id);
	$result["tags"] = $tages;
	echo (json_encode($result));
} else {
	echo 'false';
}
?>
