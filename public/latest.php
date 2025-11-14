<?php
require_once '../src/init.php';

$user = new User();
$upload = new Upload();

$uploads = $upload->getUploadsByDate();

$page_title = "Upload - Latest Uploads";

require VIEW_ROOT . '/latest.php';