<?php
// Sanitize user input

function escape($string) {
  return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

// Custom error page

function errorHandler() {
  if(error_reporting()) {
    require_once __DIR__ . '/../public/errors/error.php';

    exit();
  }
}

// Check for a value in a multidimensional array

function findValue($array, $key, $value) {
  foreach($array as $item) {
    if(is_array($item) && findValue($item, $key, $value)) {
      return true;
    }

    if(isset($item[$key]) && $item[$key] == $value) {
      return true;
    }
  }

  return false;
}

// Check if file is image or video

function is_video($file) {
  $extension = explode('.', strtolower($file));

  if(count(array_intersect($extension, ['mp4'])) > 0) {
    return true;
  } else {
    return false;
  }
}