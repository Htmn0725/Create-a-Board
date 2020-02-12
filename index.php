<?php

require 'Database.php';
require 'Validator.php';

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

session_start();

$db = new Database();

if (!empty($_POST['btn_submit'])) {
    $validation = new Validator($_POST);
    $error_message = $validation->validateForm();

    if (empty($error_message)) {
        $db->insert($_POST['view_name'], $_POST['message']);
    }
}

$message_array = $db->select();

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
<?php if (!empty($success_message)): ?>
	<p class="success_message"><?php echo $success_message; ?></p>
<?php endif; ?>
<?php if (!empty($error_message)): ?>
	<ul class="error_message">
	  <?php foreach ($error_message as $value): ?>
		<li>・<?php echo $value; ?></li>
	  <?php endforeach; ?>
	</ul>
<?php endif; ?>
<form method="post">
  <div>
    <label for="view_name">name</label>
	<input id="view_name" type="text" name="view_name" value="<?php
    if (!empty($_SESSION['view_name'])) {
        echo $_SESSION['view_name'];
    } ?>">
  </div>
  <div>
    <label for="message">message</label>
    <textarea id="message" name="message"></textarea>
  </div>
  <input type="submit" name="btn_submit" value="go!">
</form>
<hr>
<section>
<?php if (!empty($message_array)): ?>
<?php foreach ($message_array as $value):?>
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