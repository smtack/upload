<?php
require_once '../src/init.php';

$user = new User();
$upload = new Upload();

$uploads = $upload->getUploadsByViews();

$page_title = "Upload - Popular Uploads";

require VIEW_ROOT . '/popular.php';