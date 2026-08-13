<?php

use Core\Redirect;
use Core\Router;
use Core\Input;
use Core\File;

$user = new Models\User();

if(!$user->loggedIn()) {
    Redirect::to(BASE_URL);
}

if(Input::exists($_REQUEST, 'f')) {
    $file = urldecode(Input::get('f'));

    if(preg_match('([\w ]+)', $file)) {
        $path = "uploads/uploads/" . $file;

        if(!File::downloadFile($path)) {
            Router::abort(404);
        }
    }
}
