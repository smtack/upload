<?php
require_once 'src/init.php';

$user = new User();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
}

$page_title = "Upload - New Upload";

if(isset($_POST['upload'])) {
  if(empty($_POST['upload_title']) || empty($_POST['upload_description'])) {
    $message = '<p class="message error">Enter a title and a description</p>';
  } else {
    if(empty($_FILES['upload_file']['name'])) {
      $message = '<p class="message error">Select a file to upload</p>';
    } else {
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
            'upload_title' => htmlentities($_POST['upload_title']),
            'upload_description' => htmlentities($_POST['upload_description']),
            'upload_by' => $user->data()->user_id
          ))) {
            Redirect::to(BASE_URL);
          } else {
            $message = '<p class="message error">Unable to make upload</p>';
          }
        } else {
          $message = '<p class="message error">Unable to make upload</p>';
        }
      } else {
        $message = '<p class="message error">This file type is not supported</p>';
      }
    }
  }
}

require VIEW_ROOT . '/upload.php';