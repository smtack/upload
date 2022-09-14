<?php
require_once 'src/init.php';

$user = new User();
$upload = new Upload();

$keywords = Input::get('s') ? Input::get('s') : '';
$keywords = escape($keywords);
$keywords = "%{$keywords}%";

$user_results = $user->searchUsers($keywords);
$upload_results = $upload->searchUploads($keywords);

$page_title = "Upload - Search: " . str_replace('%', '', $keywords);

require VIEW_ROOT . '/search.php';