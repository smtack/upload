<?php

use Core\Cookie;
use Core\Config;
use Core\Session;
use Core\Database;
use Core\Router;

use Models\User;

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . '/config/config.php';
require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/src/functions.php';

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('log_errors', 'on');

date_default_timezone_set('Europe/London');

session_start();

$image_extensions = [
    'jpg',
    'jpeg',
    'JPEG',
    'png',
    'PNG',
];

$allowed_upload_extensions = [
    'mp4',
    'webm',
    'jpg',
    'jpeg',
    'JPEG',
    'png',
    'PNG',
];

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
    ),
    'image_extensions' => $image_extensions,
    'allowed_upload_extensions' => $allowed_upload_extensions
);

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hash_check = Database::getInstance()->select('users_session', array(), array('session_hash', '=', $hash));

    if($hash_check->count()) {
        $user = new User($hash_check->first()->user_id);
        $user->login();
    }
}

$routes = require base_path('src/routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

Router::route($uri, $routes);
