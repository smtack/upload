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
      if(empty($_FILES['upload_file']['name'])) {
        $validation->addError("Select a file to upload");
      } else {
        $upload = new Upload();
  
        $upload_dir = '../uploads/uploads/';
        $file_name = basename($_FILES['upload_file']['name']);

        $path = $upload_dir . $file_name;

        $extension = pathinfo($path, PATHINFO_EXTENSION);
      
        if(!in_array($extension, $allowed_upload_extensions)) {
          $validation->addError("This file type is not supported");
        } else {
          $unique_filename = Hash::createUniqueFilename();

          $new_path = $upload_dir . $unique_filename . '.' . $extension;
          $new_filename = $unique_filename . '.' . $extension;

          if(!move_uploaded_file($_FILES['upload_file']['tmp_name'], $new_path)) {
            $validation->addError("Unable to make upload");
          } else {
            $data = [
              'upload_file' => $new_filename,
              'upload_title' => escape(Input::get('upload_title')),
              'upload_description' => escape(Input::get('upload_description')),
              'upload_by' => $user->data()->user_id
            ];

            if($upload->newUpload($data)) {
              Redirect::to(BASE_URL);
            } else {
              $validation->addError("Unable to make upload");
            }
          }
        }
      }
    }
  }
}

require VIEW_ROOT . '/upload.php';