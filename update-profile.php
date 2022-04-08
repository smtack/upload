<?php
require_once 'src/init.php';

$user = new User();
$validate = new Validate();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
}

$page_title = "Upload - Update Profile";

if(Input::exists($_POST, 'update_profile')) {
  if(Hash::checkToken(Input::get('token'), 'token')) {
    $validation = $validate->check($_POST, array(
      'user_name' => array(
        'required' => true,
        'min' => 1,
        'max' => 50
      ),
      'user_username' => array(
        'required' => true,
        'min' => 3,
        'max' => 25,
      ),
      'user_email' => array(
        'required' => true,
        'min' => 5,
        'max' => 128,
      )
    ));
  
    if($validation->passed()) {
      if(Database::getInstance()->exists('users', array('user_username', '=', Input::get('user_username'))) && Input::get('user_username') !== $user->data()->user_username) {
        $validation->addError('This username already exists');
      } else if(Database::getInstance()->exists('users', array('user_email', '=', Input::get('user_email'))) && Input::get('user_email') !== $user->data()->user_email) {
        $validation->addError('This email already exists');
      } else {
        if($user->updateProfile(array(
          'user_name' => escape(Input::get('user_name')),
          'user_username' => escape(Input::get('user_username')),
          'user_email' => escape(Input::get('user_email'))
        ))) {
          Redirect::to(BASE_URL);
        } else {
          $validation->addError("Unable to update profile");
        }
      }
    }
  }
}

if(Input::exists($_POST, 'upload_profile_picture')) {
  if(Hash::checkToken(Input::get('picture-token'), 'picture-token')) {
    $picture_validation = $validate->check($_POST, array());

    if($picture_validation->passed()) {
      if(!empty($_FILES['user_profile_picture']['name'])) {
        $target_dir = "uploads/profile-pictures/";
        $file_name = basename($_FILES['user_profile_picture']['name']);
        $path = $target_dir . $file_name;
        $file_type = pathinfo($path, PATHINFO_EXTENSION);
        $allow_types = array('jpg', 'png', 'PNG');
    
        if(in_array($file_type, $allow_types)) {
          if(move_uploaded_file($_FILES['user_profile_picture']['tmp_name'], $path)) {
            if($user->updateProfile(array('user_profile_picture' => $file_name))) {
              Redirect::to(BASE_URL);
            } else {
              $picture_validation->addError("Unable to upload profile picture");
            }
          } else {
            $picture_validation->addError("Unable to upload profile picture");
          }
        } else {
          $picture_validation->addError("This file type is not supported");
        }
      } else {
        $picture_validation->addError("Select a file to upload");
      }
    }
  }
}

if(Input::exists($_POST, 'change_password')) {
  if(Hash::checkToken(Input::get('password-token'), 'password-token')) {
    $password_validation = $validate->check($_POST, array(
      'current_password' => array(
        'required' => true
      ),
      'new_password' => array(
        'required' => true,
        'min' => 3,
        'max' => 128
      ),
      'confirm_password' => array(
        'required' => true,
        'matches' => 'new_password'
      )
    ));
  
    if($password_validation->passed()) {
      if(Hash::verifyPassword(Input::get('current_password'), $user->data()->user_password)) {
        if($user->updateProfile(array('user_password' => Hash::createPassword(Input::get('new_password'))))) {
          Redirect::to(BASE_URL);
        } else {
          $password_validation->addError("Unable to change password");
        }
      } else {
        $password_validation->AddError("Enter your current password correctly");
      }
    }
  }
}

if(Input::exists($_POST, 'delete_profile')) {
  if(Hash::checkToken(Input::get('delete-token'), 'delete-token')) {
    $delete_validation = $validate->check($_POST, array(
      'user_password' => array(
        'required' => true
      )
    ));
  
    if($delete_validation->passed()) {
      if(Hash::verifyPassword(Input::get('user_password'), $user->data()->user_password)) {
        if($user->deleteProfile()) {
          Redirect::to(BASE_URL);
        } else {
          $delete_validation->addError("Unable to delete profile");
        }
      } else {
        $delete_validation->addError("Enter your password correctly");
      }
    }
  }
}

require VIEW_ROOT . '/update-profile.php';