<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{

    private $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Authenticate user
     */
    public function authenticate($username, $password)
    {
        $user = $this->db->get_where($this->table, [
            'username' => $username,
            'is_active' => 'active'
        ])->row_array();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * Get user by ID
     */
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    /**
     * Get user by username
     */
    public function get_by_username($username)
    {
        return $this->db->get_where($this->table, ['username' => $username])->row_array();
    }

    /**
     * Get total user count
     */
    public function count_all()
    {
        return $this->db->count_all_results($this->table);
    }
}
