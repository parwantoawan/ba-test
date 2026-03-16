<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Position_history_model extends CI_Model
{

    private $table = 'employee_position_history';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get full history for an employee (ordered by start_date DESC)
     */
    public function get_by_employee($employee_id)
    {
        $this->db->select('eph.*, p.name as position_name');
        $this->db->from($this->table . ' eph');
        $this->db->join('positions p', 'p.id = eph.position_id', 'left');
        $this->db->where('eph.employee_id', $employee_id);
        $this->db->order_by('eph.start_date', 'DESC');
        $this->db->order_by('eph.id', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Get current (active) position for an employee
     */
    public function get_current($employee_id)
    {
        $this->db->select('eph.*, p.name as position_name');
        $this->db->from($this->table . ' eph');
        $this->db->join('positions p', 'p.id = eph.position_id', 'left');
        $this->db->where('eph.employee_id', $employee_id);
        $this->db->where('eph.end_date IS NULL', null, FALSE);
        return $this->db->get()->row_array();
    }

    /**
     * Close current position (set end_date)
     */
    public function close_current($employee_id, $end_date = null)
    {
        if (!$end_date)
            $end_date = date('Y-m-d');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('end_date IS NULL', null, FALSE);
        return $this->db->update($this->table, ['end_date' => $end_date]);
    }

    /**
     * Create new history record
     */
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $query = $this->db->query("SELECT SCOPE_IDENTITY() AS id");
        $row = $query->row();
        return $row ? $row->id : false;
    }

    /**
     * Get latest position changes across all employees (for admin dashboard)
     */
    public function get_latest_changes($limit = 10)
    {
        $this->db->select('eph.*, p.name as position_name, e.nama as employee_name, e.nip');
        $this->db->from($this->table . ' eph');
        $this->db->join('positions p', 'p.id = eph.position_id', 'left');
        $this->db->join('employees e', 'e.id = eph.employee_id', 'left');
        $this->db->order_by('eph.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Delete all history for an employee
     */
    public function delete_by_employee($employee_id)
    {
        $this->db->where('employee_id', $employee_id);
        return $this->db->delete($this->table);
    }
}
