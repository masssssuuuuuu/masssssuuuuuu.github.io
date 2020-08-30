<?php
//データベースに登録
$dsn = 'データベース名';//接続先
$user = 'ユーザー名';//ユーザー名
$password = 'パスワード';//パスワード

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
