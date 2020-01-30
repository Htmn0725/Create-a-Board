<?php

// メッセージを保存するファイルのパス設定
define( 'FILENAME', './message.txt');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 初期化
$error_message = array();
$clean = array();
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;

if( !empty($_POST['btn_submit']) ) {

	// 入力チェック
	// name
	if( empty($_POST['view_name']) ){
		$error_message[] = 'Please enter name.';
	}
	else{
		$clean['view_name'] = htmlspecialchars( $_POST['view_name'], ENT_QUOTES);
		$clean['view_name'] = preg_replace( '/\\r\\n|\\n|\\r/', '', 
		$clean['view_name']);
	}
	
	// message
	if( empty($_POST['message']) ){
		$error_message[] = 'Please enter message.';
	}
	else{
		$clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES);
		$clean['message'] = preg_replace( '/\\r\\n|\\n|\\r/', '<br>', 
		$clean['message']);
	}

	if( empty($error_message) ){
		// nameとmessageを登録
		if( $file_handle = fopen( FILENAME, "a") ) {

			// 現在の日時を取得
			$now_date = date("Y-m-d H:i:s");
		
			// 書き込むデータを作成
			$data = "'".$clean['view_name']."','".$clean['message']."','".$now_date."'\n";
		
			fwrite( $file_handle, $data);
		
			fclose( $file_handle);

			$success_message = 'Success!';
		}
	}		
}

if( $file_handle = fopen( FILENAME, "r")){

	while( $data = fgets( $file_handle) ){
		$split_data = preg_split( '/\'/', $data);

		$message = array(
			'view_name' => $split_data[1],
			'message' => $split_data[3],
			'post_date' => $split_data[5]
		);
		
		array_unshift( $message_array, $message);
	}

	fclose( $file_handle);
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
<?php if( !empty($success_message) ): ?>
	<p class="success_message"><?php echo $success_message; ?></p>
<?php endif;?>
<?php if( !empty($error_message) ): ?>
	<ul class="error_message">
	  <?php foreach( $error_message as $value ): ?>
		<li>・<?php echo $value; ?></li>
	  <?php endforeach; ?>
	</ul>
<?php endif;?>
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
<?php if( !empty($message_array) ): ?>
<?php foreach( $message_array as $value ):?>
<article>
	<div class="info">
		<h2><?php echo $value['view_name']; ?></h2>
		<time><?php echo date('Y年m月d日　H:i',
		strtotime($value['post_date'])); ?></time>
	</div>
	<p><?php echo $value['message']; ?></p>
</article>
<?php endforeach; ?>
<?php endif; ?>
</section>
</body>
</html>