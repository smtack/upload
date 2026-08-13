<?php

namespace Models;


use Core\Database;
use Core\Config;
use Core\Cookie;
use Core\Hash;
use Core\Session;

class User
{
    private $db;
    private $data;
    private $session_name;
    private $cookie_name;
    private $logged_in;

    public function __construct($user = null)
    {
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

    public function create($fields = array())
    {
        if($this->db->insert('users', $fields)) {
            $user = $this->find($fields['user_username']);

            Session::set($this->session_name, $this->data()->user_id);

            return true;
        }

        return false;
    }

    public function find($user = null)
    {
        if($user) {
            $field = (is_numeric($user)) ? 'user_id' : 'user_username';
            $data = $this->db->select('users', [$field => $user]);

            if($data->count()) {
                $this->data = $data->first();

                return true;
            }
        }

        return false;
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if(!$username && !$password && $this->exists()) {
            Session::set($this->session_name, $this->data()->user_id);
        } else {
            $user = $this->find($username);

            if($user) {
                if(Hash::verifyPassword($password, $this->data()->user_password)) {
                    Session::set($this->session_name, $this->data()->user_id);

                    if($remember) {
                        $hash = Hash::random(128);
                        $hash_check = $this->db->select('users_session', ['user_id' => $this->data()->user_id]);

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

    public function logout()
    {
        $this->db->delete('users_session', ['user_id' => $this->data()->user_id]);

        Session::unset($this->session_name);

        Cookie::delete($this->cookie_name);
    }

    public function updateProfile(array $data = [], ?int $id = null): bool
    {
        if(!$id && $this->loggedIn()) {
            $id = $this->data()->user_id;
        }

        if($this->db->update('users', $data, 'user_id', $id)) {
            return true;
        }

        return false;
    }

    public function deleteProfile($id = null)
    {
        if(!$id && $this->loggedIn()) {
            $id = $this->data()->user_id;
        }

        if($this->db->delete('users', ['user_id' => $id])) {
            $this->logout();

            return true;
        }

        return false;
    }

    public function getProfile($user)
    {
        if(is_numeric($user)) {
            $query = $this->db->query('select * from users where user_id = ?', [$user]);
        } else {
            $query = $this->db->query('select * from users where user_username = ?', [$user]);
        }

        if($query->count()) {
            return $query->first();
        }

        return false;
    }

    public function searchUsers($keywords)
    {
        $results = $this->db->query('select * from users where user_username like ? order by user_joined', ["%$keywords%"]);

        if($results->count()) {
            return $results->results();
        }
    }

    public function follow($follow)
    {
        if($this->db->insert('follows', $follow)) {
            return true;
        }

        return false;
    }

    public function unfollow($follow)
    {
        if ($this->db->delete('follows', ['follow_user' => $follow['follow_user'], 'follow_following' => $follow['follow_following']])) {
            return true;
        }

        return false;
    }

    public function getFollowsData($user)
    {
        $rows = $this->db->query("SELECT * FROM follows WHERE follow_following = ?", [$user]);

        $results = $rows->results();

        return array_map(fn ($result) => (array) $result, $results);
    }

    public function getUsersFollows($user)
    {
        $rows = $this->db->query('select * from users left join follows on users.user_id = follows.follow_following where follows.follow_user = ?', [$user]);

        if($rows->count()) {
            return $rows->results();
        }

        return false;
    }

    public function exists()
    {
        return (!empty($this->data)) ? true : false;
    }

    public function data()
    {
        return $this->data;
    }

    public function loggedIn()
    {
        return $this->logged_in;
    }
}
