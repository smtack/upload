<?php
require_once 'src/init.php';

$user = new User();
$upload = new Upload();

if(!$username = Input::get('user')) {
  Redirect::to(BASE_URL);
} else {
  if(!$profile = $user->getProfile($username)) {
    Redirect::to(404);
  } else {
    $users_uploads = $upload->getUsersUploads($profile->user_id);
  }
}

$page_title = "Upload - " . $profile->user_name . "'s Profile";

$img_exts = array('jpg', 'png', 'PNG', 'gif');

require VIEW_ROOT . '/profile.php';