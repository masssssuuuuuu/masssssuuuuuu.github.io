<?php
require_once('db_info.php');
//編集フォームの送信の有無で処理を分岐
if (!empty($_POST['edit']) && !empty($_POST['pass_edit'])) {

     //入力データの受け取りを変数に代入
    $edit = $_POST['edit'];
    $edit_pass = $_POST['pass_edit'];
}

try {//dbに接続
$pdo = new PDO($dsn, $user, $password ,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
}catch (PDOExeption $e){
    $error = 'データベース接続に失敗しました: ' . $e->getMessage();
  }

//テーブルをセレクト、データの読み込み
$sql = 'SELECT * FROM tbtest WHERE id= :id';
$stmt = $pdo->prepare($sql);  // ←差し替えるパラメータを含めて記述したSQLを準備し、
$stmt->bindParam(':id', $edit, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
$stmt->execute();                             // ←SQLを実行する。
$results = $stmt->fetchAll();
foreach ($results as $row){

  //$rowの中にはテーブルのカラム名が入る
  if ($edit  ==  $row['id'] && $edit_pass == $row['pass']) {

       //投稿のそれぞれの値を取得し変数に代入
      $editnumber = $row['id'];
      $editname = $row['name'];
      $editcomment = $row['comment'];
    }
  }//ここまでok

//編集か新規投稿か判断
//formからpost受信されたものを受け取る.新規投稿
if (empty($_POST['editNO']) && !empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['pass'])) {

//フォームの値を取得
   $name = $_POST['name'];
   $comment = $_POST['comment'];
   $pass = $_POST["pass"];
   $day = date("Y年m月d日 H:i:s");

}

// 在庫データベースのそれぞれのテーブルにデータを入れる準備をして、それを$sql変数に定義します～
 $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
  $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
  $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql -> execute();//実行



if (!empty($_POST["editNO"])) {// 以下編集機能
    //入力データの受け取りを変数に代入

       $sql = 'SELECT * FROM tbtest';
       $stmt = $pdo->query($sql);
       $results = $stmt->fetchAll();

       $flag = false;

       foreach ($results as $row){

           //もしidが編集番号に一致　かつ　passが入力されたパスワードに一致　したらflag をtrueにする
           $passw = $row['pass'];
           $num = $row["id"];

           //echo "password : " . $passw . " num : " . $num . " edit_post : " . $_POST["edit_post"] . " post_pass : " . $_POST["pass"] . "<br>";

           if($num == $_POST["editNO"] && $passw == $_POST["pass"]) {

           $flag = true;

       }
}
       if ($flag){

           $id = $_POST["editNO"];
           $name = $_POST["name"];
           $comment = $_POST["comment"];
           $day = date("Y/m/d H:i:s");

          $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date WHERE id=:id';
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':name', $name, PDO::PARAM_STR);
          $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
          $stmt->bindParam(':date', $date, PDO::PARAM_STR);
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);

           $stmt->execute();

       }else{

     $name = $_POST["name"];
     $comment = $_POST["comment"];
     $pass = $_POST["pass"];
   //追記モード
   $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
   $sql -> bindParam(':name', $name, PDO::PARAM_STR);
   $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
   $sql -> bindParam(':date', $date, PDO::PARAM_STR);
   $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);


   $sql -> execute();

   }
}

//削除機能
if (!empty($_POST['delete']) && !empty($_POST['pass_delete'])) {

  $delete = $_POST['delete'];

  $sql = 'SELECT * FROM tbtest';
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchAll();

  foreach ($results as $row){

         if($row["id"] == $_POST["delete"] && $row["pass"] == $_POST["pass_delete"]) {

           $sql = 'delete from tbtest where id=:id';
           $stmt = $pdo->prepare($sql);
           $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
           $stmt->execute();
         }
    }
}

//テーブル表示
$sql = 'SELECT * FROM tbtest';
 $stmt = $pdo->query($sql);
 $results = $stmt->fetchAll();
 foreach ($results as $row){
   //$rowの中にはテーブルのカラム名が入る
   echo $row['id'].',';
   echo $row['name'].',';
   echo $row['comment'].',';
    echo $row['date'].'<br>';
 echo "<hr>";
 }





?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>掲示板</title>
  </head>
  <body>

<h1>入力ホーム</h1>
<form action="form.php" method="post">

<input type="hidden" name="editNO" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>"><br>

  <tr>
      <th>名前</th>
      <td><input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"></td><br>
  </tr>

  <tr>
      <th>コメント</th>
      <td><input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"></td><br>
  </tr>



  <tr>
      <th>パスワード</th>
        <td><input type="text" name="pass" placeholder="パスワード"></td><br>
  </tr>

  <tr colspan="2">
        <td><input type="submit" name="normal" value="送信"></td>
  </tr>

</form>

<h1>削除ホーム</h1>
       <form action="form.php" method="post">

<tr>
    <td>削除対象番号<input type="text" name="delete" placeholder="削除対象番号"></td><br>
</tr>

<tr>
  <td>パスワード<input type="text" name="pass_delete" placeholder="パスワード"></td><br>
</tr>

<tr colspan="2">
      <input type="submit" value="削除"><br>
</tr>
    </form>

<h1>編集ホーム</h1>
    <form action="form.php" method="post">
      編集対象番号<input type="text" name="edit" placeholder="編集対象番号"><br>
      パスワード<input type="text" name="pass_edit" placeholder="パスワード"><br>
      <input type="submit" value="編集">
    </form>
<!-- 投稿フォーム終了 -->
</body>
</html>
