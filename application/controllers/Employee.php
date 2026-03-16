<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->_check_login();
        $this->load->model('Employee_model');
        $this->load->model('Jabatan_model');
        $this->load->model('Position_model');
        $this->load->model('Position_history_model');
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

    // ===== PAGES =====

    /**
     * Employee list page
     */
    public function page_list()
    {
        $data['title'] = 'Data Karyawan';
        $data['user'] = $this->session->userdata();
        $this->load->view('layout/header', $data);
        $this->load->view('employee/list', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Employee form page (add/edit)
     */
    public function page_form($id = null)
    {
        if (!$this->_is_admin()) {
            redirect('employees');
        }

        $data['title'] = $id ? 'Edit Karyawan' : 'Tambah Karyawan';
        $data['user'] = $this->session->userdata();
        $data['employee_id'] = $id;
        $data['jabatan_list'] = $this->Jabatan_model->get_all_active();
        $data['position_list'] = $this->Position_model->get_all_active();
        $this->load->view('layout/header', $data);
        $this->load->view('employee/form', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Employee history page
     */
    public function page_history($id = null)
    {
        $data['title'] = 'Riwayat Jabatan Karyawan';
        $data['user'] = $this->session->userdata();
        $data['employee_id'] = $id;
        $data['employees'] = $this->Employee_model->get_all_simple();
        $data['position_list'] = $this->Position_model->get_all_active();
        $this->load->view('layout/header', $data);
        $this->load->view('employee/history', $data);
        $this->load->view('layout/footer', $data);
    }

    // ===== API =====

    /**
     * API: List employees with pagination and search
     */
    public function list_data()
    {
        $limit = (int) $this->input->get('limit') ?: 10;
        $offset = (int) $this->input->get('offset') ?: 0;
        $search = $this->input->get('search', TRUE) ?: '';
        $sort_by = $this->input->get('sort_by') ?: 'id';
        $sort_dir = $this->input->get('sort_dir') ?: 'ASC';

        $employees = $this->Employee_model->get_all($limit, $offset, $search, $sort_by, $sort_dir);
        $total = $this->Employee_model->count_all($search);

        echo json_encode([
            'status' => true,
            'data' => $employees,
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * API: Get single employee
     */
    public function get($id)
    {
        $employee = $this->Employee_model->get_by_id($id);
        if ($employee) {
            echo json_encode(['status' => true, 'data' => $employee]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Karyawan tidak ditemukan.']);
        }
    }

    /**
     * API: Create employee (with initial position history)
     */
    public function create()
    {
        if (!$this->_is_admin()) {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak. Hanya administrator yang dapat menambah data.']);
            return;
        }

        $this->form_validation->set_data($this->input->post());
        $this->form_validation->set_rules('nip', 'NIP', 'required|max_length[20]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|max_length[100]');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[Laki-laki,Perempuan]');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required|max_length[100]');
        $this->form_validation->set_rules('tanggal_aktif_jabatan', 'Tanggal Aktif Jabatan', 'required');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('status_karyawan', 'Status Karyawan', 'required|in_list[Tetap,Kontrak]');
        $this->form_validation->set_rules('is_active', 'Status Aktif', 'required|in_list[active,inactive]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $this->form_validation->error_array()
            ]);
            return;
        }

        // Check NIP uniqueness
        $nip = $this->input->post('nip', TRUE);
        if ($this->Employee_model->nip_exists($nip)) {
            echo json_encode(['status' => false, 'message' => 'NIP sudah digunakan.']);
            return;
        }

        $jabatan = $this->input->post('jabatan', TRUE);
        $tanggal_aktif = $this->input->post('tanggal_aktif_jabatan', TRUE);

        $data = [
            'nip' => $nip,
            'nama' => $this->input->post('nama', TRUE),
            'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
            'jabatan' => $jabatan,
            'tanggal_aktif_jabatan' => $tanggal_aktif,
            'tanggal_masuk' => $this->input->post('tanggal_masuk', TRUE),
            'status_karyawan' => $this->input->post('status_karyawan', TRUE),
            'is_active' => $this->input->post('is_active', TRUE),
        ];

        $id = $this->Employee_model->create($data);
        if ($id) {
            // Create initial position history record
            $position = $this->Position_model->get_by_name($jabatan);
            if ($position) {
                $this->Position_history_model->create([
                    'employee_id' => $id,
                    'position_id' => $position['id'],
                    'start_date' => $tanggal_aktif,
                    'end_date' => null
                ]);
            }
            echo json_encode(['status' => true, 'message' => 'Karyawan berhasil ditambahkan.', 'id' => $id]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menambahkan karyawan.']);
        }
    }

    /**
     * API: Update employee (with position change tracking)
     */
    public function update($id)
    {
        if (!$this->_is_admin()) {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak. Hanya administrator yang dapat mengedit data.']);
            return;
        }

        $employee = $this->Employee_model->get_by_id($id);
        if (!$employee) {
            echo json_encode(['status' => false, 'message' => 'Karyawan tidak ditemukan.']);
            return;
        }

        $this->form_validation->set_data($this->input->post());
        $this->form_validation->set_rules('nip', 'NIP', 'required|max_length[20]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|max_length[100]');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[Laki-laki,Perempuan]');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required|max_length[100]');
        $this->form_validation->set_rules('tanggal_aktif_jabatan', 'Tanggal Aktif Jabatan', 'required');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('status_karyawan', 'Status Karyawan', 'required|in_list[Tetap,Kontrak]');
        $this->form_validation->set_rules('is_active', 'Status Aktif', 'required|in_list[active,inactive]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $this->form_validation->error_array()
            ]);
            return;
        }

        // Check NIP uniqueness (exclude current)
        $nip = $this->input->post('nip', TRUE);
        if ($this->Employee_model->nip_exists($nip, $id)) {
            echo json_encode(['status' => false, 'message' => 'NIP sudah digunakan.']);
            return;
        }

        $new_jabatan = $this->input->post('jabatan', TRUE);
        $new_tanggal_aktif = $this->input->post('tanggal_aktif_jabatan', TRUE);
        $old_jabatan = $employee['jabatan'];

        $data = [
            'nip' => $nip,
            'nama' => $this->input->post('nama', TRUE),
            'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
            'jabatan' => $new_jabatan,
            'tanggal_aktif_jabatan' => $new_tanggal_aktif,
            'tanggal_masuk' => $this->input->post('tanggal_masuk', TRUE),
            'status_karyawan' => $this->input->post('status_karyawan', TRUE),
            'is_active' => $this->input->post('is_active', TRUE),
        ];

        if ($this->Employee_model->update($id, $data)) {
            // If position changed, track it in history
            if ($old_jabatan !== $new_jabatan) {
                // Close current position record
                $this->Position_history_model->close_current($id, $new_tanggal_aktif);

                // Create new position history record
                $position = $this->Position_model->get_by_name($new_jabatan);
                if ($position) {
                    $this->Position_history_model->create([
                        'employee_id' => $id,
                        'position_id' => $position['id'],
                        'start_date' => $new_tanggal_aktif,
                        'end_date' => null
                    ]);
                }
            }
            echo json_encode(['status' => true, 'message' => 'Karyawan berhasil diperbarui.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal memperbarui karyawan.']);
        }
    }

    /**
     * API: Delete employee (and related history)
     */
    public function delete($id)
    {
        if (!$this->_is_admin()) {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak. Hanya administrator yang dapat menghapus data.']);
            return;
        }

        $employee = $this->Employee_model->get_by_id($id);
        if (!$employee) {
            echo json_encode(['status' => false, 'message' => 'Karyawan tidak ditemukan.']);
            return;
        }

        // Delete position history first (FK constraint)
        $this->Position_history_model->delete_by_employee($id);

        if ($this->Employee_model->delete($id)) {
            echo json_encode(['status' => true, 'message' => 'Karyawan berhasil dihapus.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus karyawan.']);
        }
    }

    /**
     * API: Get employee position history
     */
    public function history($id)
    {
        $employee = $this->Employee_model->get_by_id($id);
        if (!$employee) {
            echo json_encode(['status' => false, 'message' => 'Karyawan tidak ditemukan.']);
            return;
        }

        $history = $this->Position_history_model->get_by_employee($id);

        echo json_encode([
            'status' => true,
            'data' => [
                'employee' => $employee,
                'history' => $history
            ]
        ]);
    }
}
