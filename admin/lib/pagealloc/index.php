<?php
	require_once 'page.php';
	require_once '../PDO/index2.php';
	$config = ['host'=>'localhost','dbname'=>'gaokao','user'=>'root','password'=>'root'];
	$db1 = Db::getInstance($config);
	$field = ['name','pwd','fee'];

?>
<!DOCTYPE html>
<html>
<head>
<meta charset = "utf-8">
<title>分页类</title>
</head>
<body>
<?php
	//$total = 300;
	$total = $db1->getCount('user',$field,'id>=1');
	$size = 2;//每页显示的条数
	$cur  = isset($_GET['page'])?$_GET['page']:1;
	$from = ($cur-1) * $size;
	$result = $db1->getAll('user',$field,'id>=1',$from,$size);
	foreach($result as $v){
		echo "<li>$v[name]</li>";
	}
	$page = new Page($total,$size,$cur);
	echo $page->showPage(); 
?>
</body>
</html>
