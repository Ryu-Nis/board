<?php
session_start();
$error_message = "";

if(!$_SESSION['id']){
  header('Location: login.php');
}

if(isset($_POST['clear'])){
// セッション変数のクリア
$_SESSION = array();
// セッションクリア
@session_destroy();
header('Location: login.php');
}
?>


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="SJIS-win">
    <title>My Page</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>  
    <h1>マイページ</h1>
    <br>
    <form method="POST">
      <p><input type="submit" name="clear" value="ログアウト"></p>
    </form>
<!-- 画像登録 -->
    <form method="POST" action="" enctype="multipart/form-data">
    　<input type="file" name="upimg" accept="image/*">
    　<input type="submit">
    </form>

     
  </body>
</html>