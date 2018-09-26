<?
function classlist($up_id, $type = '') {//获取栏目列表
    $up_id = intval($up_id);
    $sql = "select classname,id,note,image,url from base_class where upid='$up_id'";
    if ($type != '') {
        $sql.=" and type='" . $type . "'";
    }
    $sql.=" and valid=1 order by displayorder desc,id";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $data[$i]['name'] = $arr['classname'];
        $data[$i]['id'] = $arr['id'];
        $data[$i]['url'] = $arr['url'];
        $data[$i]['image'] = dealimage($arr['image']);
        $data[$i]["note"] = $arr["note"];
        $i++;
    }
    return $data;
}

function publishindex($class_id, $classidtype, $pagesize, $titlelen = 0, $pageposi = 0, $pic = 2, $top = 1, $indexcontent = 0, $except = '') {//获取无分页新闻列�?
    $now=time();
    $sql = "select *  from base_publish where valid=1 and starttime<".$now." and ";
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
		$title=$arr['shorttitle']==''?$title:$arr['shorttitle'];
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('Y-m-d', $arr['starttime']);
        $data[$i]['time'] = date('H:i', $arr['starttime']);
        $data[$i]['id'] = $arr['target_id'];
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['catname'] = catname($arr['class_id']);
        $data[$i]['image'] = dealimage($arr['image']);
        if ($arr["publishtype"] == 3) {
            $data[$i]["url"] = $arr["url"];
        } else {
            $data[$i]['url'] = urlformat($arr['urlformat'], $arr['target_id'], $arr['class_id']);
        }
        if ($indexcontent > 0) {
            $content = str_replace("\n", "<br>", $arr['indexcontent']);
            $data[$i]['indexcontent'] = strip_tags(substrcn($content, $indexcontent, "..."));
        }
        $i++;
    }
    return $data;
}

function publishlist($class_id, $classidtype, $pagesize, $pageno = 1, $pic = 0, $indexcontent = 0, $top = 1) {
    $pageposi = ($pageno - 1) * $pagesize;
    $now = time();
    $sql = "select *  from base_publish where valid=1 and starttime<unix_timestamp() and ";
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
    $sql.=" order by";
    if ($top > 0)//当$top=-1时，不根据发布级别排序，仅按照时间
        $sql.=" toprank desc,";
    $sql.=" starttime desc";
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $totalpage = Ceil($total / $pagesize);
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('Y-m-d', $arr['starttime']);
        $data[$i]['time'] = date('H:i', $arr['starttime']);
        $data[$i]['id'] = $arr['target_id'];
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['catname'] = catname($arr['class_id']);
        $data[$i]['image'] = dealimage($arr['image']);
        if ($arr["publishtype"] == 3) {
            $data[$i]["url"] = $arr["url"];
        } else {
            $data[$i]['url'] = urlformat($arr['urlformat'], $arr['target_id'], $arr['class_id']);
        }
        if ($indexcontent > 0) {
            $content = str_replace("\n", "<br>", $arr['indexcontent']);
            $data[$i]['indexcontent'] = strip_tags(substrcn($content, $indexcontent, "..."));
        }
        $i++;
    }
    $total = array();
    $total['data'] = $data;
    $total['totalpage'] = $totalpage;
    $total['pageno'] = $pageno;
    return $total;
}

// This function is only used for internal purpose to generate html file.
function videoindex_internal($class_id, $classidtype, $pagesize, $titlelen = 0, $pageposi = 0, $pic = 2, $top = 1, $indexcontent = 0, $except = '') {//获取无分页视频列表
    // mark yemol
    $sql = "select a.*,b.pv,b.oripv  from base_publish a,video_list b where a.target_id=b.id and a.valid=1 and b.valid=1 and a.target_type=1 and a.starttime<unix_timestamp() and ";
    switch ($classidtype) {
        case 1:
            $sql.=" a.class_id='$class_id'";
            break;
        case 2:
            $sql.=" a.class_id in (select id from base_class where upid='$class_id')";
            break;
        case 3:
            if (is_array($class_id) && !empty($class_id)) {
                $class_id = implode(',', $class_id);
            } else {
                $class_id = intval($class_id);
            }
            $sql.=" a.class_id in ($class_id)";
            break;
        case 4:
            $sql.=" a.class_id in (select id from base_class where type='$class_id')";
            break;
        case 5:
            $sql.=" a.class_id>0";
            break;
        default:
            $sql = " a.class_id='$class_id'";
    }
    if ($pic < 2)
        $sql.=" and a.pic='$pic'";
    if ($except != '')
        $sql.=" and a.id not in ($except)";
    if ($top == 5)//只取置顶级别的发布信息
        $sql.=" and a.toprank=5";
    if ($top > 1 && $top < 5)//取小于该发布级别的信息，用于和$top=5配合使用
        $sql.=" and a.toprank<" . $top;
	  // $sql.=" group by target_id";
    $sql.=" order by";
    if ($top > 0)//当$top=-1时，不根据发布级别排序，仅按照时间，当top=-2时按照播放量排
        $sql.=" a.toprank desc,";
    if($top==-2){
        $sql.=" b.pv desc,";
    }
    $sql.=" a.starttime desc";
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
		$title=$arr['shorttitle']==''?$title:$arr['shorttitle'];
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('Y-m-d', $arr['starttime']);
        $data[$i]['time'] = date('H:i', $arr['starttime']);
        $data[$i]['id'] = $arr['target_id'];
        $data[$i]['len'] = timeformat($arr['len']);
        $data[$i]['pv'] = $arr['pv']*7;
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['catname'] = catname($arr['class_id']);
        $data[$i]['image'] = dealimage($arr['image']);
        if ($arr["publishtype"] == 3) {
            $data[$i]["url"] = $arr["url"];
        } else {
            $data[$i]['url'] = urlformat($arr['urlformat'], $arr['target_id'], $arr['class_id']);
        }
        if ($indexcontent > 0) {
            $content = str_replace("\n", "<br>", $arr['indexcontent']);
            $data[$i]['indexcontent'] = strip_tags(substrcn($content, $indexcontent, "..."));
        }
        $i++;
    }
    return $data;
}
function videoindex($class_id, $classidtype, $pagesize, $titlelen = 0, $pageposi = 0, $pic = 2, $top = 1, $indexcontent = 0, $except = '') {//获取无分页视频列表
    return videoindex_internal($class_id, $classidtype, $pagesize, $titlelen, $pageposi, $pic, $top, $indexcontent, $except);
}

