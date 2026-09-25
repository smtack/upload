<?php

use Core\Input;
use Core\Redirect;

$user = new Models\User();

if(!$user->loggedIn()) {
    Redirect::to(BASE_URL);
}

if(!$id = Input::get('u')) {
    Redirect::to(BASE_URL);
} else if($id === $user->data()->user_id) {
    Redirect::to(BASE_URL);
}

$followed_user = $user->getProfile($id);

$follows_data = $user->getFollowsData($followed_user->user_id);

$user_follows = findValue($follows_data, 'follow_user', $user->data()->user_id);

$data = [
    'follow_user' => $user->data()->user_id,
    'follow_following' => $id
];

if ($user_follows) {
    $user->unfollow($data);
} else {
    $user->follow($data);
}

Redirect::to(BASE_URL . '/profile?u=' . $followed_user->user_username);