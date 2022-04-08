<?php
require_once 'src/init.php';

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
        'required' => true,
        'min' => 1,
        'max' => 150
      ),
      'upload_description' => array(
        'max' => 500
      )
    ));
  
    if($validation->passed()) {
      if(!empty($_FILES['upload_file']['name'])) {
        $upload = new Upload();
  
        $upload_dir = 'uploads/uploads/';
        $file_name = basename($_FILES['upload_file']['name']);
        $path = $upload_dir . $file_name;
        $file_type = pathinfo($path, PATHINFO_EXTENSION);
        $allow_types = array('mp4', 'jpg', 'png', 'PNG', 'gif');
    
        if(in_array($file_type, $allow_types)) {
          if(move_uploaded_file($_FILES['upload_file']['tmp_name'], $path)) {
            if($upload->newUpload(array(
              'upload_file' => $file_name,
              'upload_title' => escape(Input::get('upload_title')),
              'upload_description' => escape(Input::get('upload_description')),
              'upload_by' => $user->data()->user_id
            ))) {
              Redirect::to(BASE_URL);
            } else {
              $validation->addError("Unable to make upload");
            }
          } else {
            $validation->addError("Unable to make upload");
          }
        } else {
          $validation->addError("This file type is not supported");
        }
      } else {
        $validation->addError("Select a file to upload");
      }
    }
  }
}

require VIEW_ROOT . '/upload.php';