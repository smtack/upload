<?php
require_once 'src/init.php';

$user = new User();
$upload = new Upload();

if(!$username = $_GET['user']) {
  Redirect::to(BASE_URL);
} else {
  $profile = new User($username);

  if(!$profile->exists()) {
    Redirect::to(404);
  } else {
    $user_data = $profile->data();
    $users_uploads = $upload->getUsersUploads($user_data->user_id);
  }
}

$page_title = "Upload - " . $user_data->user_name . "'s Profile";

$img_exts = array('jpg', 'png', 'PNG', 'gif');

require VIEW_ROOT . '/profile.php';