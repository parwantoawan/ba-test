<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->_check_login();
        $this->load->model('Employee_model');
        $this->load->model('User_model');
        $this->load->model('Jabatan_model');
        $this->load->model('Position_model');
        $this->load->model('Position_history_model');
    }

    private function _check_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    /**
     * Dashboard page
     */
    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->session->userdata();
        $this->load->view('layout/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Dashboard statistics API
     */
    public function stats()
    {
        $employee_stats = $this->Employee_model->get_stats();
        $user_count = $this->User_model->count_all();
        $jabatan_count = $this->Jabatan_model->get_total();
        $position_count = $this->Position_model->get_total();

        $response = [
            'status' => true,
            'data' => [
                'employees' => $employee_stats,
                'users' => $user_count,
                'jabatan' => $jabatan_count,
                'positions' => $position_count
            ]
        ];

        // Admin gets extra data: latest position changes
        if ($this->session->userdata('role') === 'administrator') {
            $response['data']['latest_changes'] = $this->Position_history_model->get_latest_changes(5);
        }

        echo json_encode($response);
    }
}
