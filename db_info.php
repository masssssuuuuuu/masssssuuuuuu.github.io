<?php
//データベースに登録
$dsn = 'mysql:dbname=tb220306db;host=localhost';//接続先
$user = 'tb-220306';//ユーザー名
$password = 'LbbgbegHLg';//パスワード

$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS tbtest"
  ." ("
  . "id INT AUTO_INCREMENT PRIMARY KEY,"
  . "name char(32),"
  . "comment TEXT,"
  . "pass TEXT,"
  . "date TEXT"
  .");";

		$stmt = $pdo->query($sql);


?>
