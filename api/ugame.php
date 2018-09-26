<?
require("./config/connect_148.php");
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

header("Content-type: application/json");  //统一输出json格式
$action = $_GET['a'];

switch($action) {
	case "ad" :
	echo ('234');
	break;
	case "hot" :
		$webgame = mgameindex(1,12,12,0,0);
		// var_dump($webgame);
		foreach($webgame as $k=>$d)
		{
			$xid = $d['id'];
			$webgame[$k]['id'] = $xid;
			$webgame[$k]['title'] = $d['title'];
			$webgame[$k]['down'] = $data2[$xid]*7;
			$webgame[$k]['cover'] =  $d['cover'];
			$webgame[$k]['remark'] =  $d['remark'];
			$webgame[$k]['size'] =  $d['size'];
			$webgame[$k]['downloadurl'] = 'http://down.gamefy.cn/common/index.php?game='.getDownloadURL($d['url']).'&client=android&cf=appgamefy&ph=link119';
		}
		echo (json_encode($webgame));
		break;
	case "rec" :
		$dgame = [];
		$data = json_decode(file_get_contents('http://a.gamefy.cn/api.php'),true);
		$data2 = json_decode(file_get_contents('http://down.gamefy.cn/common/output.php?action=getDownload'),true);
		foreach($data as $k=>$d)
		{
			$dgame[$k]['id'] = $d['xid'];
			$dgame[$k]['title'] = $d['title'];
			$dgame[$k]['cover'] =  $d['cover'];
			$dgame[$k]['remark'] =  $d['remark'];
			$dgame[$k]['size'] =  $d['size'];
			$dgame[$k]['downloadurl'] = 'http://down.gamefy.cn/common/index.php?game='.getDownloadURL($d['url']).'&client=android&cf=appgamefy&ph=link119';
		}
		echo (json_encode($dgame));
		break;
}


function getDownloadURL ($url) {
	$url = str_replace(array('http://g.gamefy.cn/','http://wan.gamefy.cn/','/'),array('','',''), $url);
	$url = str_replace(array('http:down.gamefy.cncommonindex.php?game=','&client=android&cf=appgamefy&ph=link119'),array('',''), $url);
	return $url;
}

// function adlist($class_id, $pagesize = 0) {
//     $id = intval($class_id);
//     $sql = "select * from ad_list where class_id='" . $id . "' and valid=1 order by displayorder desc,id";
//     if ($pagesize > 0)
//         $sql.=" limit 0,$pagesize";
//     $result = mysql_query($sql);
//     $data = array();
//     $i = 0;
//     while ($row = mysql_fetch_array($result)) {
//         $data[$i] = $row;
//         $i++;
//     }
//     return $data;
// }

?>
