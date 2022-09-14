<?php
class Input {
  public static function exists($type, $form) {
    switch($type) {
      case $_POST:
        return (!empty($_POST[$form])) ? true : false;
      break;
      case $_GET:
        return (!empty($_GET[$form])) ? true : false;
      break;
      case $_REQUEST:
        return (!empty($_REQUEST[$form])) ? true : false;
      default:
        return false;
      break;
    }
  }

  public static function get($item) {
    if(isset($_POST[$item])) {
      return $_POST[$item];
    } else if(isset($_GET[$item])) {
      return $_GET[$item];
    } else if(isset($_REQUEST[$item])) {
      return $_REQUEST[$item];
    }

    return '';
  }
}