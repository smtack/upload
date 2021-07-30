<?php
require_once 'src/init.php';

$user = new User();

if($user->loggedIn()) {
  Redirect::to(BASE_URL);
}

$page_title = "Upload - Sign Up";

if(isset($_POST['signup'])) {
  if(empty($_POST['user_name']) || empty($_POST['user_username']) || empty($_POST['user_email']) || empty($_POST['user_password']) || empty($_POST['confirm_password'])) {
    $message = '<p class="message error">Fill in all fields</p>';
  } else {
    if($_POST['user_password'] !== $_POST['confirm_password']) {
      $message = '<p class="message error">Passwords must match</p>';
    } else {
      if($user->create(array(
        'user_name' => htmlentities($_POST['user_name']),
        'user_username' => htmlentities($_POST['user_username']),
        'user_email' => htmlentities($_POST['user_email']),
        'user_password' => password_hash($_POST['user_password'], PASSWORD_BCRYPT)
      ))) {
        Redirect::to(BASE_URL);
      } else {
        $message = '<p class="message error">Unable to sign up</p>';
      }
    }
  }
}

require VIEW_ROOT . '/signup.php';