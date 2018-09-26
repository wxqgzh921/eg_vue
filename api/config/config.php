<?php
	define('__SITE_ROOT', '/home/www/android/'); // 最后有斜线
	define('__ATTURL',"http://att.gamefy.cn/files/");
	define('__WEBURL',"http://www.gamefy.cn/");
	require(__SITE_ROOT."function/global.php");
	require __SITE_ROOT.'lib/smarty/Smarty.class.php';
	$tpl = new Smarty();
	$tpl->template_dir =__SITE_ROOT."templates/";
	$tpl->compile_dir =__SITE_ROOT."templates_c/";
	$tpl->config_dir =__SITE_ROOT."config/";
	$tpl->cache_dir =__SITE_ROOT."cache/";
	$tpl->caching = false;
	$tpl->left_delimiter = '<{';
	$tpl->right_delimiter = '}>';

	$webtitle="游戏风云";
	//$CompanyName="上海文广互动电视有限公司";
	$CompanyName="上海游戏风云文化传媒有限公司";
	$tpl->assign("companyname",$CompanyName);
	$tpl->assign("atturl",__ATTURL);
	$tpl->assign("weburl",__WEBURL);

	$redisServer = '127.0.0.1';
	$redisPort = '6379';
?>
