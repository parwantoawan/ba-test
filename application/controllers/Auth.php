<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    /**
     * Default - redirect to login or dashboard
     */
    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        redirect('login');
    }

    /**
     * Login page & handler
     */
    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            // AJAX login
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password');

            // Validation
            if (empty($username) || empty($password)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Username dan password wajib diisi.'
                ]);
                return;
            }

            $user = $this->User_model->authenticate($username, $password);

            if ($user) {
                $session_data = [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($session_data);

                echo json_encode([
                    'status' => true,
                    'message' => 'Login berhasil!',
                    'redirect' => base_url('dashboard')
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Username atau password salah.'
                ]);
            }
            return;
        }

        $this->load->view('auth/login');
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
