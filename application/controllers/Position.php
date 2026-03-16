<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Position extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->_check_login();
        $this->load->model('Position_model');
    }

    private function _check_login()
    {
        if (!$this->session->userdata('logged_in')) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => false, 'message' => 'Session expired.']);
                exit;
            }
            redirect('login');
        }
    }

    private function _is_admin()
    {
        return $this->session->userdata('role') === 'administrator';
    }

    // ===== PAGES =====

    public function page_list()
    {
        $data['title'] = 'Data Posisi';
        $data['user'] = $this->session->userdata();
        $this->load->view('layout/header', $data);
        $this->load->view('positions/list', $data);
        $this->load->view('layout/footer', $data);
    }

    public function page_form($id = null)
    {
        if (!$this->_is_admin())
            redirect('positions');
        $data['title'] = $id ? 'Edit Posisi' : 'Tambah Posisi';
        $data['user'] = $this->session->userdata();
        $data['position_id'] = $id;
        $this->load->view('layout/header', $data);
        $this->load->view('positions/form', $data);
        $this->load->view('layout/footer', $data);
    }

    // ===== APIs =====

    public function list_data()
    {
        $limit = (int) $this->input->get('limit') ?: 10;
        $offset = (int) $this->input->get('offset') ?: 0;
        $search = $this->input->get('search', TRUE) ?: '';
        $sort_by = $this->input->get('sort_by') ?: 'id';
        $sort_dir = $this->input->get('sort_dir') ?: 'ASC';

        $data = $this->Position_model->get_all($limit, $offset, $search, $sort_by, $sort_dir);
        $total = $this->Position_model->count_all($search);

        echo json_encode(['status' => true, 'data' => $data, 'total' => $total]);
    }

    public function get($id)
    {
        $pos = $this->Position_model->get_by_id($id);
        if ($pos) {
            echo json_encode(['status' => true, 'data' => $pos]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Posisi tidak ditemukan.']);
        }
    }

    public function get_all()
    {
        $positions = $this->Position_model->get_all_active();
        echo json_encode(['status' => true, 'data' => $positions]);
    }

    public function create()
    {
        if (!$this->_is_admin()) {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak.']);
            return;
        }

        $this->form_validation->set_data($this->input->post());
        $this->form_validation->set_rules('name', 'Nama Posisi', 'required|max_length[100]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['status' => false, 'message' => 'Validasi gagal.', 'errors' => $this->form_validation->error_array()]);
            return;
        }

        $name = $this->input->post('name', TRUE);
        if ($this->Position_model->name_exists($name)) {
            echo json_encode(['status' => false, 'message' => 'Nama posisi sudah digunakan.']);
            return;
        }

        $id = $this->Position_model->create(['name' => $name]);
        if ($id) {
            echo json_encode(['status' => true, 'message' => 'Posisi berhasil ditambahkan.', 'id' => $id]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menambahkan posisi.']);
        }
    }

    public function update($id)
    {
        if (!$this->_is_admin()) {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak.']);
            return;
        }

        $pos = $this->Position_model->get_by_id($id);
        if (!$pos) {
            echo json_encode(['status' => false, 'message' => 'Posisi tidak ditemukan.']);
            return;
        }

        $this->form_validation->set_data($this->input->post());
        $this->form_validation->set_rules('name', 'Nama Posisi', 'required|max_length[100]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['status' => false, 'message' => 'Validasi gagal.', 'errors' => $this->form_validation->error_array()]);
            return;
        }

        $name = $this->input->post('name', TRUE);
        if ($this->Position_model->name_exists($name, $id)) {
            echo json_encode(['status' => false, 'message' => 'Nama posisi sudah digunakan.']);
            return;
        }

        if ($this->Position_model->update($id, ['name' => $name])) {
            echo json_encode(['status' => true, 'message' => 'Posisi berhasil diperbarui.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal memperbarui posisi.']);
        }
    }

    public function delete($id)
    {
        if (!$this->_is_admin()) {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak.']);
            return;
        }

        $pos = $this->Position_model->get_by_id($id);
        if (!$pos) {
            echo json_encode(['status' => false, 'message' => 'Posisi tidak ditemukan.']);
            return;
        }

        // Check if position is in use
        $this->db->where('position_id', $id);
        $usageCount = $this->db->count_all_results('employee_position_history');
        if ($usageCount > 0) {
            echo json_encode(['status' => false, 'message' => 'Posisi tidak dapat dihapus karena masih digunakan dalam riwayat jabatan karyawan.']);
            return;
        }

        if ($this->Position_model->delete($id)) {
            echo json_encode(['status' => true, 'message' => 'Posisi berhasil dihapus.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus posisi.']);
        }
    }
}
