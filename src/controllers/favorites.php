<?php

$user = new Models\User();
$upload = new Models\Upload();

if(!$user->loggedIn()) {
    Redirect::to(BASE_URL);
} else {
    $uploads = $upload->getUsersFavorites($user->data()->user_id);
}

require VIEW_ROOT . '/favorites.php';
