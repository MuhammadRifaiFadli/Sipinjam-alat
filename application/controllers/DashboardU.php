<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboardu extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Cek apakah user sudah login
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        // Cek apakah user adalah member/user biasa
        if ($this->ion_auth->is_admin()) {
            redirect('dashboard');
        }
    }

    public function index() {
        $data['title'] = 'Dashboard User';
        $data['user'] = $this->ion_auth->user()->row();
        
        // Query untuk mengambil semua alat yang tersedia
        $data['alat'] = $this->db
            ->select('alat.*, COUNT(p.id) as total_dipinjam')
            ->from('alat')
            ->join('peminjaman p', 'p.alat_id = alat.id AND p.status = "Dipinjam"', 'left')
            ->where('alat.stok >', 0)
            ->group_by('alat.id')
            ->get()
            ->result();

        // Mengambil peminjaman aktif user
        $data['peminjaman_aktif'] = $this->db
            ->select('peminjaman.*, alat.nama as nama_alat, alat.kode_seri')
            ->from('peminjaman')
            ->join('alat', 'alat.id = peminjaman.alat_id')
            ->where('peminjaman.user_id', $data['user']->id)
            ->where('peminjaman.status', 'Dipinjam')
            ->get()
            ->result();

        // Load view
        $this->load->view('inc/navbar', $data);
        $this->load->view('user/dashboardu', $data);
        $this->load->view('inc/footer');
    }
}