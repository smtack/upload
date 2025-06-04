<?php
class Redirect {
  public static function to($location = null) {
    if($location) {
      if(is_numeric($location)) {
        switch($location) {
          case 404:
            header('HTTP/1.0 404 Not Found');

            include_once __DIR__ . '/../../public/errors/404.php';

            exit();
          break;
        }
      } else {
        header('Location: ' . $location);
        
        exit();
      }
    }
  }
}