<?php
require_once 'src/init.php';

$user = new User();
$upload = new Upload();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
}

if(!$id = Input::get('id')) {
  Redirect::to(BASE_URL);
} else {
  if(!$upload_data = $upload->getUpload($id)) {
    Redirect::to(404);
  } else {
    if($upload_data->upload_by !== $user->data()->user_id) {
      Redirect::to(BASE_URL);
    }
  }
}

$page_title = "Upload - Edit Post: " . $upload_data->upload_title;

$img_exts = array('jpg', 'png', 'PNG', 'gif');

if(Input::exists($_POST, 'edit')) {
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
      if($upload->editUpload($id, array(
        'upload_title' => escape(Input::get('upload_title')),
        'upload_description' => escape(Input::get('upload_description'))
      ))) {
        Redirect::to(BASE_URL);
      } else {
        $validation->addError("Unable to edit upload");
      }
    }
  }
}

if(Input::exists($_POST, 'delete_upload')) {
  if(Hash::checkToken(Input::get('delete-token'), 'delete-token')) {
    $upload_dir = "uploads/uploads/";
    $upload_name = $upload_data->upload_file;
    $file_to_delete = $upload_dir . $upload_name;
  
    if($upload->deleteUpload($id)) {
      if(file_exists($file_to_delete)) {
        unlink($file_to_delete);
      }
      
      Redirect::to(BASE_URL);
    }
  }
}

require VIEW_ROOT . '/edit.php';