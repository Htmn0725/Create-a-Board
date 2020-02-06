<?php

// DB接続情報
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','board');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

session_start();

if( !empty($_GET['message_id']) && empty($_POST['message_id']) ){

	$message_id = (int)htmlspecialchars($_GET['message_id'],
ENT_QUOTES);
}
elseif( !empty($_POST['message_id'])){

	$message_id = (int)htmlspecialchars($_POST['message_id'],
	ENT_QUOTES);

	// 入力チェック
	// name
	if( empty($_POST['view_name']) ){
		$error_message[] = 'Please enter name.';
	}
	else{
		$message_date['view_name'] = htmlspecialchars( $_POST['view_name'], 
		ENT_QUOTES);
	}
	
	// message
	if( empty($_POST['message']) ){
		$error_message[] = 'Please enter message.';
	}
	else{
		$message_date['message'] = htmlspecialchars( $_POST['message'], 
		ENT_QUOTES);
		}
	
	if( empty($error_message) ){
		// DBに接続
		$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if( $mysqli->connect_errno ){
			$error_message[] = 'fail to write. erro no. '.$mysqli
			->connect_errno.' : '.$mysqli->connect_error;
		}
		else{

			// 現在の日時を取得
			$now_date = date("Y-m-d H:i:s");

			$sql = "UPDATE message 
					   set view_name = '$message_date[view_name]', 
							message = '$message_date[message]'
						WHERE id = $message_id";

			$res = $mysqli->query($sql);
		}

		// DBの接続を閉じる
		$mysqli->close();

		if( $res ){
			header("Location: ./index.php");
		}
	}
}
// DBへ接続
$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if( $mysqli->connect_errno){
	$error_message[] = 'データベースの接続に失敗しました。　エラー番号'
	.$mysqli->connect_errno.' : '.$mysqli->connect_error;
}
else{
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

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ひとり言掲示板 編集ページ</title>
<link href="./create-a-board.css" rel="stylesheet">
</head>
<body>
<h1>ひとり言掲示板 編集ページ</h1>
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
	if( !empty($message_date['view_name']) ){ echo $message_date['view_name'];
	}?>">
  </div>
  <div>
    <label for="message">message</label>
    <textarea id="message" name="message">
	<?php if( !empty($message_date['message']) ){ echo $message_date['message']; }?>
	</textarea>
  </div>
  <a class="btn_cancel" href="index.php">cancel</a>
  <input type="submit" name="btn_submit" value="fix!">
  <input type="hidden" name="message_id" value="<?php echo 
  $message_date['id']; ?>">
</form>
</body>
</html>