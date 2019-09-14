<?php
	//データベースのテーブル作成
	$dsn = 'mysql:dbname=（データベース名）;host=localhost';
$user = '（ユーザー名）';
$password = '（パスワード）';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	$sql = "CREATE TABLE IF NOT EXISTS usersData1"
	." ("
  . "user_id INT AUTO_INCREMENT PRIMARY KEY,"
  . "user_name VARCHAR( 255 ) NOT NULL ,"
  . "user_email VARCHAR( 60 ) NOT NULL ,"
  . "user_pointNum INT,"
  . "user_comment TEXT,"
  . "user_pass VARCHAR( 255 ) NOT NULL ,"
  . "UNIQUE (`user_name`),"
  . "UNIQUE (`user_email`)"
	.");";
	$stmt = $pdo->query($sql);
  $sql ='SHOW CREATE TABLE usersData1';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";
?>
