<?php
require_once 'config.php';
require_once 'functions.php';

spl_autoload_register(function($class) {
  require_once 'classes/' . $class . '.php';
});

// set_error_handler('errorHandler');
ini_set('display_errors', 'on');
error_reporting(E_ALL);

session_start();

$GLOBALS['config'] = array(
  'mysql' => array(
    'dbhost' => DB_HOST,
    'dbname' => DB_NAME,
    'dbuser' => DB_USER,
    'dbpass' => DB_PASS,
    'dbchar' => DB_CHAR
  ),
  'remember' => array(
    'cookie_name' => 'session_hash',
    'cookie_expiry' => 604800
  ),
  'session' => array(
    'session_name' => 'user',
    'token_name' => 'token'
  )
);

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
  $hash = Cookie::get(Config::get('remember/cookie_name'));
  $hash_check = Database::getInstance()->select('users_session', array(), array('session_hash', '=', $hash));

  if($hash_check->count()) {
    $user = new User($hash_check->first()->user_id);
    $user->login();
  }
}

$self = $_SERVER['PHP_SELF'];

$image_extensions = array(
  'jpg',
  'png',
  'PNG',
  'gif'
);