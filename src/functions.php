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

// Print time ago string

function timeAgo($timestamp, $full = false) {
  $now = new DateTime;
  $ago = new DateTime($timestamp);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
    'y' => 'year',
    'm' => 'month',
    'w' => 'week',
    'd' => 'day',
    'h' => 'hour',
    'm' => 'minute',
    's' => 'second'
  );

  foreach($string as $key => &$value) {
    if($diff->$key) {
      $value = $diff->$key . ' ' . $value . ($diff->$key > 1 ? 's' : '');
    } else {
      unset($string[$key]);
    }
  }

  if(!$full) $string = array_slice($string, 0, 1);

  return $string ? implode(', ', $string) . ' ago' : 'just now';
}