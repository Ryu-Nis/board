<?php
// $jpeg_name = 'image.jpeg';
 
// /// 保存したい画像バイナリ（仮）
// $jpeg_data = hex2bin("FFD8DDE0...");
// /// データカラム用のダミーデータ
// $DUMMY_NULL = null;
 
// $mysqli = new mysqli( 'mysql', 'root', 'root', 'board');

// $sql = "INSERT INTO thumbnails (data, name) VALUES (?, ?)";

// $stmt = $mysqli->prepare( $sql );
// // $stmt->bind_param('bs', $DUMMY_NULL, $jpeg_name);
// // $stmt->send_long_data( 0, $jpeg_data );
// $stmt->execute();
// $stmt->close();


// ドライバ呼び出しを使用して MySQL データベースに接続します
$dsn = 'mysql:dbname=board;host=127.0.0.1;charset=utf8;port=8081';
$user = 'root';
$password = 'root';

try {
    $dbh = new PDO($dsn, $user, $password);
    echo "接続成功\n";
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage() . "\n";
    exit();
}


?>