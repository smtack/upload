<?php

namespace Core;

use DateTime;

class Date
{
    public static function format(string $timestamp): string
    {
        return date('l j F Y \a\t H:i', strtotime(escape($timestamp)));
    }

    public static function timeAgo(string $timestamp): string
    {
        $now = new DateTime;
        $ago = new DateTime($timestamp);

        $diff = $now->diff($ago);

        $weeks = floor($diff->d / 7);
        $days = $diff->d % 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second'
        );

        $output = [];

        if($diff->y) {
            $output['y'] = $diff->y . ' ' . $string['y'] . ($diff->y > 1 ? 's' : '');
        } else if($diff->m) {
            $output['m'] = $diff->m . ' ' . $string['m'] . ($diff->m > 1 ? 's' : '');
        } else if($weeks) {
            $output['w'] = $weeks . ' ' . $string['w'] . ($weeks > 1 ? 's' : '');
        } else if($days) {
            $output['d'] = $days . ' ' . $string['d'] . ($days > 1 ? 's' : '');
        } else if($diff->h) {
            $output['h'] = $diff->h . ' ' . $string['h'] . ($diff->h > 1 ? 's' : '');
        } else if($diff->i) {
            $output['i'] = $diff->i . ' ' . $string['i'] . ($diff->i > 1 ? 's' : '');
        } else if($diff->s) {
            $output['s'] = $diff->s . ' ' . $string['s'] . ($diff->s > 1 ? 's' : '');
        }

        return $output ? implode(', ', $output) . ' ago' : 'just now';
    }
}
