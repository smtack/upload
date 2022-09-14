<?php
require_once 'src/init.php';

$user = new User();

if($user->loggedIn()) {
  Redirect::to(BASE_URL);
}

$page_title = "Upload - Sign Up";

if(Input::exists($_POST, 'signup')) {
  if(Hash::checkToken(Input::get('token'), 'token')) {
    $validate = new Validate();

    $validation = $validate->check($_POST, array(
      'user_name' => array(
        'name' => 'Name',
        'required' => true,
        'min' => 1,
        'max' => 50
      ),
      'user_username' => array(
        'name' => 'Username',
        'required' => true,
        'min' => 3,
        'max' => 25,
        'unique' => 'users'
      ),
      'user_email' => array(
        'name' => 'Email',
        'required' => true,
        'min' => 5,
        'max' => 128,
        'unique' => 'users'
      ),
      'user_password' => array(
        'name' => 'Password',
        'required' => true,
        'min' => 3,
        'max' => 128
      ),
      'confirm_password' => array(
        'name' => 'Confirm Password',
        'required' => true,
        'matches' => 'user_password'
      )
    ));
  
    if($validation->passed()) {
      if($user->create(array(
        'user_name' => escape(Input::get('user_name')),
        'user_username' => escape(Input::get('user_username')),
        'user_email' => escape(Input::get('user_email')),
        'user_password' => escape(Hash::createPassword(Input::get('user_password')))
      ))) {
        Redirect::to(BASE_URL);
      } else {
        $validation->addError("Unable to Sign Up");
      }
    }
  }
}

require VIEW_ROOT . '/signup.php';