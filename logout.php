<?php
require_once 'src/init.php';

$user = new User();

$user->logout();

Redirect::to(BASE_URL);