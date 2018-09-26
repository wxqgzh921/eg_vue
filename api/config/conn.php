<?
error_reporting(1);
error_reporting(E_ALL | E_STRICT);
$connection=mysql_connect("192.168.16.168","cmsnewdb","cmsnew_GameFY@");
// $connection=mysql_connect("10.0.0.87","test","test");
$conn=mysql_select_db("cmsnew",$connection);
mysql_query("SET NAMES utf8");

// $DBHelper_server = "10.0.0.87";
// $DBHelper_user = "test";
// $DBHelper_password = "test";
// $DBHelper_database = "gamefy";

$DBHelper_server = "192.168.16.168";
$DBHelper_user = "cmsnewdb";
$DBHelper_password = "cmsnew_GameFY@";
$DBHelper_database = "cmsnew";
?>
