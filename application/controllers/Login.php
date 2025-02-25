<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function index()
    {
        $this->load->view('auth/my_login');
    }

    public function login_proses()
    {
        $remember = (bool)$this->input->post('remember');
        $identity = $this->input->post('identity');
        $password = $this->input->post('password');

        $login_query = $this->ion_auth->login($identity, $password, $remember);

        if ($login_query == true) {
            if ($this->ion_auth->is_admin() == true) {
                redirect('dashboard');
            }
            if ($this->ion_auth->is_admin() == false) {
                redirect('dashboardu');
            }
        } else {
            redirect('login/index');
        }
    }

    public function register()
    {
        $this->load->view('auth/my_register');
    }

    public function register_proses()
    {
        $username = $this->input->post('identity');
        $password = $this->input->post('password');
        $email = $this->input->post('email');
        $additional_data = array(
            'username' => $this->input->post('identity'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'phone' => $this->input->post('phone')
        );  
        $group = array('2');

        $register_query = $this->ion_auth->create_user($username, $password, $email, $additional_data, $group);

        if ($register_query == true) {
            redirect('login/login_proses');
        } else {
            redirect('login/register');
        }
    }

    public function logout_proses()
    {
        if ($this->ion_auth->logout())
        {
            redirect('auth/index');
        }
    }
}
