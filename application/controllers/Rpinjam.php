<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpinjam extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('peminjaman.*, users.first_name, users.last_name, alat.nama AS nama_alat');
        $this->db->from('peminjaman');
        $this->db->join('users', 'users.id = peminjaman.user_id');
        $this->db->join('alat', 'alat.id = peminjaman.alat_id');
        $this->db->where('peminjaman.user_id', $user_id);
        $data['peminjaman'] = $this->db->get()->result();
        
        $this->load->view('inc/navbar');
        $this->load->view('user/rpinjam', $data);
        $this->load->view('inc/footer');
    }
}