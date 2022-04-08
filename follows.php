<?php
require_once 'src/init.php';

$user = new User();

if($user->loggedIn()) {
  $follows = $user->getUsersFollows($user->data()->user_id);
} else {
  Redirect::to(BASE_URL);
}

require VIEW_ROOT . '/follows.php';