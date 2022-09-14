<?php
require_once 'src/init.php';

$user = new User();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
}

if(Input::exists($_REQUEST, 'f')) {
  $file = urldecode(Input::get('f'));

  if(preg_match('([\w ]+)', $file)) {
    $path = "uploads/uploads/" . $file;

    if(file_exists($path)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="' . basename($path) . '"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: '. filesize($path));

      flush();

      readfile($path);

      die();
    } else {
      http_response_code(404);

      Redirect::to(404);
    }
  } else {
    http_response_code(404);

    Redirect::to(404);
  }
}