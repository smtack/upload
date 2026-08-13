<?php

// Sanitize database output
function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

// Die and Dump
function dd($value) {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
}

// Return Base Path
function base_path($path) {
    return BASE_PATH . $path;
}

// Return asset path
function asset($path) {
    return '/assets/' . $path;
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

    if(count(array_intersect($extension, ['mp4', 'webm'])) > 0) {
        return true;
    } else {
        return false;
    }
}
