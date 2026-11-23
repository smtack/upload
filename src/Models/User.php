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

        if (!$user) {
            if (Session::exists($this->session_name)) {
                $session_token = Session::get($this->session_name);
                $hashed_token = hash('sha256', $session_token);

                $session = $this->db->select('users_sessions', ['session_token' => $hashed_token]);

                if ($session->count()) {
                    $session_data = $session->first();

                    if (strtotime($session_data->expires_at) > time()) {
                        if ($this->find($session_data->session_user)) {
                            $this->logged_in = true;
                        }
                    } else {
                        Session::unset($this->session_name);
                    }
                } else {
                    Session::unset($this->session_name);
                }
            }
        } else {
            $this->find($user);
        }
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

    public function create($fields = [])
    {
        if($this->db->insert('users', $fields)) {
            $user = $this->find($fields['user_username']);

            Session::set($this->session_name, $this->data()->user_id);

            $this->createUserSession($this->data()->user_id);

            return true;
        }

        return false;
    }

    public function createUserSession($user_id)
    {
        $session_token = Hash::random(32);
        $hashed_token = hash('sha256', $session_token);

        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $expires = date('Y-m-d H:i:s', strtotime('+1 days'));

        $this->db->query("delete from users_sessions where session_user = ? AND expires_at < NOW()", [$user_id]);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $session = [
            'session_user' => $user_id,
            'session_token' => $hashed_token,
            'user_agent' => $user_agent,
            'ip_address' => $ip_address,
            'expires_at' => $expires
        ];

        if (!$this->db->insert('users_sessions', $session)) {
            return false;
        }

        Session::set($this->session_name, $session_token);

        return $session_token;
    }

    public function validateUserSession()
    {
        if (!Session::exists($this->session_name) || !preg_match('/\A[a-f0-9]{64}\z/', Session::get($this->session_name))) {
            return false;
        }

        $session_token = Session::get($this->session_name);
        $hashed_token = hash('sha256', $session_token);

        $result = $this->db->query(
            'select session_user as user_id from users_sessions where session_token = ? AND expires_at > NOW() limit 1',
            [$hashed_token]
        );

        if ($result->count()) {
            $user_id = $result->first()->user_id;

            if ($this->find($user_id)) {
                $this->logged_in = true;

                return $user_id;
            }
        }

        return false;
    }

    public function deleteUserSession()
    {
        $session_token = Session::get($this->session_name) ?? null;

        if (empty($session_token)) {
            return false;
        }

        $hashed_token = hash('sha256', $session_token);

        if ($this->db->delete('users_sessions', ['session_token' => $hashed_token])) {
            Session::unset($this->session_name);

            return true;
        }

        return false;
    }

    public function createRememberToken($user_id)
    {
        $selector = Hash::random(16);
        $validator = Hash::random(32);

        $hashed_validator = password_hash($validator, PASSWORD_DEFAULT);

        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown'; 
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));

        $remember = [
            'remember_user' => $user_id,
            'selector' => $selector,
            'hashed_validator' => $hashed_validator,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'expires_at' => $expires
        ];

        if ($this->db->insert('remember_tokens', $remember)) {
            $cookie_value = "$selector:$validator";

            Cookie::set(
                $this->cookie_name,
                $cookie_value,
                [
                    'expires' => strtotime($expires),
                    'path' => '/',
                    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]
            );

            return true;
        }

        return false;
    }

    public function validateRememberToken()
    {
        if ($this->validateUserSession()) {
            return false;
        }

        if (!Cookie::exists($this->cookie_name)) {
            return false;
        }

        $cookie_value = Cookie::get($this->cookie_name);
        $cookie_parts = explode(':', $cookie_value, 2);

        if (count($cookie_parts) !== 2) {
            $this->deleteRememberCookie();

            return false;
        }

        [$selector, $validator] = $cookie_parts;

        if (!preg_match('/\A[a-f0-9]{32}\z/', $selector) || !preg_match('/\A[a-f0-9]{64}\z/', $validator)) {
            $this->deleteRememberCookie();

            return false;
        }

        $result = $this->db->query(
            'select remember_user as user_id, hashed_validator from remember_tokens where selector = ? and expires_at > NOW() limit 1',
            [$selector]
        );

        if (!$result->count()) {
            $this->deleteRememberCookie();

            return false;
        }

        $row = $result->first();

        if (!password_verify($validator, $row->hashed_validator)) {
            $this->deleteRememberTokenBySelector($selector);
            $this->deleteRememberCookie();

            return false;
        }

        $user_id = $row->user_id;

        if (!$this->find($row->user_id)) {
            return false;
        }

        if (!$this->deleteRememberTokenBySelector($selector)) {
            return false;
        }

        if (!$this->createRememberToken($row->user_id)) {
            return false;
        }

        if (!$this->createUserSession($row->user_id)) {
            return false;
        }

        $this->logged_in = true;

        return $user_id;
    }

    public function deleteRememberTokenBySelector($selector)
    {
        if ($this->db->delete('remember_tokens', ['selector' => $selector])) {
            return true;
        }

        return false;
    }

    public function deleteRememberToken()
    {
        if ($this->exists()) {
            $user_id = $this->data()->user_id;

            $this->db->delete('remember_tokens', ['remember_user' => $user_id]);
        }

        $this->deleteRememberCookie();
    }

    public function deleteRememberCookie()
    {
        Cookie::delete($this->cookie_name);
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if (!$username && !$password && $this->exists()) {
            $this->createUserSession($this->data()->user_id);

            $this->logged_in = true;

            return true;
        }

        $user = $this->find($username);

        if ($user) {
            if (Hash::verifyPassword($password, $this->data()->user_password)) {
                $this->createUserSession($this->data()->user_id);

                if($remember) {
                    $this->createRememberToken($this->data()->user_id);
                }

                $this->logged_in = true;

                return true;
            }
        }

        return false;
    }

    public function logout()
    {
        $this->deleteRememberToken();

        $this->deleteUserSession();

        Session::destroy();
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
        return $this->db->insert('follows', $follow);
    }

    public function unfollow($follow)
    {
        return $this->db->delete('follows', ['follow_user' => $follow['follow_user'], 'follow_following' => $follow['follow_following']]);
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
