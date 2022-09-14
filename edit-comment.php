<?php
require_once 'src/init.php';

$user = new User;
$upload = new Upload();

if(!$user->loggedIn()) {
  Redirect::to(BASE_URL);
}

if(!$id = Input::get('id')) {
  Redirect::to(BASE_URL);
} else {
  if(!$comment = $upload->getComment($id)) {
    Redirect::to(BASE_URL);
  } else {
    if($comment->comment_by !== $user->data()->user_id) {
      Redirect::to(BASE_URL);
    }
  }
}

$page_title = "Upload - Edit Comment";

if(Input::exists($_POST, 'edit_comment')) {
  if(Hash::checkToken(Input::get('token'), 'token')) {
    $validate = new Validate();

    $validation = $validate->check($_POST, array(
      'comment_text' => array(
        'name' => 'Comment',
        'required' => true,
        'min' => 1,
        'max' => 255
      )
    ));
  
    if($validation->passed()) {
      if($upload->editComment($id, array('comment_text' => escape(Input::get('comment_text'))))) {
        Redirect::to(BASE_URL . '/view?id=' . $comment->comment_upload);
      } else {
        $validation->addError("Unable to update comment");
      }
    }
  }
}

if(Input::exists($_POST, 'delete_comment')) {
  if(Hash::checkToken(Input::get('delete-token'), 'delete-token')) {
    if($upload->deleteComment($id)) {
      Redirect::to(BASE_URL . '/view?id=' . $comment->comment_upload);
    }
  }
}

require VIEW_ROOT . '/edit-comment.php';