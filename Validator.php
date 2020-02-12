<?php

class Validator
{
    private $data;
    private $errors = [];
    private static $fields = ['view_name', 'message'];

    public function __construct($post_data)
    {
        $this->data = $post_data;
    }

    public function validateForm()
    {
        foreach (self::$fields as $field) {
            if (!array_key_exists($field, $this->data)) {
                trigger_error("$field is not present in data");

                return;
            }
        }

        $this->validateViewName();
        $this->validateMessage();

        return $this->errors;
    }

    private function validateViewName()
    {
        $val = trim($this->data['view_name']);

        $clean['view_name'] = htmlspecialchars($this->data['view_name'], ENT_QUOTES);
        $clean['view_name'] = preg_replace('/\\r\\n|\\n|\\r/', '',
        $clean['view_name']);

        if (empty($clean['view_name'])) {
            $this->addError('view_name', 'Please enter name');
        } else {
            // 	セッションにnameを保存
            $_SESSION['view_name'] = $clean['view_name'];
        }
    }

    private function validateMessage()
    {
        $clean['message'] = htmlspecialchars($this->data['message'], ENT_QUOTES);
        if (empty($clean['message'])) {
            $this->addError('message', 'Please enter message.');
        }
    }

    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }
}
