<?php

// DB接続情報
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','board');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// DBへ接続
$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if( $mysqli->connect_errno){
	$error_message[] = 'データベースの接続に失敗しました。　エラー番号'
	.$mysqli->connect_errno.' : '.$mysqli->connect_error;

	return;
}

if( !empty($_GET['message_id']) && empty($_POST['message_id']) ){

	$message_id = (int)htmlspecialchars($_GET['message_id'],
					ENT_QUOTES);

	// データの読み込み
	$sql = "SELECT * FROM message WHERE id = $message_id";
	$res = $mysqli->query($sql);

	if( $res ){
		$message_date = $res->fetch_assoc();
	}
	else{
		// データが読み込めなければメインページに戻る
		header("Location: ./index.php");
	}

	$mysqli->close();
}
elseif( !empty($_POST['message_id'])){

	$message_id = (int)htmlspecialchars($_POST['message_id'],
	ENT_QUOTES);

	$sql = "DELETE FROM message WHERE id = $message_id";
	$res = $mysqli->query($sql);

	if( $res ){
		header("Location: ./index.php");
	}

}



?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ひとり言掲示板 削除ページ</title>
<link href="./create-a-board.css" rel="stylesheet">
</head>
<body>
<h1>ひとり言掲示板 削除ページ</h1>
<?php if( !empty($error_message) ): ?>
	<ul class="error_message">
	  <?php foreach( $error_message as $value ): ?>
		<li>・<?php echo $value; ?></li>
	  <?php endforeach; ?>
	</ul>
<?php endif;?>
<p class="text-confirm">Are you sure to delete this post? <br> Please enter 'Delete!' button.</p>
<form method="post">
  <div>
    <label for="view_name">name</label>
	<input id="view_name" type="text" name="view_name" value="<?php 
	if( !empty($message_date['view_name']) ){ echo $message_date['view_name'];
	}?>" disabled>
  </div>
  <div>
    <label for="message">message</label>
    <textarea id="message" name="message" disabled>
	<?php if( !empty($message_date['message']) ){ echo $message_date['message']; }?>
	</textarea>
  </div>
  <a class="btn_cancel" href="index.php">cancel</a>
  <input type="submit" name="btn_submit" value="Delete!">
  <input type="hidden" name="message_id" value="<?php echo 
  $message_date['id']; ?>">
</form>
</body>
</html>