<?php

$user = new Models\User();

if($user->loggedIn()) {
    $follows = $user->getUsersFollows($user->data()->user_id);
} else {
    Redirect::to(BASE_URL);
}

require VIEW_ROOT . '/follows.php';