// This function is only used for internal purpose to generate html file.
function videolist_internal($class_id, $classidtype, $pagesize, $pageno = 1, $pic = 2, $indexcontent = 0, $top = 1) {
    // mark yemol
    $pageposi = ($pageno - 1) * $pagesize;
    $now = time();
    $sql = "select a.*,b.pv,b.oripv  from base_publish a,video_list b where a.target_id=b.id and a.valid=1 and b.valid=1 and a.target_type=1 and a.starttime<unix_timestamp() and ";
    switch ($classidtype) {
        case 1:
            $sql.=" a.class_id='$class_id'";
            break;
        case 2:
            $sql.=" a.class_id in (select id from base_class where upid='$class_id')";
            break;
        case 3:
            if (is_array($class_id) && !empty($class_id)) {
                $class_id = implode(',', $class_id);
            } else {
                $class_id = intval($class_id);
            }
            $sql.=" a.class_id in ($class_id)";
            break;
        case 4:
            $sql.=" a.class_id in (select id from base_class where type='$class_id')";
            break;
        default:
            $sql = " a.class_id='$class_id'";
    }
    if ($pic < 2)
        $sql.=" and a.pic='$pic'";
	  // $sql.=" group by target_id";
    $sql.=" order by";
    if ($top > 0)//当$top=-1时，不根据发布级别排序，仅按照时间，当top=-2时按照播放量排
        $sql.=" a.toprank desc,";
    if($top==-2){
        $sql.=" b.pv desc,";
    }
    $sql.=" a.starttime desc";
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $totalpage = Ceil($total / $pagesize);
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('Y-m-d', $arr['starttime']);
        $data[$i]['time'] = date('H:i', $arr['starttime']);
        $data[$i]['id'] = $arr['target_id'];
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['len'] = timeformat($arr['len']);
        $data[$i]['pv'] = $arr['pv']*7;
        $data[$i]['catname'] = catname($arr['class_id']);
        $data[$i]['image'] = dealimage($arr['image']);
        if ($arr["publishtype"] == 3) {
            $data[$i]["url"] = $arr["url"];
        } else {
            $data[$i]['url'] = urlformat($arr['urlformat'], $arr['target_id'], $arr['class_id']);
        }
        if ($indexcontent > 0) {
            $content = str_replace("\n", "<br>", $arr['indexcontent']);
            $data[$i]['indexcontent'] = strip_tags(substrcn($content, $indexcontent, "..."));
        }
        $i++;
    }
    $total = array();
    $total['data'] = $data;
    $total['totalpage'] = $totalpage;
    $total['pageno'] = $pageno;
    return $total;
}
function videolist($class_id, $classidtype, $pagesize, $pageno = 1, $pic = 2, $indexcontent = 0, $top = 1) {
    return videolist_internal($class_id, $classidtype, $pagesize, $pageno, $pic, $indexcontent, $top);
}

function videoalbumclass($up_id) {//获取视频专辑类别
    $up_id = intval($up_id);
    $sql = "select * from video_albumclass where upid='$up_id' and valid=1 order by id";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $data[$i]['name'] = $arr['classname'];
        $data[$i]['id'] = $arr['id'];
        $i++;
    }
    return $data;
}

function videoalbumindex($videoalbumclass_id, $pagesize,$pageposi=0,$type=0) {//获取视频专辑
    $videoalbumclass_id = intval($videoalbumclass_id);
    $sql = "select * from video_album where valid=1 and last_vid>0";
    if ($videoalbumclass_id > 0) {
        $sql.=" and albumclass_id=" . $videoalbumclass_id;
    }
    if ($type > 0) {
        $sql.=" and atype=" . $type;
    }
    $sql.=" order by displayorder desc,updatetime desc";
    $sql .= " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $data[$i]['albumname'] = $arr['albumname'];
        $data[$i]['dateline'] = $arr['updatetime'];
        $data[$i]['date'] = date('Y-m-d', $arr['updatetime']);
        $data[$i]['time'] = date('H:i', $arr['updatetime']);
        $data[$i]['id'] = $arr['id'];
        $data[$i]['albumclass_id'] = $arr['albumclass_id'];
        $data[$i]['note'] = $arr['note'];
        $data[$i]['image'] = dealimage($arr['image']);
        $vid = $arr['last_vid'];
        $sql1 = "select title from video_list where id=" . $vid;
        $result1 = mysql_query($sql1);
        $data[$i]['last_video'] = mysql_result($result1, 0, 'title');
        $data[$i]['last_vid'] = $vid;
        $sql1 = "select class_id from base_publish where publishtype=1 and target_type=1 and target_id=" . $vid;
        $result1 = mysql_query($sql1);
        if(mysql_num_rows($result1)){
            $data[$i]['last_videoclass'] = mysql_result($result1, 0, 'class_id');
        }else{
            $data[$i]['last_videoclass'] = 0;
        }
        $i++;
    }
    return $data;
}

