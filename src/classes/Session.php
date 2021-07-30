<?php
class Session {
  public static function exists($name) {
    return (isset($_SESSION[$name])) ? true : false;
  }

  public static function set($name, $value) {
    return $_SESSION[$name] = $value;
  }

  public static function get($name) {
    return $_SESSION[$name];
  }

  public static function unset($name) {
    if(self::exists($name)) {
      unset($_SESSION[$name]);
    }
  }
}