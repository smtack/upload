<?php
require_once 'src/init.php';

$user = new User();
$upload = new Upload();

$keywords = isset($_GET['s']) ? $_GET['s'] : '';
$keywords = htmlentities($keywords);
$keywords = "%{$keywords}%";

$results = $upload->searchUploads($keywords);

$page_title = "Upload - Search: " . str_replace('%', '', $keywords);

$img_exts = array('jpg', 'png', 'PNG', 'gif');

require VIEW_ROOT . '/search.php';