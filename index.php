<?php

// DB接続情報
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','board');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

session_start();


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

		// 	セッションにnameを保存
		$_SESSION['view_name'] = $clean['view_name'];
	}
	
	// message
	if( empty($_POST['message']) ){
		$error_message[] = 'Please enter message.';
	}
	else{
		$clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES);
	}
	
	if( empty($error_message) ){
		// DBに接続
		$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if( $mysqli->connect_errno ){
			$error_message[] = 'fail to write. erro no. '.$mysqli
			->connect_errno.' : '.$mysqli->connect_error;
		}
		else{
			// 文字コード
			$mysqli->set_charset('utf8');

			// 現在の日時を取得
			$now_date = date("Y-m-d H:i:s");

			$sql = "INSERT INTO message (view_name, message, post_date)
			VALUES ('$clean[view_name]', '$clean[message]', '$now_date')";

			$res = $mysqli->query($sql);

			if( $res ){
				$success_message = 'Success!';
			}
			else{

				$error_message[] = 'fail to write message';
			}

			// DBの接続を閉じる
			$mysqli->close();
		}
	}		
}

// DBに接続
$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if( $mysqli->connect_errno ){
	$error_message[] = 'fail to read. erro no. '.$mysqli
	->connect_errno.' : '.$mysqli->connect_error;
}
else{
	// 文字コード
	$mysqli->set_charset('utf8');

	$sql = "SELECT id, view_name, message, post_date FROM message 
	ORDER BY post_date DESC";

	$res = $mysqli->query($sql);

	if( $res ){
		$message_array = $res->fetch_all(MYSQLI_ASSOC);
	}

	// DBの接続を閉じる
	$mysqli->close();

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
	<input id="view_name" type="text" name="view_name" value="<?php 
	if( !empty($_SESSION['view_name'])){ echo $_SESSION['view_name']; } ?>">
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
		<p><a href="edit.php?message_id=<?php echo $value['id']; ?>">edit</a> 
		<a href="delete.php?message_id=<?php echo $value['id']; ?>">delete</a></p>
	</div>
	<p><?php echo nl2br($value['message']); ?></p>
</article>
<?php endforeach; ?>
<?php endif; ?>
</section>
</body>
</html>