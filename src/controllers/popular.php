<?php

$user = new Models\User();
$upload = new Models\Upload();

$uploads = $upload->getUploadsByViews();

$page_title = "Upload - Popular Uploads";

require VIEW_ROOT . '/popular.php';