function videoalbumlist($videoalbumclass_id, $pageno = 1, $pagesize=20) {//获取视频专辑有分页
    $videoalbumclass_id = intval($videoalbumclass_id);
    $pageno=$pageno==0?1:$pageno;
    $pageposi = ($pageno - 1) * $pagesize;
    $sql = "select * from video_album where valid=1 and last_vid>0";
    if ($videoalbumclass_id > 0) {
        $sql.=" and albumclass_id=" . $videoalbumclass_id;
    }
    $sql.=" order by displayorder desc,updatetime desc";
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $totalpage = Ceil($total / $pagesize);
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $data[$i]['albumname'] = $arr['albumname'];
        $data[$i]['dateline'] = $arr['updatetime'];
        $data[$i]['date'] = date('Y-m-d', $arr['updatetime']);
        $data[$i]['time'] = date('H:i', $arr['updatetime']);
        $data[$i]['id'] = $arr['id'];
        $data[$i]['albumclass_id'] = $arr['albumclass_id'];
        $data[$i]['note'] = $arr['note'];
        $data[$i]['image'] = dealimage($arr['image']);
        $vid = $arr['last_vid'];
        $sql1 = "select title from video_list where id=" . $vid;
        $result1 = mysql_query($sql1);
        $data[$i]['last_video'] = mysql_result($result1, 0, 'title');
        $data[$i]['last_vid'] = $vid;
        $sql1 = "select class_id from base_publish where publishtype=1 and target_type=1 and target_id=" . $vid;
        $result1 = mysql_query($sql1);
        if(mysql_num_rows($result1)){
            $data[$i]['last_videoclass'] = mysql_result($result1, 0, 'class_id');
        }else{
            $data[$i]['last_videoclass'] = 0;
        }
        $i++;
    }
    $total = array();
    $total['data'] = $data;
    $total['totalpage'] = $totalpage;
    $total['pageno'] = $pageno;
    return $total;
}

function albumvideos($album_id, $pagesize,  $pageposi = 0,$titlelen=20) {//获取专辑视频列表
    $sql="select a.* from base_publish a,video_albumvideo b where a.target_id=b.vid and a.target_type=1 and b.album_id=".$album_id." and b.valid=1 and a.valid=1";
    $sql.=" order by b.displayorder desc,b.addtime desc limit $pageposi,$pagesize";
    $result=  mysql_query($sql);
    $data=array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
        $title=$arr['shorttitle']==''?$title:$arr['shorttitle'];
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('m-d', $arr['starttime']);
        $data[$i]['time'] = date('H:i', $arr['starttime']);
        $data[$i]['id'] = $arr['target_id'];
        $data[$i]['len'] = timeformat($arr['len']);
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['catname'] = catname($arr['class_id']);
        $data[$i]['url'] = urlformat($arr['urlformat'], $arr['target_id'], $arr['class_id']);
        $data[$i]['image'] = dealimage($arr['image']);
        $i++;
    }
    return $data;
}

function albumvideolist($album_id, $pageno = 1, $pagesize=20) {//获取专辑视频有分页
    $videoalbumclass_id = intval($videoalbumclass_id);
    $pageno=$pageno==0?1:$pageno;
    $pageposi = ($pageno - 1) * $pagesize;
    $sql="select a.* from base_publish a,video_albumvideo b where a.target_id=b.vid and a.target_type=1 and b.album_id=".$album_id." and b.valid=1 and a.valid=1";
    $sql.=" order by b.displayorder desc,b.addtime desc";
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $totalpage = Ceil($total / $pagesize);
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data=array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
        $title=$arr['shorttitle']==''?$title:$arr['shorttitle'];
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('m-d', $arr['starttime']);
        $data[$i]['time'] = date('H:i', $arr['starttime']);
        $data[$i]['id'] = $arr['target_id'];
        $data[$i]['len'] = timeformat($arr['len']);
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['catname'] = catname($arr['class_id']);
        $data[$i]['url'] = urlformat($arr['urlformat'], $arr['target_id'], $arr['class_id']);
        $data[$i]['image'] = dealimage($arr['image']);
        $i++;
    }
    $total = array();
    $total['data'] = $data;
    $total['totalpage'] = $totalpage;
    $total['pageno'] = $pageno;
    return $total;
}

function getvideoalbum($album_id,$pageno=1,$pagesize=8) {
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
    $sql="select a.title,a.target_id,a.image,a.class_id,b.addtime from base_publish a,video_albumvideo b where a.target_id=b.vid and a.target_type=1 and b.album_id=".$album_id." and a.publishtype=1 and b.valid=1 and a.valid=1";
    $sql.=" order by b.displayorder desc,b.addtime desc limit $pageposi,$pagesize";
    $result=  mysql_query($sql);
    $vlist=array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $vlist[$i]['id'] = $arr['target_id'];
        $vlist[$i]['title'] = $arr['title'];
        $vlist[$i]['class_id'] = $arr['class_id'];
        $vlist[$i]['image'] = dealimage($arr["image"]);
        $vlist[$i]['datetime'] = date('Y-m-d H:i',$arr["addtime"]);
        $i++;
    }
    $data["vlist"]=$vlist;
    return $data;
}

function tagpublishindex($tag_id, $pagesize = 20, $pic = 0, $except = 0, $excepttype = 0,$pageposi=0,$titlelen=20) {//根据TAG取发布信息
    $tag_id = intval($tag_id);
    $sql = "select a.*,concat(a.target_type,'_',a.target_id) unid from base_publish a,base_taglist b where b.tag_id='" . $tag_id . "'";
    if($except>0)
        $sql.=" and (b.target_id<>'" . $except . "' or b.target_type<>'" . $excepttype . "')";
    $sql.=" and b.target_id=a.target_id and a.target_type=b.target_type and a.valid=1 and a.publishtype=1";
    if ($pic) {
        $sql.=" and a.pic=1";
    }
    // $sql.=" group by unid";
    $sql.=" order by a.starttime desc";
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $totalpage = Ceil($total / $pagesize);
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('Y-m-d', $arr['starttime']);
        $data[$i]['time'] = date('H:i', $arr['starttime']);
        $data[$i]['id'] = $arr['id'];
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['catname'] = catname($arr['class_id']);
        $data[$i]['url'] = urlformat($arr['urlformat'], $arr['target_id'], $arr['class_id']);
        $data[$i]['image'] = dealimage($arr['image']);
        $i++;
    }
    return $data;
}

