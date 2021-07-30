<?php
require_once 'src/init.php';

$user = new User();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
}

$page_title = "Upload - Update Profile";

if(isset($_POST['update_profile'])) {
  if(empty($_POST['user_name']) || empty($_POST['user_username']) || empty($_POST['user_email'])) {
    $update_message = '<p class="message error">Fill in all fields</p>';
  } else {
    if($user->updateProfile(array(
      'user_name' => htmlentities($_POST['user_name']),
      'user_username' => htmlentities($_POST['user_username']),
      'user_email' => htmlentities($_POST['user_email'])
    ))) {
      $update_message = '<p class="message notice">Profile updated successfully</p>';
    } else {
      $update_message = '<p class="message error">Unable to update profile/p>';
    }
  }
}

if(isset($_POST['upload_profile_picture'])) {
  if(empty($_FILES['user_profile_picture']['name'])) {
    $picture_message = '<p class="message error">Select a file to upload</p>';
  } else {
    $target_dir = "uploads/profile-pictures/";
    $file_name = basename($_FILES['user_profile_picture']['name']);
    $path = $target_dir . $file_name;
    $file_type = pathinfo($path, PATHINFO_EXTENSION);
    $allow_types = array('jpg', 'png', 'PNG');

    if(in_array($file_type, $allow_types)) {
      if(move_uploaded_file($_FILES['user_profile_picture']['tmp_name'], $path)) {
        if($user->updateProfile(array('user_profile_picture' => $file_name))) {
          $picture_message = '<p class="message notice">Profile picture uploaded</p>';
        } else {
          $picture_message = '<p class="message error">Unable to upload profile picture</p>';
        }
      } else {
        $picture_message = '<p class="message error">Unable to upload profile picture</p>';
      }
    } else {
      $picture_message = '<p class="message error">This file type is not supported</p>';
    }
  }
}

if(isset($_POST['change_password'])) {
  if(empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
    $password_message = '<p class="message error">Fill in all fields</p>';
  } else {
    if(!password_verify($_POST['current_password'], $user->data()->user_password)) {
      $password_message = '<p class="message error">Enter your current password correctly</p>';
    } else {
      if($_POST['new_password'] !== $_POST['confirm_password']) {
        $password_message = '<p class="message error">Passwords do not match</p>';
      } else {
        if($user->updateProfile(array(
          'user_password' => password_hash($_POST['new_password'], PASSWORD_BCRYPT)
        ))) {
          $password_message = '<p class="message notice">Password updated successfully</p>';
        } else {
          $password_message = '<p class="message notice">Unable to change password</p>';
        }
      }
    }
  }
}

if(isset($_POST['delete_profile'])) {
  if($user->deleteProfile()) {
    Redirect::to(BASE_URL);
  } else {
    $delete_message = '<p class="message error">Unable to delete profile</p>';
  }
}

require VIEW_ROOT . '/update-profile.php';