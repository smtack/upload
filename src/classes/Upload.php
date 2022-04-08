<?php
class Upload {
  private $db;

  public function __construct() {
    $this->db = Database::getInstance();
  }

  public function newUpload($fields = array()) {
    if($this->db->insert('uploads', $fields)) {
      return true;
    }

    return false;
  }

  public function getUploads() {
    $rows = $this->db->select('uploads', array('users', 'users.user_id', 'uploads.upload_by'), array(), 'upload_id');

    if($rows->count()) {
      return $rows->results();
    }

    return false;
  }

  public function getHomepageUploads($user) {
    $sql = "SELECT
              *
            FROM
              uploads
            LEFT JOIN
              users
            ON
              users.user_id = uploads.upload_by
            WHERE
              (upload_by = users.user_id AND users.user_id = $user)
            OR
              (upload_by = users.user_id AND upload_by
            IN
              (SELECT
                follow_following
              FROM
                follows
              WHERE
                follow_user = $user))
            ORDER BY
              upload_date
            DESC";

    $stmt = $this->db->pdo->prepare($sql);

    if($stmt->execute()) {
      return $stmt->fetchAll();
    }

    return false;
  }

  public function getUsersUploads($user) {
    $rows = $this->db->select('uploads', array('users', 'users.user_id', 'uploads.upload_by'), array('upload_by', '=', $user), 'upload_id');

    return $rows->results();
  }

  public function getUpload($id) {
    $row = $this->db->select('uploads', array('users', 'users.user_id', 'uploads.upload_by'), array('upload_id', '=', $id));

    if($row->count()) {
      return $row->first();
    }

    return false;
  }

  public function addView($id) {
    $sql = "UPDATE uploads SET upload_views = upload_views + 1 WHERE upload_id = ?";

    $stmt = $this->db->pdo->prepare($sql);

    $stmt->bindParam(1, $id);

    if($stmt->execute()) {
      return true;
    }

    return false;
  }
  
  public function editUpload($id, $fields = array()) {
    if($this->db->update('uploads', 'upload_id', $id, $fields)) {
      return true;
    }

    return false;
  }

  public function deleteUpload($id) {
    if($this->db->delete('uploads', array('upload_id', '=', $id))) {
      return true;
    }

    return false;
  }

  public function favorite($favorite) {
    if($this->db->insert('favorites', $favorite)) {
      return true;
    }

    return false;
  }

  public function unfavorite($favorite) {
    $sql = "DELETE FROM favorites WHERE favorite_user = ? AND favorite_upload = ?";

    $stmt = $this->db->pdo->prepare($sql);

    $stmt->bindParam(1, $favorite['favorite_user']);
    $stmt->bindParam(2, $favorite['favorite_upload']);

    if($stmt->execute()) {
      return true;
    }

    return false;
  }

  public function getFavoritesData($favorite) {
    $sql = "SELECT * FROM favorites WHERE favorite_upload = $favorite";

    $stmt = $this->db->pdo->prepare($sql);

    if($stmt->execute()) {
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return false;
  }

  public function getUsersFavorites($user) {
    $sql = "SELECT
              *
            FROM
              uploads
            LEFT JOIN
              favorites
            ON
              uploads.upload_id = favorites.favorite_upload
            LEFT JOIN
              users
            ON
              uploads.upload_by = users.user_id
            WHERE
              favorite_user = $user
            ORDER BY
              upload_date
            DESC";
    
    $stmt = $this->db->pdo->prepare($sql);

    if($stmt->execute()) {
      return $stmt->fetchAll();
    }

    return false;
  }

  public function newComment($comment = array()) {
    if($this->db->insert('comments', $comment)) {
      return true;
    }

    return false;
  }

  public function getComments($id) {
    $comments = $this->db->select('comments', array('users', 'users.user_id', 'comments.comment_by'), array('comment_upload', '=', $id), 'comment_date');

    if($comments->count()) {
      return $comments->results();
    }

    return false;
  }

  public function getComment($id) {
    $comment = $this->db->select('comments', array('users', 'users.user_id', 'comments.comment_by'), array('comment_id', '=', $id));

    if($comment->count()) {
      return $comment->first();
    }

    return false;
  }

  public function editComment($id, $fields = array()) {
    if($this->db->update('comments', 'comment_id', $id, $fields)) {
      return true;
    }

    return false;
  }

  public function deleteComment($id) {
    if($this->db->delete('comments', array('comment_id', '=', $id))) {
      return true;
    }

    return false;
  }

  public function searchUploads($keywords) {
    $results = $this->db->select('uploads', array('users', 'users.user_id', 'uploads.upload_by'), array('upload_title', 'LIKE', $keywords), 'upload_date');

    if($results->count()) {
      return $results->results();
    }

    return false;
  }
}