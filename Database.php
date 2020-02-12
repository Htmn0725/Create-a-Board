<?php

// DB接続情報
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'board');

class Database
{
    //共通処理は一箇所にする。
    public function __construct()
    {
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->mysqli->set_charset('utf8');

        if ($this->mysqli->connect_errno) {
            return  'fail to read. erro no. '.$this->mysqli->connect_errno.' : '.$this->mysqli->connect_error;
        }
    }

    //一覧
    public function select()
    {
        $sql = 'SELECT id, view_name, message, post_date FROM message ORDER BY post_date DESC';

        $res = $this->mysqli->query($sql);

        if ($res) {
            $message_array = $res->fetch_all(MYSQLI_ASSOC);
        }

        return $message_array;
    }

    //一覧(編集、削除画面)
    public function select_message($message_id)
    {
        $sql = "SELECT view_name, message FROM message WHERE id = '$message_id'";

        $res = $this->mysqli->query($sql);

        if( $res ){
            $message_array = $res->fetch_assoc();
        }
        else{
            // データが読み込めなければメインページに戻る
            return header("Location: ./index.php");
        }

        return $message_array;
    }

    //登録
    public function insert($view_name, $message)
    {
        // 現在の日時を取得
        $now_date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO message (view_name, message, post_date)
			VALUES ('$view_name', '$message', '$now_date')";

        $res = $this->mysqli->query($sql);

        if ($res) {
            $success_message = 'Success!';

            return $success_message;
        }

        $error_message[] = 'fail to write message';

        return $error_message;
    }

    //更新
    public function update($view_name, $message, $message_id)
    {
        $sql = "UPDATE message set view_name ='$view_name', message = '$message' WHERE id = $message_id";

        $res = $this->mysqli->query($sql);

        if ($res) {
			return header("Location: ./index.php");
        }
    }

    //削除
    public function delete($message_id)
    {
        $sql = "DELETE FROM message WHERE id = $message_id";
        $res = $this->mysqli->query($sql);

        if( $res ){
           return header("Location: ./index.php");
        }

        $error_message[] = 'fail to delete message';

        return $error_message;
    }

    public function __destruct()
    {
        $this->mysqli->close();
    }
}
