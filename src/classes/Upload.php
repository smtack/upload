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

  public function getUploadsByDate() {
    $rows = $this->db->select('uploads', array('users', 'users.user_id', 'uploads.upload_by'), array(), 'upload_date');

    if($rows->count()) {
      return $rows->results();
    }

    return false;
  }

  public function getUploadsByViews() {
    $rows = $this->db->select('uploads', array('users', 'users.user_id', 'uploads.upload_by'), array(), 'upload_views');

    if($rows->count()) {
      return $rows->results();
    }

    return false;
  }

  public function getUploadsByStarRating() {
    $sql = "SELECT
              u.*,
              user.*,
              COALESCE(AVG(r.rating_number), 0) AS avg_rating,
              COUNT(r.rating_number) AS rating_count
            FROM
              uploads u
            LEFT JOIN
              uploads_ratings r
            ON
              u.upload_id = r.rating_upload
            LEFT JOIN
              users user
            ON
              u.upload_by = user.user_id
            GROUP BY
              u.upload_id, user.user_id
            ORDER BY
              avg_rating DESC, rating_count DESC";

    $stmt = $this->db->pdo->prepare($sql);

    if($stmt->execute()) {
      return $stmt->fetchAll();
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

  public function rate($data) {
    if($rating = $this->getUsersRating($data['rating_user'], $data['rating_upload'])) {
      if($this->db->update('uploads_ratings', 'rating_id', $rating->rating_id, $data)) {
        return true;
      }
    } else {
      if($this->db->insert('uploads_ratings', $data)) {
        return true;
      }
    }

    return false;
  }

  public function getUploadRating($upload) {
    $sql = "SELECT
              uploads.upload_id, AVG(uploads_ratings.rating_number)
            AS
              rating
            FROM
              uploads
            LEFT JOIN
              uploads_ratings
            ON
              uploads.upload_id = uploads_ratings.rating_upload 
            WHERE
              uploads.upload_id = ?";

    $stmt = $this->db->pdo->prepare($sql);

    $stmt->bindParam(1, $upload);

    if($stmt->execute()) {
      return $stmt->fetchObject();
    }

    return false;
  }

  public function getUsersRating($user, $upload) {
    $sql = "SELECT
              rating_number,
              rating_id
            FROM
              uploads_ratings
            WHERE
              rating_user = ?
            AND
              rating_upload = ?";

    $stmt = $this->db->pdo->prepare($sql);

    $stmt->bindParam(1, $user, PDO::PARAM_INT);
    $stmt->bindParam(2, $upload, PDO::PARAM_INT);

    try {
      $stmt->execute();

      $row = $stmt->fetch();

      return $row ? $row : false;
    } catch(PDOException $e) {
      return false; 
    }
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