<?php

use Core\Input;
use Core\Redirect;
use Core\Router;

$user = new Models\User();
$upload = new Models\Upload();

if(!$username = Input::get('u')) {
    Redirect::to(BASE_URL);
} else {
    if(!$profile = $user->getProfile($username)) {
        Router::abort(404);
    } else {
        $follows_data = $user->getFollowsData($profile->user_id);
        $users_uploads = $upload->getUsersUploads($profile->user_id);
    }
}

$page_title = "Upload - " . $profile->user_name . "'s Profile";

require VIEW_ROOT . '/profile.php';
