<?php

$user = new Models\User();
$upload = new Models\Upload();

$uploads = $upload->getUploadsByDate();

$page_title = "Upload - Latest Uploads";

require VIEW_ROOT . '/latest.php';
