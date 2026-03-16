<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->_check_login();
        $this->load->model('Jabatan_model');
    }

    private function _check_login()
    {
        if (!$this->session->userdata('logged_in')) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => false, 'message' => 'Session expired. Please login again.']);
                exit;
            }
            redirect('login');
        }
    }

    private function _is_admin()
    {
        return $this->session->userdata('role') === 'administrator';
    }

    /**
     * Jabatan list page
     */
    public function page_list()
    {
        $data['title'] = 'Data Jabatan';
        $data['user'] = $this->session->userdata();
        $this->load->view('layout/header', $data);
        $this->load->view('jabatan/list', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Jabatan form page (add/edit)
     */
    public function page_form($id = null)
    {
        if (!$this->_is_admin()) {
            redirect('jabatan');
        }

        $data['title'] = $id ? 'Edit Jabatan' : 'Tambah Jabatan';
        $data['user'] = $this->session->userdata();
        $data['jabatan_id'] = $id;
        $this->load->view('layout/header', $data);
        $this->load->view('jabatan/form', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * API: List jabatan with pagination and search
     */
    public function list_data()
    {
        $limit = (int) $this->input->get('limit') ?: 10;
        $offset = (int) $this->input->get('offset') ?: 0;
        $search = $this->input->get('search', TRUE) ?: '';
        $sort_by = $this->input->get('sort_by') ?: 'id';
        $sort_dir = $this->input->get('sort_dir') ?: 'ASC';

        $jabatan = $this->Jabatan_model->get_all($limit, $offset, $search, $sort_by, $sort_dir);
        $total = $this->Jabatan_model->count_all($search);

        echo json_encode([
            'status' => true,
            'data' => $jabatan,
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * API: Get all active jabatan (for dropdown)
     */
    public function get_all()
    {
        $jabatan = $this->Jabatan_model->get_all_active();
        echo json_encode(['status' => true, 'data' => $jabatan]);
    }

    /**
     * API: Get single jabatan
     */
    public function get($id)
    {
        $jabatan = $this->Jabatan_model->get_by_id($id);
        if ($jabatan) {
            echo json_encode(['status' => true, 'data' => $jabatan]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Jabatan tidak ditemukan.']);
        }
    }

    /**
     * API: Create jabatan
     */
    public function create()
    {
        if (!$this->_is_admin()) {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak. Hanya administrator yang dapat menambah data.']);
            return;
        }

        $this->form_validation->set_data($this->input->post());
        $this->form_validation->set_rules('nama_jabatan', 'Nama Jabatan', 'required|max_length[100]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'max_length[255]');
        $this->form_validation->set_rules('is_active', 'Status Aktif', 'required|in_list[active,inactive]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $this->form_validation->error_array()
            ]);
            return;
        }

        $nama = $this->input->post('nama_jabatan', TRUE);
        if ($this->Jabatan_model->name_exists($nama)) {
            echo json_encode(['status' => false, 'message' => 'Nama jabatan sudah digunakan.']);
            return;
        }

        $data = [
            'nama_jabatan' => $nama,
            'deskripsi' => $this->input->post('deskripsi', TRUE),
            'is_active' => $this->input->post('is_active', TRUE),
        ];

        $id = $this->Jabatan_model->create($data);
        if ($id) {
            echo json_encode(['status' => true, 'message' => 'Jabatan berhasil ditambahkan.', 'id' => $id]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menambahkan jabatan.']);
        }
    }

    /**
     * API: Update jabatan
     */
    public function update($id)
    {
        if (!$this->_is_admin()) {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak. Hanya administrator yang dapat mengedit data.']);
            return;
        }

        $jabatan = $this->Jabatan_model->get_by_id($id);
        if (!$jabatan) {
            echo json_encode(['status' => false, 'message' => 'Jabatan tidak ditemukan.']);
            return;
        }

        $this->form_validation->set_data($this->input->post());
        $this->form_validation->set_rules('nama_jabatan', 'Nama Jabatan', 'required|max_length[100]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'max_length[255]');
        $this->form_validation->set_rules('is_active', 'Status Aktif', 'required|in_list[active,inactive]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $this->form_validation->error_array()
            ]);
            return;
        }

        $nama = $this->input->post('nama_jabatan', TRUE);
        if ($this->Jabatan_model->name_exists($nama, $id)) {
            echo json_encode(['status' => false, 'message' => 'Nama jabatan sudah digunakan.']);
            return;
        }

        $data = [
            'nama_jabatan' => $nama,
            'deskripsi' => $this->input->post('deskripsi', TRUE),
            'is_active' => $this->input->post('is_active', TRUE),
        ];

        if ($this->Jabatan_model->update($id, $data)) {
            echo json_encode(['status' => true, 'message' => 'Jabatan berhasil diperbarui.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal memperbarui jabatan.']);
        }
    }

    /**
     * API: Delete jabatan
     */
    public function delete($id)
    {
        if (!$this->_is_admin()) {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak. Hanya administrator yang dapat menghapus data.']);
            return;
        }

        $jabatan = $this->Jabatan_model->get_by_id($id);
        if (!$jabatan) {
            echo json_encode(['status' => false, 'message' => 'Jabatan tidak ditemukan.']);
            return;
        }

        if ($this->Jabatan_model->delete($id)) {
            echo json_encode(['status' => true, 'message' => 'Jabatan berhasil dihapus.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus jabatan.']);
        }
    }
}
