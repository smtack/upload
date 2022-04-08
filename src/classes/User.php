<?php
class User {
  private $db;
  private $data;
  private $session_name;
  private $cookie_name;
  private $logged_in;

  public function __construct($user = null) {
    $this->db = Database::getInstance();
    $this->session_name = Config::get('session/session_name');
    $this->cookie_name = Config::get('remember/cookie_name');

    if(!$user) {
      if(Session::exists($this->session_name)) {
        $user = Session::get($this->session_name);

        if($this->find($user)) {
          $this->logged_in = true;
        } else {
          $this->logout();
        }
      }
    } else {
      $this->find($user);
    }
  }

  public function create($fields = array()) {
    if($this->db->insert('users', $fields)) {
      $user = $this->find($fields['user_username']);

      Session::set($this->session_name, $this->data()->user_id);

      return true;
    }

    return false;
  }

  public function find($user = null) {
    if($user) {
      $field = (is_numeric($user)) ? 'user_id' : 'user_username';
      $data = $this->db->select('users', array(), array($field, '=', $user));

      if($data->count()) {
        $this->data = $data->first();

        return true;
      }
    }

    return false;
  }

  public function login($username = null, $password = null, $remember = false) {
    if(!$username && !$password && $this->exists()) {
      Session::set($this->session_name, $this->data()->user_id);
    } else {
      $user = $this->find($username);

      if($user) {
        if(Hash::verifyPassword($password, $this->data()->user_password)) {
          Session::set($this->session_name, $this->data()->user_id);

          if($remember) {
            $hash = Hash::random();
            $hash_check = $this->db->select('users_session', array(), array('user_id', '=', $this->data()->user_id));

            if(!$hash_check->count()) {
              $this->db->insert('users_session', array(
                'user_id' => $this->data()->user_id,
                'session_hash' => $hash
              ));
            } else {
              $hash = $hash_check->first()->session_hash;
            }

            Cookie::set($this->cookie_name, $hash, Config::get('remember/cookie_expiry'));
          }

          return true;
        }
      }
    }

    return false;
  }

  public function logout() {
    $this->db->delete('users_session', array('user_id', '=', $this->data()->user_id));

    Session::unset($this->session_name);

    Cookie::delete($this->cookie_name);
  }

  public function updateProfile($fields = array(), $id = null) {
    if(!$id && $this->loggedIn()) {
      $id = $this->data()->user_id;
    }

    if($this->db->update('users', 'user_id', $id, $fields)) {
      return true;
    }

    return false;
  }

  public function deleteProfile($id = null) {
    if(!$id && $this->loggedIn()) {
      $id = $this->data()->user_id;
    }

    if($this->db->delete('users', array('user_id', '=', $id))) {
      $this->logout();

      return true;
    }

    return false;
  }

  public function getProfile($user) {
    if(is_numeric($user)) {
      $id = $user;
    } else {
      $username = $user;
    }
    
    $sql = "SELECT
              *
            FROM
              users
            WHERE
              user_id = ?
            OR
              user_username = ?";
    
    $stmt = $this->db->pdo->prepare($sql);

    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $username);

    if($stmt->execute()) {
      return $stmt->fetch();
    }

    return false;
  }

  public function searchUsers($keywords) {
    $results = $this->db->select('users', array(), array('user_username', 'LIKE', $keywords), 'user_joined');

    if($results->count()) {
      return $results->results();
    } else {
      return '';
    }
  }

  public function follow($follow) {
    if($this->db->insert('follows', $follow)) {
      return true;
    }

    return false;
  }

  public function unfollow($follow) {
    $sql = "DELETE FROM follows WHERE follow_user = ? AND follow_following = ?";

    $stmt = $this->db->pdo->prepare($sql);

    $stmt->bindParam(1, $follow['follow_user']);
    $stmt->bindParam(2, $follow['follow_following']);

    if($stmt->execute()) {
      return true;
    }

    return false;
  }

  public function getFollowsData($user) {
    $sql = "SELECT * FROM follows WHERE follow_following = $user";

    $stmt = $this->db->pdo->prepare($sql);

    if($stmt->execute()) {
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return false;
  }

  public function getUsersFollows($user) {
    $rows = $this->db->select('users', array('follows', 'users.user_id', 'follows.follow_following'), array('follow_user', '=', $user));

    if($rows->count()) {
      return $rows->results();
    }

    return false;
  }

  public function exists() {
    return (!empty($this->data)) ? true : false;
  }

  public function data() {
    return $this->data;
  }

  public function loggedIn() {
    return $this->logged_in;
  }
}