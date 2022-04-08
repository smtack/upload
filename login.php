<?php
require_once 'src/init.php';

$user = new User();

if($user->loggedIn()) {
  Redirect::to(BASE_URL);
}

$page_title = "Upload - Log In";

if(Input::exists($_POST, 'login')) {
  if(Hash::checkToken(Input::get('token'), 'token')) {
    $validate = new Validate();

    $validation = $validate->check($_POST, array(
      'user_username' => array('required' => true),
      'user_password' => array('required' => true)
    ));
  
    if($validation->passed()) {
      $remember = (Input::get('remember') === 'on') ? true : false;
  
      if($user->login(Input::get('user_username'), Input::get('user_password'), $remember)) {
        Redirect::to(BASE_URL);
      } else {
        $validation->addError("Username or Password Incorrect");
      }
    }
  }
}

require VIEW_ROOT . '/login.php';