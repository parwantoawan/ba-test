<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan_model extends CI_Model
{

    private $table = 'jabatan';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all jabatan with pagination and search
     */
    public function get_all($limit = 10, $offset = 0, $search = '', $sort_by = 'id', $sort_dir = 'ASC')
    {
        $allowed_sort = ['id', 'nama_jabatan', 'deskripsi'];
        if (!in_array($sort_by, $allowed_sort)) {
            $sort_by = 'id';
        }
        $sort_dir = strtoupper($sort_dir) === 'DESC' ? 'DESC' : 'ASC';

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('nama_jabatan', $search);
            $this->db->or_like('deskripsi', $search);
            $this->db->group_end();
        }

        $this->db->order_by($sort_by, $sort_dir);
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Count total records (with optional search)
     */
    public function count_all($search = '')
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('nama_jabatan', $search);
            $this->db->or_like('deskripsi', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results($this->table);
    }

    /**
     * Get all active jabatan (for dropdown)
     */
    public function get_all_active()
    {
        $this->db->where('is_active', 'active');
        $this->db->order_by('nama_jabatan', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get single jabatan by ID
     */
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    /**
     * Check if nama_jabatan exists (exclude current id for updates)
     */
    public function name_exists($nama, $exclude_id = null)
    {
        $this->db->where('nama_jabatan', $nama);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Create new jabatan
     */
    public function create($data)
    {
        $this->db->insert($this->table, $data);
        $query = $this->db->query("SELECT SCOPE_IDENTITY() AS id");
        $row = $query->row();
        return $row ? $row->id : false;
    }

    /**
     * Update jabatan
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete jabatan
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get total count
     */
    public function get_total()
    {
        return $this->db->count_all_results($this->table);
    }
}
