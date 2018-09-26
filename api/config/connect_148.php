<?
// 因为需要访问游戏站相关内容，所有这里单独使用游戏站的数据库连接
error_reporting(0);
$connection=mysql_connect("192.168.16.140","gamefycms","gamefycms_#^behi");
// $connection=mysql_connect("10.0.0.87","test","test");
$conn=mysql_select_db("newcms",$connection);
mysql_query("SET NAMES utf8");

function mgameindex($webgame,$pagesize,$titlelen=0,$pageposi=0,$indexcontent=0)//获取无分页手游列表?
{
	$sql="select a.id,a.title,a.star,a.date,a.cover,a.erweima,a.weburl,a.size,a.remark,c.name from mgame_list a left join mgame_typelist b on a.id = b.mg_id left join mgame_type c on c.id = b.type_id";
	switch ($webgame){ //webgame=0是单机，webgame=1是网游，其他为查询所有
		case 0:
			$sql.=" where webgame=0 and is_del=0";
			break;
		case 1:
			$sql.=" where webgame=1 and is_del=0";
			break;
		default:
			break;
	}

	$sql.=" group by a.id order by date desc";
	$sql.=" limit $pageposi,$pagesize";
	$result=mysql_query($sql);
	$data=array();
	$i=0;
	while ($arr=mysql_fetch_array($result))
	{
		  $data[$i]['id']=$arr['id'];
		  $title=$arr['title'];
		  $data[$i]['title']=$title;
		  $data[$i]['styletitle']=titlestyle($title,$arr['color'],$arr['strong'],$titlelen);
		  $data[$i]['star']=$arr['star'];
		  $data[$i]['dateline']=$arr['date'];
		  $data[$i]['cover']=$arr['cover'];
		  $data[$i]['erweima']=$arr['erweima'];
		  $data[$i]['url']=$arr['weburl'];
		  $data[$i]['type']=$arr['name'];
			$data[$i]['size']=$arr['size'];
			$data[$i]['remark']=$arr['remark'];
		  if($indexcontent>0){
			  $content=str_replace("\n","<br>",$arr['remark']);
			  $data[$i]['indexcontent']=strip_tags(substrcn($content,$indexcontent,"..."));
		  }
		  else
		  {
			  $data[$i]['indexcontent']=str_replace("\n","<br>",$arr['remark']);
		  }
		  $i++;
	}
	return $data;
}
?>
