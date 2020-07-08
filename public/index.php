<?php
// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');
//キャッシュファイルの設定
$cache_file = dirname(__FILE__).'/cache/cache.csv';
//関数ファイルの呼び出し
require_once(dirname(__FILE__)."/shell/function.php");
// 変数の初期化
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$clean = array();

session_start();

if( !empty($_POST['btn_submit']) ) {
    // 表示名の入力チェック
	if( empty($_POST['view_name']) ) {
		$error_message[] = '表示名を入力してください。';
    }else {
		$clean['view_name'] = htmlspecialchars( $_POST['view_name'], ENT_QUOTES);
        $clean['view_name'] = preg_replace( '/\\r\\n|\\n|\\r/', '', $clean['view_name']);

        // セッションに表示名を保存
		$_SESSION['view_name'] = $clean['view_name'];
    }
     // メッセージの入力チェック
	if( empty($_POST['message']) ) {
		$error_message[] = '内容を入力してください。';
    }else {
        $clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES);
	}
    if(empty($error_message)){
		$now_date = date("Y-m-d H:i:s");
        $sql = "INSERT INTO message (view_name, message, post_date) VALUES ( '$clean[view_name]', '$clean[message]', '$now_date')";
		insertDB($sql);
		//再度読み込んで、最新情報にしたい（キャッシュファイルの読み込み）
		header('Location: ./');
	}	
}

//データファイルを読み込んで、表示する
// キャッシュファイル存在確認→古ければ削除
confirmCacheFile($cache_file);
$view_html = @file_get_contents($cache_file);

if (!$view_html){
	// //キャッシュファイルが古ければ、DBからデータを取得する
	$sql = "SELECT view_name,message,post_date FROM message ORDER BY post_date DESC limit 5";
	$view_html = selectDB($sql,$cache_file);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>QA</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>書き込み</h1>
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
<form method="post">
	<div>
		<label for="view_name">表示名</label>
		<input id="view_name" type="text" name="view_name" 
        value="<?php if( !empty($_SESSION['view_name']) ){ echo $_SESSION['view_name']; } ?>">
	</div>
	<div>
		<label for="message">入力内容</label>
		<textarea id="message" name="message"></textarea>
	</div>
	<input type="submit" name="btn_submit" value="書き込む">
</form>
<hr>

<section>

<!-- キャッシュファイルから情報を取得する -->
<?php
		// 読み込み用にtest.csvを開きます。
		$f = fopen($cache_file, "r");
		// test.csvの行を1行ずつ読み込みます。
		while($value = fgetcsv($f)){
			// 読み込んだ結果を表示します。
?>
	<article>
		<div class="info">
			<h2><?php echo $value[0]; ?></h2>
			<time><?php echo date('Y年m月d日 H:i', strtotime($value[2])); ?></time>
		</div>
		<p><?php echo nl2br($value[1]); ?></p>
	</article>
<?php
		}
		// test.csvを閉じます。
		fclose($f);
?>
</section>
<div><input type="submit" id="aaa" value="全件表示する"></div>
<div id="test"></div>

<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <script>
      $(function() {
        $('#aaa').click(
          function() {
			$.ajax({
				type: 'GET',
				url:  './cache/cache.csv',
				// data: './sample2.html',
			}).done(function(data){
				<?php 
					// @unlink($cache_file); 
					$now_date = date("Y-m-d H:i:s");
					$sql = "SELECT view_name,message,post_date FROM message ORDER BY post_date DESC";
					$view_html = selectDB($sql,$cache_file);?>
					// $('#test').html(data);
				// $('#text').html(data);
			}).fail(function(data){
				alert('error');
			});
             
          }
		

        //     $.ajax({
        //       url: './sample2.html',
        //       dataType: 'html',
        //       success: function(data) {
        //         $('#text').html(data);
        //       },
        //       error: function(data) {
        //         alert('error');
        //       }
             
        //   	})
		//   }	



        );
      });
    </script>

</body>
</html>


<!-- ・入れたい処理
・非同期処理
・クッキーを利用する
・キャッシュを利用する
・画像アップロード
・バリデーション（正規表現）
・スクレイピング
・PDFファイル系の出力
・ユニットテスト（実運用） 
-->