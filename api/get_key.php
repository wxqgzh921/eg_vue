<?
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

spl_autoload_register(function ($class) {
		$class = str_replace("\\", "/", $class);
    include $class . '.php';
});
include 'Qiniu/functions.php';

use Qiniu\Storage;
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

// 跨域请求保护。保证跨域请求的时候不会出问题
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE');
header('Access-Control-Allow-Headers:accept,authorization');

// 只有在真实请求的时候才需要返回数据。
// OPTIONS请求的时候不能做任何检查。
if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {exit();}

$upManager = new UploadManager();
$auth = new Auth("Dyb_oiOLaOjQRS5oomc8V-t-9pLsVbOPawbEgQtH","w1RmGOas4tAZHdDvSdE9RvdQYOCiR8ibENWX5DPc");
$token = $auth->uploadToken("avatar");
echo $token;
?>
