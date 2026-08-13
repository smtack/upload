<?php

use Core\Config;
use Core\Database;
use Core\File;
use Core\Input;
use Core\Redirect;
use Core\Hash;
use Core\Validate;

$user = new Models\User();

$validate = new Validate();

if(!$user->loggedIn()) {
    Redirect::to(BASE_URL);
}

$page_title = "Upload - Update Profile";

if(Input::exists($_POST, 'update_profile')) {
    if(Hash::checkToken(Input::get('token'), 'token')) {
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
            ),
            'user_email' => array(
                'name' => 'Email',
                'required' => true,
                'min' => 5,
                'max' => 128,
            )
        ));

        if($validation->passed()) {
            if(Database::getInstance()->exists('users', ['user_username' => Input::get('user_username')]) && Input::get('user_username') !== $user->data()->user_username) {
                $validation->addError('This username already exists');
            } else if(Database::getInstance()->exists('users', ['user_email' => Input::get('user_email')]) && Input::get('user_email') !== $user->data()->user_email) {
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
            $file = File::get('user_profile_picture');

            $extension = File::getFileExtension($file);

            $unique_filename = File::createUniqueFilename();

            $new_filename = $unique_filename . "." . $extension;

            $target_folder = "profile-pictures";

            if(!$file) {
                $picture_validation->addError("Select a file to upload");
            } else if(!in_array($extension, Config::get('image_extensions'))) {
                $picture_validation->addError("This file type is not supported");
            } else {
                if(!File::moveFile('user_profile_picture', $target_folder, $new_filename)) {
                    $picture_validation->addError("Unable to upload profile picture");
                } else {
                    if(!$user->updateProfile(array('user_profile_picture' => $new_filename))) {
                        $picture_validation->addError("Unable to upload profile picture");
                    } else {
                        Redirect::to(BASE_URL);
                    }
                }
            }
        }
    }
}

if(Input::exists($_POST, 'change_password')) {
    if(Hash::checkToken(Input::get('password-token'), 'password-token')) {
        $password_validation = $validate->check($_POST, array(
            'current_password' => array(
                'name' => 'Current Password',
                'required' => true
            ),
            'new_password' => array(
                'name' => 'New Password',
                'required' => true,
                'min' => 3,
                'max' => 128
            ),
            'confirm_password' => array(
                'name' => 'Confirm Password',
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
                'name' => 'Password',
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
