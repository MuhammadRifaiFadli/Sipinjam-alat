<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat extends CI_Controller {

    public function index() {
        // Query untuk mengambil semua data pengembalian
        // Menggunakan join untuk mendapatkan data terkait dari tabel peminjaman dan users
        $query = $this->db->query("
            SELECT 
                pengembalian.*,
                peminjaman.*,
                CONCAT(users.first_name, users.last_name) AS peminjaman_id
            FROM pengembalian 
            JOIN peminjaman ON pengembalian.peminjaman_id = peminjaman.id
            JOIN users ON peminjaman.user_id = users.id
            ORDER BY pengembalian.tanggal_pengembalian DESC
        ");
    
        // Menyiapkan data untuk view
        $data['pengembalian'] = $query->result();
        
        // Memuat view dengan data
        $this->load->view('inc/header'); 
        $this->load->view('inc/sidebar'); 
        $this->load->view('admin/riwayat', $data);
        $this->load->view('inc/footer'); 
    }
}    