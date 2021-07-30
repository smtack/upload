<?php
class User {
  private $db;
  private $data;
  private $sessionName;
  private $loggedIn;

  public function __construct($user = null) {
    $this->db = Database::getInstance();
    $this->sessionName = 'user';

    if(!$user) {
      if(Session::exists($this->sessionName)) {
        $user = Session::get($this->sessionName);

        if($this->find($user)) {
          $this->loggedIn = true;
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

      Session::set($this->sessionName, $this->data()->user_id);

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

  public function login($username = null, $password = null) {
    if(!$username && !$password && $this->exists()) {
      Session::set($this->sessionName, $this->data()->user_id);
    } else {
      $user = $this->find($username);

      if($user) {
        if(password_verify($password, $this->data()->user_password)) {
          Session::set($this->sessionName, $this->data()->user_id);

          return true;
        } else {
          return false;
        }
      }
    }

    return false;
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
      Session::unset($this->sessionName);

      return true;
    } else {
      return false;
    }
  }

  public function logout() {
    Session::unset($this->sessionName);
  }

  public function exists() {
    return (!empty($this->data)) ? true : false;
  }

  public function data() {
    return $this->data;
  }

  public function loggedIn() {
    return $this->loggedIn;
  }
}