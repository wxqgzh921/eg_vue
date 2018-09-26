<?
require("./config/conn.php");
require("./config/config.php");
?>
<?
$pre_url = $_GET['pre_url'];
$access = $_GET['access'];
echo ("
<script>
localStorage.setItem(\"token\", \"$access\")
localStorage.setItem(\"timemark\", Date.now())
document.cookie = \"accesstokey=$access; path=/ ;\"
window.location.href = \"$pre_url\"
</script>
");
?>
