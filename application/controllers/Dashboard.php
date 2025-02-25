<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Load required libraries
        $this->load->library('ion_auth');
        $this->load->library('session');

        // Check if user is logged in
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        // Check if user is admin
        if (!$this->ion_auth->is_admin()) {
            redirect('user_dashboard');
        }
    }

    public function index() {
        // Get total equipment count
        $total_alat = $this->db
            ->from('alat')
            ->count_all_results();
        
        // Get total borrowed equipment
        $total_dipinjam = $this->db
            ->where('status', 'Dipinjam')
            ->from('peminjaman')
            ->count_all_results();
        
        // Get total available equipment
        $total_tersedia = $this->db
            ->where('status', 'Tersedia')
            ->from('alat')
            ->count_all_results();
        
        // Get total damaged equipment
        $total_rusak = $this->db
            ->where('status', 'Rusak')
            ->from('alat')
            ->count_all_results();
        
        // Get recent loans (limit 10)
        $peminjaman_terbaru = $this->db
            ->select('peminjaman.*, CONCAT(users.first_name, " ", users.last_name) as nama_peminjam, alat.nama as nama_alat, alat.kode_seri')
            ->from('peminjaman')
            ->join('users', 'users.id = peminjaman.user_id')
            ->join('alat', 'alat.id = peminjaman.alat_id')
            ->order_by('tanggal_pinjam', 'DESC')
            ->limit(10)
            ->get()
            ->result();
        
        // Get overdue loans
        $peminjaman_terlambat = $this->db
            ->select('peminjaman.*, CONCAT(users.first_name, " ", users.last_name) as nama_peminjam, alat.nama as nama_alat, alat.kode_seri')
            ->from('peminjaman')
            ->join('users', 'users.id = peminjaman.user_id')
            ->join('alat', 'alat.id = peminjaman.alat_id')
            ->where('peminjaman.status', 'Terlambat')
            ->get()
            ->result();
        
        // Get recent returns
        $pengembalian_terbaru = $this->db
            ->select('pengembalian.*, peminjaman.stok_dipinjam, CONCAT(users.first_name, " ", users.last_name) as nama_peminjam, alat.nama as nama_alat')
            ->from('pengembalian')
            ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->join('alat', 'alat.id = peminjaman.alat_id')
            ->order_by('tanggal_pengembalian', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        $data = [
            'total_alat' => $total_alat,
            'total_dipinjam' => $total_dipinjam,
            'total_tersedia' => $total_tersedia,
            'total_rusak' => $total_rusak,
            'peminjaman_terbaru' => $peminjaman_terbaru,
            'peminjaman_terlambat' => $peminjaman_terlambat,
            'pengembalian_terbaru' => $pengembalian_terbaru
        ];

        $this->load->view('inc/header');
        $this->load->view('inc/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('inc/footer');
    }

    public function detail_peminjaman($id) {
        $peminjaman = $this->db
            ->select('peminjaman.*, CONCAT(users.first_name, " ", users.last_name) as nama_peminjam, 
                     users.email, alat.nama as nama_alat, alat.kode_seri, alat.lokasi')
            ->from('peminjaman')
            ->join('users', 'users.id = peminjaman.user_id')
            ->join('alat', 'alat.id = peminjaman.alat_id')
            ->where('peminjaman.id', $id)
            ->get()
            ->row();

        if (!$peminjaman) {
            $this->session->set_flashdata('error', 'Data peminjaman tidak ditemukan');
            redirect('dashboard');
        }

        // Get return data if exists
        $pengembalian = $this->db
            ->where('peminjaman_id', $id)
            ->get('pengembalian')
            ->row();

        $data = [
            'peminjaman' => $peminjaman,
            'pengembalian' => $pengembalian
        ];

        $this->load->view('inc/header');
        $this->load->view('inc/sidebar');
        $this->load->view('detail_peminjaman', $data);
        $this->load->view('inc/footer');
    }

    public function proses_pengembalian() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('peminjaman_id', 'ID Peminjaman', 'required');
        $this->form_validation->set_rules('kondisi', 'Kondisi', 'required|in_list[Baik,Rusak]');
        $this->form_validation->set_rules('denda', 'Denda', 'numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard');
        }

        // Start transaction
        $this->db->trans_start();

        // Get peminjaman data
        $peminjaman = $this->db
            ->where('id', $this->input->post('peminjaman_id'))
            ->get('peminjaman')
            ->row();

        if (!$peminjaman) {
            $this->session->set_flashdata('error', 'Data peminjaman tidak ditemukan');
            redirect('dashboard');
        }

        // Insert pengembalian data
        $pengembalian_data = [
            'peminjaman_id' => $this->input->post('peminjaman_id'),
            'kondisi' => $this->input->post('kondisi'),
            'denda' => $this->input->post('denda') ?? 0
        ];
        $this->db->insert('pengembalian', $pengembalian_data);

        // Update peminjaman status
        $this->db->update('peminjaman', 
            ['status' => 'Dikembalikan', 'tanggal_kembali' => date('Y-m-d H:i:s')],
            ['id' => $peminjaman->id]
        );

        // Update alat status and stock
        $status_alat = $this->input->post('kondisi') == 'Rusak' ? 'Rusak' : 'Tersedia';
        $this->db->update('alat',
            ['status' => $status_alat],
            ['id' => $peminjaman->alat_id]
        );

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal memproses pengembalian');
        } else {
            $this->session->set_flashdata('success', 'Pengembalian berhasil diproses');
        }

        redirect('dashboard');
    }

    public function update_status_peminjaman($id, $status) {
        if (!in_array($status, ['Dipinjam', 'Dikembalikan', 'Terlambat'])) {
            $this->session->set_flashdata('error', 'Status tidak valid');
            redirect('dashboard');
        }

        $update = $this->db->update(
            'peminjaman',
            ['status' => $status],
            ['id' => $id]
        );

        if ($update) {
            $this->session->set_flashdata('success', 'Status peminjaman berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate status peminjaman');
        }

        redirect('dashboard');
    }
}