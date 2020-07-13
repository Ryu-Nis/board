<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="SJIS-win">
    <title>ログイン</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
  </head>
    <title>ログインユーザ追加用の入力画面</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
  <h1>ログインユーザ追加用の入力画面</h1>

    <!-- 操作後の成功・失敗メッセージ -->
    <?php if( empty($_POST['btn_submit']) && !empty($_SESSION['success_message']) ): ?>
      <p class="success_message"><?php echo $_SESSION['success_message']; ?></p>
    <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <?php if( !empty($error_message) ): ?>
    <ul class="error_message">
      <?php foreach( $error_message as $value ): ?>
        <li>・<?php echo $value; ?></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form action="adduser.php" method="post">
      <table>
        <tr>
          <td>NAME</td>
          <td><input type="text" name="name" id="name"></td>
        </tr>
        <tr>
          <td>PASSWORD</td>
          <td><input type="password" name="password" id="pass"></td>
        </tr>
      </table>
      <p><div id="attention"></div></p>
      <p><div id="attention1"></div></p>
      <p><div id="attention2"></div></p>
      <br>
      <p><input type="submit" name="btn_submit" value="追加"></p>
    </form>
    <br>

<!-- 非同期処理：パスワードの長さチェック-->
<script>
$(function(){
  if ($("#pass").val().length == 0) {
    var MSG = document.getElementById("attention");
    MSG.innerHTML = "IDとパスワードを入力してください";
  } 
//ID欄
  $("#name").on("keydown keyup keypress change", function() { 
    if ($("#name").val().length < 1 || $("#pass").val().length < 1) {
       MSG.innerHTML = "IDとパスワードを入力してください";
    } else {
      if($("#pass").val().length < 8){
        MSG.innerHTML = "8文字以上のパスワードを推奨します";
      }else{
        MSG.innerHTML = "安全なパスワードです";
      }
    }
  });

  $("#pass").on("keydown keyup keypress change", function() { 
    if ($("#name").val().length < 1 || $("#pass").val().length < 1) {
      MSG.innerHTML = "IDとパスワードを入力してください";
    } else {
      if($("#pass").val().length < 8){
        MSG.innerHTML = "8文字以上のパスワードを推奨します";
      }else{
        MSG.innerHTML = "OK";
      }
    }
  });
});
</script>

<?php
$name = filter_input(INPUT_POST, 'name');
//入力文字をエスケープしてエスケープされればエラーにする
$name_esc = addslashes($name);
if(empty($name_esc)){
    // print('新しく登録する名前を入力してください<br></a>');
    $mysqli->close();
    exit();
}
if($name != $name_esc){
    ?>
  　<ul class="error_message">
    使用できない文字（\',\\,NULL,"）が含まれています。<br>
    </ul>
    <?php
    $mysqli->close();
    exit();
}
$password = filter_input(INPUT_POST, 'password');
if(empty($password)){
    ?>
  　<ul class="error_message">
    PASSWORDが入力されていません。<br>
    </ul>
    <?php
    $mysqli->close();
    exit();
}
$hashpass = password_hash($password, PASSWORD_DEFAULT);

include 'shell/connectDB.php';
$sql = "INSERT INTO users (name, password) VALUES ('$name_esc','$hashpass')";
$result = $mysqli->query($sql);

if( $result ) {
    $_SESSION['success_message'] = $name_esc.'さんのユーザー登録が完了しました。';?>
    <p class="success_message"><?php echo $_SESSION['success_message']; ?></p>
    <?php printf("<br><a href='login.php'>ログイン画面へ</a>");
} else {
    $error_message[] = '書き込みに失敗しました。';
    print('クエリーが失敗しました。' . $mysqli->error.'<br>');
}
// データベースの接続を閉じる
$mysqli->close();
?>


</body>
</html>

