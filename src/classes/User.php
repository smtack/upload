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
    } else {
      return false;
    }
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
        } else {
          return false;
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
    } else {
      return false;
    }
  }

  public function deleteProfile() {
    if(!$id && $this->loggedIn()) {
      $id = $this->data()->user_id;
    }

    if($this->db->delete('users', array('user_id', '=', $id))) {
      $this->logout();

      return true;
    } else {
      return false;
    }
  }

  public function getProfile($username) {
    $row = $this->db->select('users', array(), array('user_username', '=', $username));
    
    if($row->count()) {
      $profile = $row->first();

      return $profile;
    } else {
      return false;
    }
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