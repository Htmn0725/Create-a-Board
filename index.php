<?php

// メッセージを保存するファイルのパス設定
define( 'FILENAME', './message.txt');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

if( !empty($_POST['btn_submit']) ) {
	
	if( $file_handle = fopen( FILENAME, "a") ) {

	    // 日時を取得
		$now_date = date("Y-m-d H:i:s");
	
		// 書き込むデータを作成
		$data = "'".$_POST['view_name']."','".$_POST['message']."','".$now_date."'\n";
	
		// 書き込み
		fwrite( $file_handle, $data);
	
		// ファイルを閉じる
		fclose( $file_handle);
	}		
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ひとり言掲示板</title>
<link href="./create-a-board.css" rel="stylesheet">
</head>
<body>
<h1>ひとり言掲示板</h1>
<form method="post">
  <div>
    <label for="view_name">name</label>
    <input id="view_name" type="text" name="view_name" value="">
  </div>
  <div>
    <label for="message">message</label>
    <textarea id="message" name="message"></textarea>
  </div>
  <input type="submit" name="btn_submit" value="go!">
</form>
<hr>
<section>
<!-- ここに投稿されたメッセージを表示 -->
</section>
</body>
</html>