function tagvideoindex($tag_id, $pagesize = 20, $pic = 0, $except = 0, $order=1 , $day=0 ,$pageposi=0,$titlelen=20) {//根据TAG取发布信息
    $tag_id = intval($tag_id);
    $sql = "select a.*,concat(a.target_type,'_',a.target_id) unid from base_publish a,base_taglist b,video_list c where b.tag_id='" . $tag_id . "' and b.target_type=1";
	if($day>0){
		$limittime=time()-$day*86400;
		$sql.=" and c.dateline>".$limittime;
	}
    if($except>0)
        $sql.=" and b.target_id<>'" . $except . "'";
    $sql.=" and b.target_id=a.target_id and c.id=b.target_id and a.target_type=b.target_type and a.valid=1 and a.publishtype=1";
    if ($pic) {
        $sql.=" and a.pic=1";
    }
    // $sql.=" group by unid";
	if($order==1){
		$sql.=" order by a.toprank desc,a.starttime desc";
	}else{
		$sql.=" order by c.pv desc";
	}
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $totalpage = Ceil($total / $pagesize);
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
		$data[$i]['indexcontent'] = $arr['indexcontent'];
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('Y-m-d', $arr['starttime']);
        $data[$i]['time'] = date('H:i', $arr['starttime']);
        $data[$i]['id'] = $arr['id'];
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['catname'] = catname($arr['class_id']);
		$data[$i]['target_id'] = $arr['target_id'];
        $data[$i]['url'] = urlformat($arr['urlformat'], $arr['target_id'], $arr['class_id']);
        $data[$i]['image'] = dealimage($arr['image']);
        $i++;
    }
    return $data;
}

function tagpublishlist($tag_id, $pagesize = 20, $pageno = 1, $pic = 0,$titlelen=20) {
    $pageposi = ($pageno - 1) * $pagesize;
    $sql = "select a.*,concat(a.target_type,'_',a.target_id) unid from base_publish a,base_taglist b";
    $sql.=" where b.tag_id='" . $tag_id . "' and b.target_id=a.target_id and a.target_type=b.target_type and a.valid=1 and a.publishtype=1";
    if ($pic) {
        $sql.=" and a.pic=1";
    }
    // $sql.=" group by unid";
    $sql.=" order by a.starttime desc";
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $totalpage = Ceil($total / $pagesize);
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
		$data[$i]['indexcontent'] = $arr['indexcontent'];
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('Y-m-d', $arr['starttime']);
        $data[$i]['time'] = date('H:i', $arr['starttime']);
        $data[$i]['id'] = $arr['id'];
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['catname'] = catname($arr['class_id']);
        $data[$i]['url'] = urlformat($arr['urlformat'], $arr['target_id'], $arr['class_id']);
        $data[$i]['image'] = dealimage($arr['image']);
        $i++;
    }
    $total = array();
    $total['data'] = $data;
    $total['totalpage'] = $totalpage;
    $total['pageno'] = $pageno;
    return $total;
}

function gettag($tagid) {//获取TAG
    $tagid = intval($tagid);
    $sql = "select tag from base_tag where id='" . $tagid . "'";
    $result = mysql_query($sql);
    if (mysql_affected_rows() > 0) {
        $tag = mysql_result($result, 0, 'tag');
    } else {
        $tag = '';
    }
    return $tag;
}

function catname($class_id) {//获取栏目名称
    $sql = "select classname from base_class where id='" . $class_id . "'";
    $result = mysql_query($sql);
    if (mysql_affected_rows() > 0) {
        $catname = mysql_result($result, 0, 'classname');
    } else {
        $catname = '';
    }
    return $catname;
}

function getclass($class_id) {//获取栏目信息
    $sql = "select classname,url from base_class where id='" . $class_id . "'";
    $result = mysql_query($sql);
    if (mysql_affected_rows() > 0) {
        $catname = mysql_result($result, 0, 'classname');
        $caturl = mysql_result($result, 0, 'url');
    } else {
        $catname = '';
        $caturl = '';
    }
    return array('catname' => $catname, 'caturl' => $caturl);
}

