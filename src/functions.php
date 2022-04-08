<?php
// Sanitize user input

function escape($string) {
  return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

// Handle PHP Errors

function errorHandler() {
  if(error_reporting()) {
    include_once 'public/views/errors/error.php';

    // die();
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