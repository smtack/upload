<?php
require_once 'src/init.php';

$user = new User();

if(!$id = $_GET['id']) {
  Redirect::to(BASE_URL);
} else {
  $upload = new Upload();

  if(!$upload_data = $upload->getUpload($id)) {
    Redirect::to(404);
  }
}

if(isset($_POST['submit_comment'])) {
  if(empty($_POST['comment_text'])) {
    $message = '<p class="message error">Enter a comment</p>';
  } else {
    if($upload->newComment(array(
      'comment_text' => htmlentities($_POST['comment_text']),
      'comment_upload' => $upload_data->upload_id,
      'comment_by' => $user->data()->user_id
    ))) {
      Redirect::to(BASE_URL . '/view?id=' . $upload_data->upload_id);
    } else {
      $message = '<p class="message error">Unable to post comment</p>';
    }
  }
}

$page_title = "Upload - " . $upload_data->upload_title;

$comments = $upload->getComments($upload_data->upload_id);

$img_exts = array('jpg', 'png', 'PNG', 'gif');

require VIEW_ROOT . '/view.php';