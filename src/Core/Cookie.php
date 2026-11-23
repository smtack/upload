<?php

namespace Core;

class Cookie
{
    public static function exists($name)
    {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    public static function set($name, $value, $options = [])
    {
        if (setcookie($name, $value, $options)) {
            return true;
        }

        return false;
    }

    public static function get($name)
    {
        return $_COOKIE[$name];
    }

    public static function delete($name)
    {
        self::set($name, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}