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
  $upload_to_favorite = $upload->getUpload($id);

  $favorite = [
    'favorite_user' => $user->data()->user_id,
    'favorite_upload' => $id
  ];

  if($upload->favorite($favorite)) {
    Redirect::to(BASE_URL . '/view?id=' . $upload_to_favorite->upload_id);
  } else {
    Redirect::to(BASE_URL);
  }
}