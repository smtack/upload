<?php
require_once '../src/init.php';

$user = new User();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
}

$page_title = "Upload - New Upload";

if(Input::exists($_POST, 'upload')) {
  if(Hash::checkToken(Input::get('token'), 'token')) {
    $validate = new Validate();

    $validation = $validate->check($_POST, array(
      'upload_title' => array(
        'name' => 'Title',
        'required' => true,
        'min' => 1,
        'max' => 150
      ),
      'upload_description' => array(
        'name' => 'Description',
        'max' => 500
      )
    ));
  
    if($validation->passed()) {
      $upload = new Upload();
      
      $file = File::get('upload_file');

      $extension = File::getFileExtension($file);

      $unique_filename = File::createUniqueFilename();

      $new_filename = $unique_filename . "." . $extension;

      $target_folder = "uploads";

      $data = [
        'upload_file' => $new_filename,
        'upload_title' => escape(Input::get('upload_title')),
        'upload_description' => escape(Input::get('upload_description')),
        'upload_by' => $user->data()->user_id
      ];

      if(!$file) {
        $validation->addError("Select a file to upload");
      } else if(!in_array($extension, $allowed_upload_extensions)) {
        $validation->addError("This file type is not supported");
      } else {
        if(!File::moveFile('upload_file', $target_folder, $new_filename)) {
          $validation->addError("Unable to make upload");
        } else {
          if(!$upload->newUpload($data)) {
            $validation->addError("Unable to make upload");
          } else {
            Redirect::to(BASE_URL);
          }
        }
      }
    }
  }
}

require VIEW_ROOT . '/upload.php';