function getVideo($vid,$class_id=0){
    $vid=intval($vid);
		$searchid = '';
    if($class_id==0){
        $sql="select id, class_id from base_publish where target_id=".$vid." and target_type=1 and publishtype=1";
        $result=  mysql_query($sql);
				$arr=  mysql_fetch_array($result);
        $class_id=$arr['class_id'];
				$classid=$class_id;
				$searchid=$arr["id"];
    }
    $sql="select classname,upid from base_class where id=".$class_id;
    $result=  mysql_query($sql);
    $classarr[]=array('id'=>$class_id,'classname'=>mysql_result($result,0,'classname'));
    $class_id=mysql_result($result,0,'upid');
    while($class_id>0){
        $sql="select classname,upid from base_class where id=".$class_id;
        $result=  mysql_query($sql);
        $classarr[]=array('id'=>$class_id,'classname'=>mysql_result($result,0,'classname'));
        $class_id=mysql_result($result,0,'upid');
    }
    $sql="select * from video_list where id=".$vid;
    $result=  mysql_query($sql);
    $arr=  mysql_fetch_array($result);
    $data["title"]=$arr["title"];
		$data["searchid"]=$searchid;
    $data["old_id"]=$arr["vid"];
    $data["image"]=dealimage($arr["image"]);
    //$data["vurl"]=$arr["vurl"];
    $data["datetime"]=date("Y-m-d H:i",$arr["dateline"]);
    $data["totalpv"]=(ceil($arr['oripv']/10)+$arr["pv"]*7);
    $data["len"]=  timeformat($arr["len"]);
    $data["vdesc"]=$arr["vdesc"];
    $data["vkey"]=$arr["vkey"];
    $data["vtype"]=$arr["vtype"];
    switch ($data['vtype']){
        case 1:
            //$vurl='http://v.youku.com/player/getRealM3U8/vid/'.$data['vkey'].'/type/hd2/v.m3u8';
			//$vurl='http://v.youku.com/player/getRealM3U8/vid/'.$data['vkey'].'/type/video.m3u8';
			//$vurl='http://v.youku.com/player/getrealM3U8/vid/'.$data['vkey'].'/type/mp4/video.m3u8';
			$vurl='http://v.gamefy.cn/yk.php?vid='.$data['vkey'];
			//$vurl='http://v.youku.com/player/getM3U8/vid/'.$data['vkey'].'/type/hd2/v.m3u8';
            //$vurl='http://pl.youku.com/playlist/m3u8?vid='.$data['vkey'].'&type=hd2';
            $data['vurl']=$vurl;
            break;
        case 6:
            include('qqvideo.php');
            $qqvideo = new qqVideo();
            $vurl = $qqvideo->parseVideoUrl($data["vkey"]);
            $data['vurl']=$vurl;
            break;
        case 7:
            $vurl='http://v.gamefy.cn/iqiyi/playM3U8/'.$data['vkey'];
            $data['vurl']=$vurl;
            break;
    }
    $data["author_id"]=$arr["user_id"];
	$data['weburl']=  urlformat(1, $vid,$classid);
    $data["author"]=  getAuthor($arr["user_id"]);
    $data["classarr"]=$classarr;
    return $data;
}

function getvideorel($vid,$pagesize=3){
	if (!class_exists('qqVideo')) {
    	include('qqvideo.php');
	}
    $vid=intval($vid);
    $sql="select tag_id from base_taglist where target_type=1 and target_id=".$vid;
    $result=mysql_query($sql);
    $i=0;
    $relate=array();
    while($i<$pagesize&&$row=mysql_fetch_array($result)){
            $sql1="select p.*,v.vtype,v.vkey from base_taglist t,base_publish p,video_list v where t.target_type=1 and v.vkey<>'' and t.target_id=p.target_id and p.pic=1 and p.target_type=1 and p.publishtype=1 and t.target_id<>".$vid." and t.tag_id=".$row['tag_id']." and p.valid=1 and v.id=p.target_id order by p.starttime desc limit 0,".$pagesize;
            $result1=mysql_query($sql1);
            while($row2=mysql_fetch_array($result1)){
                            $relate[$i]['id']=$row2['target_id'];
                            $relate[$i]['title']=$row2['title'];
                            $relate[$i]['image']=dealimage($row2['image']);
                            switch ($row2['vtype']){
                                case 1:
                                    //$vurl='http://v.youku.com/player/getRealM3U8/vid/'.$row2['vkey'].'/type/hd2/v.m3u8';
									//$vurl='http://v.youku.com/player/getRealM3U8/vid/'.$data['vkey'].'/type/video.m3u8';
									$vurl='http://v.gamefy.cn/yk.php?vid='.$row2['vkey'];
									//$vurl='http://v.youku.com/player/getrealM3U8/vid/'.$row2['vkey'].'/type/mp4/video.m3u8';
									//$vurl='http://v.youku.com/player/getM3U8/vid/'.$data['vkey'].'/type/hd2/v.m3u8';
                                    //$vurl='http://pl.youku.com/playlist/m3u8?vid='.$row2['vkey'].'&type=hd2';
                                    $relate[$i]['vurl']=$vurl;
                                    break;
                                case 6:

                                    $qqvideo = new qqVideo();
                                    $vurl = $qqvideo->parseVideoUrl($row2["vkey"]);
                                    $relate[$i]['vurl']=$vurl;
                                    break;
                            }
                            $relate[$i]['vdesc']=$row2['indexcontent'];
                            $relate[$i]['len']=timeformat($row2['len']);
                            $relate[$i]['date']=date('Y-m-d',$row2['starttime']);
                            $i++;
            }
    }
    return $relate;
}


function getNews($id, $pageno = 1) {//获取资讯
    $data = array();
    $news_id = intval($id);
    $pageno = intval($pageno);
    $sql = "select a.class_id,a.album,a.valid,a.comment,a.keywords,a.dateline,a.author,a.user_id,a.authorword,a.newsfrom,a.title,a.indexcontent,b.content from news_subject a,news_content b where a.id='$news_id' and b.news_id='$news_id' order by b.page ";
    $result = mysql_query($sql);
    $totalpage = mysql_num_rows($result);
    if ($totalpage > 0) {
        if ($pageno > 0) {
            $pageno = $pageno - 1;
            $sql.=" limit $pageno,1";
        }
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $class_id = $row['class_id'];
        $data['subject'] = $row['title'];
        $data['description'] = trim($row['indexcontent']) == '' ? $row['title'] : trim($row['indexcontent']);
        $data['message'] = $row['content'];
        $data['dateline'] = date('Y-m-d H:i:s', $row['dateline']);
        $data['author'] = $row['author'];
        $data['newsfrom'] = $row['newsfrom'];
        $data['authorword'] = $row['authorword'];
        $data['comment'] = $row['comment'];
        $data['keywords'] = str_replace(' ', ',', $row['keywords']);
        $data['user_id'] = $row['user_id'];
        $data['album'] = $row['album'];
        $data['valid'] = $row['valid'];
        $data['username'] = getAuthor($row['user_id']);
        $data['catid'] = $class_id;
        $data['catname'] = catname($class_id);
        $data['totalpage'] = $totalpage;
    }
    return $data;
}

