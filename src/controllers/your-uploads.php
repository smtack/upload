<?php

use Core\Redirect;

$user = new Models\User();
$upload = new Models\Upload();

if(!$user->loggedIn()) {
    Redirect::to(BASE_URL);
}

$uploads = $upload->getUsersUploads($user->data()->user_id);

$page_title = "Upload - Your Uploads";

require VIEW_ROOT . '/your-uploads.php';
