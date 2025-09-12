<?php
class Hash {
  public static function random($length) {
    return bin2hex(random_bytes($length));
  }

  public static function createPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
  }

  public static function verifyPassword($password, $hash) {
    return (password_verify($password, $hash)) ? true : false;
  }

  public static function generateToken($name) {
    return Session::set($name, self::random(64));
  }

  public static function checkToken($token, $name) {
    if(Session::exists($name) && hash_equals(Session::get($name), $token)) {
      Session::unset($name);

      return true;
    }

    return false;
  }

  public static function createUniqueFilename() {
    $filename = 'upload_' . time() . self::random(16);

    return $filename;
  }
}