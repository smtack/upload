<?php
require_once '../src/init.php';

$user = new User();
$upload = new Upload();

$uploads = $upload->getUploadsByStarRating();

$page_title = "Upload - Popular Uploads";

require VIEW_ROOT . '/top.php';