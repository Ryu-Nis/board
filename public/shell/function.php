<?php

function insertDB($sql){
    $error_message[] = array();

    // データベースに接続
    include 'connectDB.php';

    // 接続エラーの確認
    if( $mysqli->connect_errno ) {
        $error_message[] = '書き込みに失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
        // return $error_message[];
    } else {
        // 文字コード設定
        $mysqli->set_charset('utf8');
        
        // データを登録
        $res = $mysqli->query($sql);

        if( $res ) {
            $_SESSION['success_message'] = 'メッセージを書き込みました。';

            //INSERTが成功したらキャッシュファイルを更新するように、キャッシュファイルを削除する
            // 変数で処理したいが、、、ファイルが違う時のうまい受け渡しを知りたい＜質問＞
            @unlink('/var/www/public/cache/cache.csv');

        } else {
            $error_message[] = '書き込みに失敗しました。';
            // return $error_message[];<!-- エラーだった場合、error_messageの返し方は？＜質問＞ -->
        }
        // データベースの接続を閉じる
        $mysqli->close();
    }
}

function selectDB($sql,$cache_file){
    // @unlink($cache_file);
    //キャッシュファイルが古ければ、DBからデータを取得する
    include 'connectDB.php';
    // 接続エラーの確認
    if( $mysqli->connect_errno ) {
        $error_message[] = 'データの読み込みに失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
    } else {
        $res = $mysqli->query($sql);
		if( $res ) {
		$message_array = $res->fetch_all(MYSQLI_ASSOC);
        }
        $mysqli->close();

        foreach($message_array as $value){
            $view_html .= $value["view_name"].",";
            $view_html .= $value["message"].",";
            $view_html .= $value["post_date"];
            $view_html .= "\r\n";	
        }
        //ファイル出力
        $result = @file_put_contents($cache_file,$view_html);
        return $view_html;
    }
}


function confirmCacheFile($cache_file){
    if(file_exists($cache_file)){
        // ファイルのタイムスタンプを１分前と比較し、古ければ削除
        if(filemtime($cache_file) < strtotime(date("Y-m-d H:i:s",strtotime("-1 minute")))){
        @unlink($cache_file);
        }
    }
}
?>

