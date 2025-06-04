<?php
class Hash {
  public static function make($string) {
    return hash('sha256', $string);
  }

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
    return Session::set($name, self::make(self::random(256)));
  }

  public static function checkToken($token, $name) {
    if(Session::exists($name) && $token === Session::get($name)) {
      Session::unset($name);

      return true;
    }

    return false;
  }

  public static function createUniqueFilename($user, $filename) {
    $hash_username = self::make($user);

    $hash_filename = self::make($filename);

    $unique_filename = $hash_username . $hash_filename . uniqid();

    return $unique_filename;
  }
}