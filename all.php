<?php
require_once 'src/init.php';

$user = new User();
$upload = new Upload();

$uploads = $upload->getUploads();

require VIEW_ROOT . '/all.php';