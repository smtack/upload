<?php
class Hash {
  public static function make($string) {
    return hash('sha256', $string);
  }

  public static function random() {
    return bin2hex(random_bytes(256));
  }

  public static function createPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
  }

  public static function verifyPassword($password, $hash) {
    return (password_verify($password, $hash)) ? true : false;
  }
}