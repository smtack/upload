<?php
require_once '../src/init.php';

$user = new User();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
}

if(Input::exists($_REQUEST, 'f')) {
  $file = urldecode(Input::get('f'));

  if(preg_match('([\w ]+)', $file)) {
    $path = "../uploads/uploads/" . $file;

    if(!File::downloadFile($path)) {
      http_response_code(404);

      Redirect::to(404);
    }
  }
}