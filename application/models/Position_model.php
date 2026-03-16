<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Position_model extends CI_Model
{

    private $table = 'positions';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($limit = 10, $offset = 0, $search = '', $sort_by = 'id', $sort_dir = 'ASC')
    {
        $allowed_sort = ['id', 'name', 'created_at'];
        if (!in_array($sort_by, $allowed_sort))
            $sort_by = 'id';
        $sort_dir = strtoupper($sort_dir) === 'DESC' ? 'DESC' : 'ASC';

        if (!empty($search)) {
            $this->db->like('name', $search);
        }
        $this->db->order_by($sort_by, $sort_dir);
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    public function count_all($search = '')
    {
        if (!empty($search)) {
            $this->db->like('name', $search);
        }
        return $this->db->count_all_results($this->table);
    }

    public function get_all_active()
    {
        $this->db->order_by('name', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function get_by_name($name)
    {
        return $this->db->get_where($this->table, ['name' => $name])->row_array();
    }

    public function name_exists($name, $exclude_id = null)
    {
        $this->db->where('name', $name);
        if ($exclude_id)
            $this->db->where('id !=', $exclude_id);
        return $this->db->count_all_results($this->table) > 0;
    }

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $query = $this->db->query("SELECT SCOPE_IDENTITY() AS id");
        $row = $query->row();
        return $row ? $row->id : false;
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function get_total()
    {
        return $this->db->count_all_results($this->table);
    }
}
