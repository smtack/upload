<?php
require_once 'src/init.php';

$user = new User();
$upload = new Upload();

if($user->loggedIn()) {
  $uploads = $upload->getHomepageUploads($user->data()->user_id);
} else {
  $uploads = $upload->getUploads();
}

$img_exts = array('jpg', 'png', 'PNG', 'gif');

require VIEW_ROOT . '/index.php';