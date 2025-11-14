<?php
require_once '../src/init.php';

$user = new User();
$upload = new Upload();

if(!$user->loggedIn()) {
    Redirect::to(BASE_URL);
}

$uploads = $upload->getUsersUploads($user->data()->user_id);

$page_title = "Upload - Your Uploads";

require VIEW_ROOT . '/your-uploads.php';