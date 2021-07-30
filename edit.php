<?php
require_once 'src/init.php';

$user = new User();
$upload = new Upload();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
}

if(!$id = $_GET['id']) {
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

if(isset($_POST['edit'])) {
  if(empty($_POST['upload_title']) || empty($_POST['upload_description'])) {
    $message = '<p class="message error">Enter a title and a description</p>';
  } else {
    $update_array = array(
      'upload_title' => htmlentities($_POST['upload_title']),
      'upload_description' => htmlentities($_POST['upload_description'])
    );

    if($upload->editUpload($id, $update_array)) {
      $message = '<p class="message notice">Upload has been updated</p>';
    } else {
      $message = '<p class="message error">Unable to edit upload</p>';
    }
  }
}

if(isset($_POST['delete_upload'])) {
  $upload_dir = "uploads/uploads/";
  $upload_name = $upload_data->upload_file;
  $file_to_delete = $upload_dir . $upload_name;

  if($upload->deleteUpload($id)) {
    if(file_exists($file_to_delete)) {
      unlink($file_to_delete);
    }
    
    Redirect::to(BASE_URL);
  } else {
    $delete_message = '<p class="message error">Unable to delete upload</p>';
  }
}

require VIEW_ROOT . '/edit.php';