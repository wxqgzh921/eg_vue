<?
function getvideourl($video_type,$video_vkey){
  //type=1为优酷视频 type=6为腾讯视频 其它根据需求调整
  if($video_type==1){
      // $url =  '<iframe height=100% width=100% src=\'http://static.youku.com/v/swf/loader.swf?VideoIDS='.$video_vkey.'&isAutoPlay=true&isShowRelatedVideo=false&embedid===&showAd=0\' frameborder=0 ></iframe>';
      $url =  '<embed width="100%" height="100%" src="http://static.youku.com/v/swf/loader.swf?VideoIDS='.$video_vkey.'&amp;isAutoPlay=true&amp;isShowRelatedVideo=false&amp;embedid===&amp;showAd=0" type="application/x-shockwave-flash" fullscreen="yes">';
  }else if($video_type==6){
     $url =  '<iframe frameborder="0" width="640" height="498" src="http://v.qq.com/iframe/player.html?vid='.$video_vkey.'&tiny=0&auto=0" allowfullscreen></iframe>';
  }
  return $url;
}

function getvideoinfo_withplayURL($album_id,$pageno=1,$pagesize=8) {
    $album_id=  intval($album_id);
    $sql = "select * from video_album where id=".$album_id;
    $result = mysql_query($sql);
    $arr=  mysql_fetch_array($result);
    $data = array();
    $data["albumname"]=$arr["albumname"];
    $data["note"]=$arr["note"];
    $data["image"]=  dealimage($arr["image"]);
    $data["updatetime"]=date('Y-m-d H:i:s',$arr["updatetime"]);
    $vid = $arr['last_vid'];
    $sql1 = "select title from video_list where id=" . $vid;
    $result1 = mysql_query($sql1);
    $data['last_video'] = mysql_result($result1, 0, 'title');
    $data['last_vid'] = $vid;
    $pageposi = ($pageno - 1) * $pagesize;
    $sql="select a.title,a.target_id,a.image,a.class_id,b.addtime,c.vtype,c.vkey from base_publish a,video_albumvideo b ,video_list c where a.target_id=b.vid and b.vid=c.id and a.target_type=1 and b.album_id=".$album_id." and a.publishtype=1 and b.valid=1 and a.valid=1 and c.valid=1";
    $sql.=" order by b.displayorder desc,b.addtime desc limit $pageposi,$pagesize";
    $result=  mysql_query($sql);
    $vlist=array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $vlist[$i]['id'] = $arr['target_id'];
        $vlist[$i]['title'] = $arr['title'];
        $vlist[$i]['url']= getvideourl($arr['vtype'],$arr['vkey']);
        $vlist[$i]['class_id'] = $arr['class_id'];
        $vlist[$i]['image'] = dealimage($arr["image"]);
        $vlist[$i]['datetime'] = date('Y-m-d H:i',$arr["addtime"]);
        $i++;
    }
    $data["vlist"]=$vlist;
    return $data;
}

function getnewinfo($id){
    $sql = "select b.title,b.starttime,b.image,b.class_id,n.content,b.indexcontent from base_publish as b,news_content as n WHERE valid=1 AND target_id = "."$id" ." and b.target_id = n.news_id";
    $result=  mysql_query($sql);
    $data =array();
    while ($arr = mysql_fetch_row($result)){
        $data['title']= $arr[0];
        $data['time'] = $arr[1];
        $data['image'] = dealimage($arr[2]);
        $data['class_id'] = $arr[3];
        $data['url']= "http://gamefy.sitv.com.cn/nview.php?id=5316".$arr['target_id'];
        $data['content'] = $arr[4];
        $data['indexcontent'] = $arr[5];
    }
    return $data;
}

function getnewslist($class_id, $classidtype, $pagesize, $pageposi = 0, $pic = 2, $top = 1, $indexcontent = 0, $except = '') {//获取无分页新闻列�?

    $now=time();
    $sql = "select b.title,b.starttime,b.target_id,b.image ,b.indexcontent,n.content from base_publish as b LEFT JOIN news_content as n on b.target_id=n.news_id where valid=1 and starttime<".$now." and ";
    switch ($classidtype) {
        case 1:
            $sql.=" class_id='$class_id'";
            break;
        case 2:
            $sql.=" class_id in (select id from base_class where upid='$class_id')";
            break;
        case 3:
            if (is_array($class_id) && !empty($class_id)) {
                $class_id = implode(',', $class_id);
            } else {
                $class_id = intval($class_id);
            }
            $sql.=" class_id in ($class_id)";
            break;
        case 4:
            $sql.=" class_id in (select id from base_class where type='$class_id')";
            break;
        default:
            $sql = " class_id='$class_id'";
    }
    if ($pic < 2)
        $sql.=" and pic='$pic'";
    if ($except != '')
        $sql.=" and id not in ($except)";
    if ($top == 5)//只取置顶级别的发布信息
        $sql.=" and toprank=5";
    if ($top > 1 && $top < 5)//取小于该发布级别的信息，用于和$top=5配合使用
        $sql.=" and toprank<" . $top;
    $sql.=" order by";
    if ($top > 0)//当$top=-1时，不根据发布级别排序，仅按照时间
        $sql.=" toprank desc,";
    $sql.=" starttime desc";
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['id'] = $arr['target_id'];
        $data[$i]['image'] = dealimage($arr['image']);
        $data[$i]['indexcontent'] = $arr['indexcontent'];
        $data[$i]['content'] = $arr[5];
        $data[$i]['url'] = "http://gamefy.sitv.com.cn/nview.php?id=".$arr['target_id'];
        $i++;
    }
    return $data;

}
?>
