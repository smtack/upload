<?php
require_once 'src/init.php';

$user = new User();

if($user->loggedIn()) {
  Redirect::to(BASE_URL);
}

$page_title = "Upload - Log In";

if(isset($_POST['login'])) {
  if(empty($_POST['user_username']) || empty($_POST['user_password'])) {
    $message = '<p class="message error">Enter your Username and Password</p>';
  } else {
    if($user->login($_POST['user_username'], $_POST['user_password'])) {
      Redirect::to(BASE_URL);
    } else {
      $message = '<p class="error message">Incorrect Username or Password</p>';
    }
  }
}

require VIEW_ROOT . '/login.php';