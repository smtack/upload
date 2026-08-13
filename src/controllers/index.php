<?php

$user = new Models\User();
$upload = new Models\Upload();

if($user->loggedIn()) {
    $uploads = $upload->getHomepageUploads($user->data()->user_id);
} else {
    $uploads = $upload->getUploadsByDate();
}

require VIEW_ROOT . '/index.php';
