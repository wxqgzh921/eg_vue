<?
$pre_url = $_GET['pre_url'];
$access = $_GET['access'];
echo ("
<script>
localStorage.setItem(\"token\", \"$access\")
localStorage.setItem(\"timemark\", Date.now())
document.cookie = \"accesstokey=$access; domain=gamefy.cn; path=/\"
window.location.href = \"$pre_url\"
</script>
");
?>
