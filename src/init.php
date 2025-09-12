<?php
require_once 'config.php';
require_once 'functions.php';

spl_autoload_register(function($class) {
  require_once 'classes/' . $class . '.php';
});

// Error Reporting
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('log_errors', 'on');

error_reporting(E_ALL);
// set_error_handler('errorHandler');

date_default_timezone_set('Europe/London');

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
  'jpeg',
  'JPEG',
  'png',
  'PNG',
  'gif',
);

$allowed_upload_extensions = array(
  'mp4',
  'jpg',
  'jpeg',
  'JPEG',
  'png',
  'PNG',
  'gif',
);