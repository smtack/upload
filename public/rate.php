<?php
require_once '../src/init.php';

$user = new User();
$upload = new Upload();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL . '/login');
}

if(!$id = Input::get('id')) {
  Redirect::to(BASE_URL);
} else {
  $upload_to_rate = $upload->getUpload($id);
  $rating = Input::get('star-input');

  $data = [
    'rating_user' => $user->data()->user_id,
    'rating_upload' => $upload_to_rate->upload_id,
    'rating_number' => $rating
  ];

  if($upload->rate($data)) {
    Redirect::to(BASE_URL . '/view?id=' . $upload_to_rate->upload_id);
  } else {
    Redirect::to(BASE_URL . '/view?id=' . $upload_to_rate->upload_id);
  }
}