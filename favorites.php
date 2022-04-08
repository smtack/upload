<?php
require_once 'src/init.php';

$user = new User();
$upload = new Upload();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
} else {
  $uploads = $upload->getUsersFavorites($user->data()->user_id);
}

$img_exts = array('jpg', 'png', 'PNG', 'gif');

require VIEW_ROOT . '/favorites.php';