function getPre($news_id, $class_id = 0) {
    $news_id = intval($news_id);
    $data = array();
    if ($class_id == 0) {
        $sql = "select class_id from news_subject where id='" . $news_id . "'";
        $class_id = mysql_result(mysql_query($sql), 0, "class_id");
    }
    $sql = "select title,target_id,urlformat from base_publish where target_id<'" . $news_id . "' and target_type=2 and class_id='" . $class_id . "' and valid=1 order by target_id desc limit 0,1";
    $result = mysql_query($sql);
    if (mysql_num_rows($result)) {
        $arr = mysql_fetch_array($result);
        $data["title"] = $arr['title'];
        $data["url"] = urlformat($arr['urlformat'], $arr['target_id'], $class_id);
    } else {
        $data["title"] = '';
        $data["url"] = '';
    }
    return $data;
}

function getNext($news_id, $class_id = 0) {
    $news_id = intval($news_id);
    $data = array();
    if ($class_id == 0) {
        $sql = "select class_id from news_subject where id='" . $news_id . "'";
        $class_id = mysql_result(mysql_query($sql), 0, "class_id");
    }
    $sql = "select title,target_id,urlformat from base_publish where target_id>'" . $news_id . "' and target_type=2 and class_id='" . $class_id . "' and valid=1 and starttime<'" . time() . "' order by target_id limit 0,1";
    $result = mysql_query($sql);
    if (mysql_num_rows($result)) {
        $arr = mysql_fetch_array($result);
        $data["title"] = $arr['title'];
        $data["url"] = urlformat($arr['urlformat'], $arr['target_id'], $class_id);
    } else {
        $data["title"] = '';
        $data["url"] = '';
    }
    return $data;
}

function getAuthor($user_id) {
    $user_id = intval($user_id);
    $sql = "select author from sys_user where id='" . $user_id . "'";
    $author = mysql_result(mysql_query($sql), 0, "author");
    return $author;
}

function getTags($target_id, $target_type) {//获取相关TAG
    $target_id = intval($target_id);
    $target_type = intval($target_type);
    $data = array();
    $sql = "select a.* from base_tag a,base_taglist b where a.id=b.tag_id and b.target_id='" . $target_id . "' and b.target_type='" . $target_type . "' and a.valid='1' order by b.id";
    $result = mysql_query($sql);
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $data[$i] = $row;
        $i++;
    }
    return $data;
}

function getalbumbyvid($vid) {//获取相关专辑
    $vid = intval($vid);
    $data = array();
    $sql = "select a.* from video_album a,video_albumvideo b where a.id=b.album_id and b.vid='" . $vid . "' and a.valid='1' order by a.id";
    $result = mysql_query($sql);
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $data[$i] = $row;
        $i++;
    }
    return $data;
}

function dealimage($image) {//处理图片地址
    if ($image != '') {
        if (substr($image, 0, 7) == 'http://') {
            $url = $image;
        } else {
            $url = __ATTURL . $image;
        }
    } else {
        $url = 'http://www.gamefy.cn/images2013/404pic.jpg';
    }
    return $url;
}

function urlformat($urlformat_id, $target_id, $class_id = 0) {
    $urlformat = geturlformat($urlformat_id);
    $url = str_replace(array("{id}", "{cid}"), array($target_id, $class_id), $urlformat);
    return $url;
}

function timeformat($num) {//将秒数转化为时间格式
    $hour = floor($num / 3600);
    $minute = floor(($num - 3600 * $hour) / 60);
    $second = floor((($num - 3600 * $hour) - 60 * $minute) % 60);
    $hour = $hour > 9 ? $hour : '0' . $hour;
    $minute = $minute > 9 ? $minute : '0' . $minute;
    $second = $second > 9 ? $second : '0' . $second;
    return $hour . ':' . $minute . ':' . $second;
}

function geturlformat($id) {
    $id = intval($id);
    $sql = "select format from base_urlformat where id=" . $id;
    $result = mysql_query($sql);
    return mysql_result($result, 0, 'format');
}

function getad($id) {
    $id = intval($id);
    $sql = "select * from ad_list where id='" . $id . "'";
    $result = mysql_query($sql);
    $data = mysql_fetch_array($result);
    switch ($data["adtype"]){
        case 1:
            if($data["image"]!=''){
                $ad = '<a href="' . $data["url"] . '" target="_blank"><img src="' . $data["image"] . '" width="' . $data["width"] . '" height="' . $data["height"] . '" border="0"></a>';
            }else{
                $ad = '<a href="' . $data["url"] . '" target="_blank">' . $data["subject"] . '</a>';
            }
            break;
        case 2:
            $ad = '<a href="' . $data["url"] . '" target="_blank">' . $data["subject"] . '</a>';
            break;
        case 3:
            if($data["image"]!=''){
                $ad = '<embed src="' . $data["image"] . '" width="' . $data["width"] . '" height="' . $data["height"] . '" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed>';
            }else{
                $ad='';
            }
             break;
        default:
            $ad="";
    }
    return $ad;
}

function getadlist($class_id) {
    $id = intval($class_id);
    $sql = "select * from ad_list where class_id='" . $id . "' and valid=1 order by displayorder desc,id";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $data[$i] = $row;
        $i++;
    }
    return $data;
}

function adlist($class_id, $pagesize = 0) {
    $id = intval($class_id);
    $sql = "select * from ad_list where class_id='" . $id . "' and valid=1 order by displayorder desc,id";
    if ($pagesize > 0)
        $sql.=" limit 0,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $data[$i] = $row;
        $i++;
    }
    return $data;
}

function adsize($class_id) {
    $id = intval($class_id);
    $sql = "select * from ad_class where id='" . $id . "' and adtype=1";
    $result = mysql_query($sql);
    $data = array();
    $row = mysql_fetch_array($result);
    $data['width'] = $row['width'];
    $data['height'] = $row['height'];
    return $data;
}

