<?php
require_once 'src/init.php';

$user = new User();

if(!$id = Input::get('id')) {
  Redirect::to(BASE_URL);
} else {
  $upload = new Upload();

  if(!$upload_data = $upload->getUpload($id)) {
    Redirect::to(404);
  }
}

$page_title = "Upload - " . $upload_data->upload_title;

$img_exts = array('jpg', 'png', 'PNG', 'gif');

if(Input::exists($_POST, 'submit_comment')) {
  $validate = new Validate();

  $validation = $validate->check($_POST, array(
    'comment_text' => array(
      'required' => true,
      'min' => 1,
      'max' => 255
    )
  ));

  if($validation->passed()) {
    if($upload->newComment(array(
      'comment_text' => escape(Input::get('comment_text')),
      'comment_upload' => $upload_data->upload_id,
      'comment_by' => $user->data()->user_id
    ))) {
      Redirect::to(BASE_URL . '/view?id=' . $upload_data->upload_id);
    } else {
      $validation->addError("Unable to post comment");
    }
  }
}

$comments = $upload->getComments($upload_data->upload_id);

require VIEW_ROOT . '/view.php';