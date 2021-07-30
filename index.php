<?php
require_once 'src/init.php';

$user = new User();
$upload = new Upload();

$uploads = $upload->getUploads();

$img_exts = array('jpg', 'png', 'PNG', 'gif');

require VIEW_ROOT . '/index.php';