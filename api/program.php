<?
// 1. 初始化
$ch = curl_init();
// 2. 设置选项，包括URL
curl_setopt($ch,CURLOPT_URL,"http://tvlist.gamefy.cn/getlist.php?date=".$_GET['date']);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,0);
// // 3. 执行并获取HTML文档内容
$output = curl_exec($ch);
if($output === FALSE){
	echo "";
} else {
	echo $output;
}
// 4. 释放curl句柄
curl_close($ch);
?>