function getvote($v_id) {
    $sql = "select * from vote_subject where id='" . $v_id . "'";
    $result = mysql_query($sql);
    $data = mysql_fetch_array($result);
    $sql = "select * from vote_option where v_id='" . $v_id . "' order by displayorder desc,id";
    $result = mysql_query($sql);
    $option = array();
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $option[$i]['id'] = $row['id'];
        $option[$i]['title'] = $row['title'];
        $option[$i]['votenum'] = $row['votenum'];
        $i++;
    }
    $data['option'] = $option;
    return $data;
}

function programjs() {
    $sql = "select content from program where day='" . strtolower(date("l")) . "'";
    $result = mysql_query($sql);
    $content = mysql_result($result, 0, "content");
    $conarr = unserialize($content);
    $constr = 'new Array(';
    $i = 0;
    while ($i < count($conarr)) {
        if ($i > 0)
            $constr.=',';
        $constr.='new Array("' . $conarr[$i][0] . '","' . $conarr[$i][1] . '")';
        $i++;
    }
    $constr.=');';
    return $constr;
}

function pagenav($url, $totalpage, $pageno, $current, $prv, $next) {
    $pagenav = '';
    if (strpos($url, '?') === false) {
        $url .= '?';
    } elseif (substr($url, -1) != '&') {
        $url .= '&';
    }
    if ($totalpage > 1) {
        $pagenav.='<a href="' . $url . 'page=1" class="' . $prv . '">��ҳ</a>';
        if ($pageno > 1) {
            $pagenav.='<a href="' . $url . 'page=' . ($pageno - 1) . '" class="' . $prv . '">��һҳ</a>';
        }
        if ($pageno > 9) {
            if ($totalpage - $pageno > 9) {
                $i = $pageno - 4;
            } else {
                $i = $totalpage - 9;
            }
        } else {
            $i = 1;
        }
        $j = 0;
        while ($j < 10 and $j < $totalpage) {
            $pagenav.='<a href="' . $url . 'page=' . $i . '"';
            if ($i == $pageno)
                $pagenav.=' class="' . $current . '"';
            $pagenav.='> ' . $i . '</a>';
            $i++;
            $j++;
        }
        if ($totalpage > $pageno) {
            $pagenav.='<a href="' . $url . 'page=' . ($pageno + 1) . '" class="' . $next . '">��һҳ</a>';
        }
        $pagenav.='<a href="' . $url . 'page=' . $totalpage . '" class="' . $next . '">βҳ</a>';
    }
    return $pagenav;
}

function page($url, $totalpage, $pageno) {
    $pagenav = '';
    if ($totalpage > 1) {
        $pagenav.='<a href="' . str_replace('(page)', 1, $url) . '">&lt;&lt;</a>&nbsp;';
        if ($pageno > 1) {
            $pagenav.='<a href="' . str_replace('(page)', $pageno - 1, $url) . '">&lt;</a>&nbsp;';
        }
        if ($pageno > 9) {
            if ($totalpage - $pageno > 9) {
                $i = $pageno - 4;
            } else {
                $i = $totalpage - 9;
            }
        } else {
            $i = 1;
        }
        $j = 0;
        while ($j < 10 and $j < $totalpage) {

            if ($i == $pageno) {
                $pagenav.='<span class="current">' . $i . '</span>';
            } else {
                $pagenav.='<a href="' . str_replace('(page)', $i, $url) . '"> ' . $i . '</a>';
            }
            $pagenav.='';
            $i++;
            $j++;
        }
        if ($totalpage > $pageno) {
            $pagenav.='&nbsp;<a href="' . str_replace('(page)', $pageno + 1, $url) . '">&gt;</a>&nbsp;';
        }
        $pagenav.='<a href="' . str_replace('(page)', $totalpage, $url) . '">&gt;&gt;</a>';
    }
    return $pagenav;
}

function getfiles($filename) {
    $fp = fopen(__SITE_ROOT . "data/" . $filename, "r");
    if ($fp) {
        while (!feof($fp)) {
            $content.=fgets($fp, 4096);
        }
        fclose($fp);
    }
    $data = json_decode($content, true);
    return $data;
}

function titlestyle($title, $color, $strong, $len = 0) {
    if ($len > 0) {
        $title = substrcn($title, $len);
    }
    if (trim($color) != '') {
        $title = '<font color="' . $color . '">' . $title . '</font>';
    }
    if ($strong) {
        $title = '<strong>' . $title . '</strong>';
    }
    return $title;
}

function qrequest($fString) {
    $fString = trim($fString);
    $fString = str_replace(";", "", $fString);
    $fString = str_replace("@", "", $fString);
    $fString = str_replace("%", "", $fString);
    $fString = str_replace(" and", "", $fString);
    $fString = str_replace(" or", "", $fString);
    $fString = str_replace("'", "", $fString);
    $fString = str_replace("\\", "", $fString);
    $fString = str_replace("<script", "", $fString);
    $fString = str_replace("</script>", "", $fString);
    $fString = str_replace("<", "*", $fString);
    $fString = str_replace(">", "*", $fString);
    return $fString;
}

function checkEmail($email) {
    if (!eregi("^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.|\-]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$", $email)) {
        return false;
    } else {
        return true;
    }
}

