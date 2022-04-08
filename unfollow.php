<?php
require_once 'src/init.php';

$user = new User();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
}

if(!$id = Input::get('u')) {
  Redirect::to(BASE_URL);
} else if($id === $user->data()->user_id) {
  Redirect::to(BASE_URL);
} else {
  $user_to_unfollow = $user->getProfile($id);

  $follow = [
    'follow_user' => $user->data()->user_id,
    'follow_following' => $id
  ];

  if($user->unfollow($follow)) {
    Redirect::to(BASE_URL . '/profile?u=' . $user_to_unfollow->user_username);
  } else {
    Redirect::to(BASE_URL);
  }
}