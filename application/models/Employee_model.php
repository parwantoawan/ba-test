<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model
{

    private $table = 'employees';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all employees with pagination, search, and sorting
     */
    public function get_all($limit = 10, $offset = 0, $search = '', $sort_by = 'id', $sort_dir = 'ASC')
    {
        $allowed_sort = ['id', 'nip', 'nama', 'nama_jabatan', 'status_karyawan', 'tanggal_masuk'];
        if (!in_array($sort_by, $allowed_sort)) {
            $sort_by = 'id';
        }
        $sort_dir = strtoupper($sort_dir) === 'DESC' ? 'DESC' : 'ASC';

        $this->db->select('employees.*, jabatan.nama_jabatan as jabatan');
        $this->db->join('jabatan', 'employees.jabatan_id = jabatan.id', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('employees.nip', $search);
            $this->db->or_like('employees.nama', $search);
            $this->db->or_like('jabatan.nama_jabatan', $search);
            $this->db->or_like('employees.status_karyawan', $search);
            $this->db->group_end();
        }

        $this->db->order_by($sort_by === 'nama_jabatan' ? 'jabatan.nama_jabatan' : 'employees.'.$sort_by, $sort_dir);
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get all employees (simple list for dropdowns)
     */
    public function get_all_simple()
    {
        $this->db->select('id, nip, nama');
        $this->db->where('is_active', 'active');
        $this->db->order_by('nama', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Count total records (with optional search)
     */
    public function count_all($search = '')
    {
        $this->db->from($this->table);
        $this->db->join('jabatan', 'employees.jabatan_id = jabatan.id', 'left');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('employees.nip', $search);
            $this->db->or_like('employees.nama', $search);
            $this->db->or_like('jabatan.nama_jabatan', $search);
            $this->db->or_like('employees.status_karyawan', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    /**
     * Get single employee by ID
     */
    public function get_by_id($id)
    {
        $this->db->select('employees.*, jabatan.nama_jabatan as jabatan');
        $this->db->join('jabatan', 'employees.jabatan_id = jabatan.id', 'left');
        $this->db->where('employees.id', $id);
        return $this->db->get($this->table)->row_array();
    }

    /**
     * Check if NIP exists (exclude current id for updates)
     */
    public function nip_exists($nip, $exclude_id = null)
    {
        $this->db->where('nip', $nip);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Create new employee
     */
    public function create($data)
    {
        $this->db->insert($this->table, $data);
        // For SQL Server with IDENTITY, get the last insert id
        $query = $this->db->query("SELECT SCOPE_IDENTITY() AS id");
        $row = $query->row();
        return $row ? $row->id : false;
    }

    /**
     * Update employee
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete employee
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get statistics (includes gender breakdown)
     */
    public function get_stats()
    {
        $total = $this->db->count_all_results($this->table);

        $this->db->where('is_active', 'active');
        $active = $this->db->count_all_results($this->table);

        $this->db->where('is_active', 'inactive');
        $inactive = $this->db->count_all_results($this->table);

        $this->db->where('status_karyawan', 'Permanen');
        $tetap = $this->db->count_all_results($this->table);

        $this->db->where('status_karyawan', 'Kontrak');
        $kontrak = $this->db->count_all_results($this->table);

        $this->db->where('jenis_kelamin', 'Laki - Laki');
        $laki = $this->db->count_all_results($this->table);

        $this->db->where('jenis_kelamin', 'Perempuan');
        $perempuan = $this->db->count_all_results($this->table);

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'tetap' => $tetap,
            'kontrak' => $kontrak,
            'laki' => $laki,
            'perempuan' => $perempuan
        ];
    }
}
