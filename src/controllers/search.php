<?php

use Core\Input;

$user = new Models\User();
$upload = new Models\Upload();

$keywords = escape(Input::get('s') ? Input::get('s') : '');

$user_results = $user->searchUsers($keywords);
$upload_results = $upload->searchUploads($keywords);

$page_title = "Upload - Search: " . str_replace('%', '', $keywords);

require VIEW_ROOT . '/search.php';