function getIP() {
    global $_SERVER;
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('REMOTE_ADDR')) {
        $ip = getenv('REMOTE_ADDR');
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function substrcn($str, $len, $dot = '') {
    $patten = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
    preg_match_all($patten, $str, $regs);
    $v = 0;
    $s = '';
    for ($i = 0; $i < count($regs[0]); $i++) {
        (ord($regs[0][$i]) > 129) ? $v += 2 : $v++;
        $s .= $regs[0][$i];
        if ($v >= $len * 2) {
            $s .= $dot;
            break;
        }
    }
    return $s;
}
//-------------------------------------------
function channellist($channel, $pagesize, $pageno = 1) {
    $pageno = intval($pageno);
    $sql = "select classname,id,note,image,url from base_class where valid=1 and upid=0 and type='" . $channel . "'";
    $pageposi = ($pageno - 1) * $pagesize;
    $sql.=" order by displayorder,id desc";
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $totalpage = Ceil($total / $pagesize);
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $data[$i]['name'] = $arr['classname'];
        $data[$i]['note'] = $arr['note'];
        $data[$i]['id'] = $arr['id'];
        $data[$i]['url'] = $arr['url'];
        $data[$i]['image'] = $arr['image'];
        $i++;
    }
    $total = array();
    $total['data'] = $data;
    $total['totalpage'] = $totalpage;
    $total['pageno'] = $pageno;
    return $total;
}

function gameindex($game, $pagesize, $titlelen) {
    $i = 0;
    $data = array();
    $len = $pagesize < count($game) ? $pagesize : count($game);
    while ($i < $len) {
        $data[$i]['title'] = $game[$i]['game_name'];
        $data[$i]['styletitle'] = substrcn($game[$i]['game_name'], $titlelen, "");
        $data[$i]['image'] = $game[$i]['img'];
        $data[$i]["url"] = 'http://u.gamefy.cn/gamelist/game_servers/' . $game[$i]['game_id'];
        $i++;
    }
    return $data;
}

function calendarlist($year = '2011', $month = '02', $day = '01') {
    $sql = "select id from calendar_list where year='" . $year . "' and month='" . $month . "' and day='" . $day . "'";
    $id = mysql_result(mysql_query($sql), 0, "id");
    $min = $id > 16 ? ($id - 16) : 0;
    $max = $id + 16;
    $sql = "select day,id,title,image,color from calendar_list where id>'" . $min . "' and id<'" . $max . "' order by id";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $data[$i] = $arr;
        $i++;
    }
    return $data;
}

function getday($id) {
    $id = intval($id);
    $sql = "select * from calendar_list where id=" . $id;
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    $arr = mysql_fetch_array($result);
    $data["image"] = $arr["image"];
    $data["news"] = str_replace("\n", "<br>", $arr["news"]);
    /* $newsarr=explode(",",$news);
      $newsdata=array();
      $j=0;
      while ($i<count($newsarr))
      {
      $nid=$newsarr[$i];
      $sql="select title from news_subject where id=".$nid;
      $result=mysql_query($sql);
      if(mysql_num_rows($result)){
      $newsdata[$j]["id"]=$nid;
      $newsdata[$j]["title"]=mysql_result($result,0,"title");
      $j++;
      }
      $i++;
      }
      $data["newsdata"]=$newsdata; */
    return $data;
}

function albumlist($class_id = 0, $pagesize = 6, $pageno = 1, $titlelen = 0) {
    $pageposi = ($pageno - 1) * $pagesize;
    $sql = "select a.title,a.news_id,a.class_id,a.starttime,a.image,a.strong,a.color from news_publish a,news_subject b";
    $sql.=" where b.newstype='album'";
    if ($class_id)
        $sql.=" and a.class_id='" . $class_id . "'";
    $sql.=" and a.news_id=b.id and a.pic=1 and a.valid=1 and a.publishtype<3 order by a.toprank desc,a.starttime desc";
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $totalpage = Ceil($total / $pagesize);
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('Y-m-d', $arr['starttime']);
        $data[$i]['id'] = $arr['news_id'];
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['catname'] = catname($arr['class_id']);
        $data[$i]['image'] = $arr['image'];
        $i++;
    }
    $total = array();
    $total['data'] = $data;
    $total['totalpage'] = $totalpage;
    $total['pageno'] = $pageno;
    return $total;
}

function relatealbum($pagesize = 6, $except = 0) {
    $pageposi = ($pageno - 1) * $pagesize;
    $sql = "select a.title,a.news_id,a.class_id,a.starttime,a.image,a.strong,a.color from news_publish a,news_subject b";
    $sql.=" where b.newstype='album' and a.news_id=b.id and a.pic=1 and a.valid=1 and a.publishtype=1";
    if ($expect)
        $sql.=" and b.id<>'" . $except . "'";
    $sql.=" order by a.starttime desc";
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $totalpage = Ceil($total / $pagesize);
    if ($total > $pagesize) {
        $pageposi = rand(0, ($total - $pagesize) > 30 ? 30 : ($total - $pagesize));
    } else {
        $pageposi = 0;
    }
    $sql = $sql . " limit $pageposi,$pagesize";
    $result = mysql_query($sql);
    $data = array();
    $i = 0;
    while ($arr = mysql_fetch_array($result)) {
        $title = $arr['title'];
        $data[$i]['title'] = $title;
        $data[$i]['styletitle'] = titlestyle($title, $arr['color'], $arr['strong'], $titlelen);
        $data[$i]['dateline'] = $arr['starttime'];
        $data[$i]['date'] = date('Y-m-d', $arr['starttime']);
        $data[$i]['id'] = $arr['news_id'];
        $data[$i]['catid'] = $arr['class_id'];
        $data[$i]['catname'] = catname($arr['class_id']);
        $data[$i]['image'] = $arr['image'];
        $i++;
    }
    return $data;
}

function addguser($username,$tel,$card){
	$sql="insert into `guser`(`username`, `tel`, `card`)";
	$sql.=" values('".$username."','".$tel."','".$card."')";
	mysql_query($sql);
	return true;
}

function getguser(){
	$sql="select * from guser";
	$result = mysql_query($sql);
	$data = array();
    $i = 0;
	while ($arr = mysql_fetch_array($result)) {
        $data[$i]['username'] =$arr['username'];
        $data[$i]['tel'] =$arr['tel'];
        $data[$i]['card'] = $arr['card'];
        $i++;
    }
    return $data;
}

function goto_error() {
    header('Location:http://www.gamefy.cn/404.html');
}
?>
