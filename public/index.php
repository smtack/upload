<?php

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

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
//ini_set('session.cookie_secure', '1');

session_start();

date_default_timezone_set('Europe/London');

$image_extensions = [
    'jpg',
    'jpeg',
    'JPEG',
    'png',
    'PNG',
    'avif',
];

$allowed_upload_extensions = [
    'mp4',
    'webm',
    'jpg',
    'jpeg',
    'JPEG',
    'png',
    'PNG',
    'avif',
];

$GLOBALS['config'] = [
    'mysql' => [
        'dbhost' => DB_HOST,
        'dbname' => DB_NAME,
        'dbuser' => DB_USER,
        'dbpass' => DB_PASS,
        'dbchar' => DB_CHAR
    ],
    'session' => [
        'session_name' => 'session_user',
        'token_name' => 'token'
    ],
    'remember' => [
        'cookie_name' => 'remember_user',
        'cookie_expiry' => 604800
    ],
    'image_extensions' => $image_extensions,
    'allowed_upload_extensions' => $allowed_upload_extensions
];

$user = new User();

if (!$user->loggedIn()) {
    $user->validateRememberToken();
}

$routes = require base_path('src/routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

Router::route($uri, $routes);
