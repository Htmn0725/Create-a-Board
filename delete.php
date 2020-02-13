<?php

require 'Database.php';
require 'Validator.php';

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

$db = new Database();

if( !empty($_GET['message_id']) && empty($_POST['message_id']) ){

	$message_id = (int)htmlspecialchars($_GET['message_id'],ENT_QUOTES);

	$message_date = $db->select_message($message_id);
}
elseif( !empty($_POST['message_id'])){

	$message_id = (int)htmlspecialchars($_POST['message_id'],ENT_QUOTES);

	$db->delete($message_id);
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
	if( !empty($message_date['view_name']) ){ echo $message_date['view_name'];}?>" disabled>
  </div>
  <div>
    <label for="message">message</label>
    <textarea id="message" name="message" disabled><?php if(!empty($message_date['message']) ){ echo $message_date['message'];}?></textarea>
  </div>
  <a class="btn_cancel" href="index.php">cancel</a>
  <input type="submit" name="btn_submit" value="Delete!">
  <input type="hidden" name="message_id" value="<?php echo
  $message_id; ?>">
</form>
</body>
</html>