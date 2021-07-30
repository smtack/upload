<?php
class Upload {
  private $db;

  public function __construct() {
    $this->db = Database::getInstance();
  }

  public function newUpload($fields = array()) {
    if($this->db->insert('uploads', $fields)) {
      return true;
    } else {
      return false;
    }
  }

  public function getUploads() {
    $rows = $this->db->select('uploads', array('users', 'users.user_id', 'uploads.upload_by'), array(), 'upload_id');

    if($rows->count()) {
      return $rows->results();
    } else {
      return false;
    }
  }

  public function getUsersUploads($user) {
    $rows = $this->db->select('uploads', array('users', 'users.user_id', 'uploads.upload_by'), array('upload_by', '=', $user), 'upload_id');
    
    if($rows->count()) {
      return $rows->results();
    } else {
      return false;
    }
  }

  public function getUpload($id) {
    $row = $this->db->select('uploads', array('users', 'users.user_id', 'uploads.upload_by'), array('upload_id', '=', $id));

    if($row->count()) {
      return $row->first();
    } else {
      return false;
    }
  }

  public function editUpload($id, $fields = array()) {
    if($this->db->update('uploads', 'upload_id', $id, $fields)) {
      return true;
    } else {
      return false;
    }
  }

  public function deleteUpload($id) {
    if($this->db->delete('uploads', array('upload_id', '=', $id))) {
      return true;
    } else {
      return false;
    }
  }

  public function newComment($comment = array()) {
    if($this->db->insert('comments', $comment)) {
      return true;
    } else {
      return false;
    }
  }

  public function getComments($id) {
    $comments = $this->db->select('comments', array('users', 'users.user_id', 'comments.comment_by'), array('comment_upload', '=', $id), 'comment_date');

    if($comments->count()) {
      return $comments->results();
    } else {
      return false;
    }
  }

  public function searchUploads($keywords) {
    $results = $this->db->select('uploads', array('users', 'users.user_id', 'uploads.upload_by'), array('upload_title', 'LIKE', $keywords), 'upload_date');

    if($results->count()) {
      return $results->results();
    } else {
      return false;
    }
  }
}