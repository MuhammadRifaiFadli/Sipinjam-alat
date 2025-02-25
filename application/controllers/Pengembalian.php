<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengembalian extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        $this->load->database();
    }

    public function index() {
        $query = $this->db->query("SELECT p.*, a.nama as nama_alat, u.first_name FROM peminjaman p JOIN alat a ON p.alat_id = a.id JOIN users u ON p.user_id = u.id WHERE p.status = 'Dipinjam'");
        $data['peminjaman_aktif'] = $query->result();
        $this->load->view('inc/header');
        $this->load->view('inc/sidebar');
        $this->load->view('admin/pengembalian', $data);
        $this->load->view('inc/footer');
    }

    public function proses_pengembalian() {
        $peminjaman_id = $this->input->post('peminjaman_id');
        $kondisi = $this->input->post('kondisi');

        $this->db->trans_start();

        try {
            $peminjaman = $this->db->get_where('peminjaman', ['id' => $peminjaman_id])->row();
            if (!$peminjaman) {
                throw new Exception('Data peminjaman tidak ditemukan');
            }

            $this->db->insert('pengembalian', [
                'peminjaman_id' => $peminjaman_id,
                'kondisi' => $kondisi,
            ]);

            $this->db->where('id', $peminjaman_id);
            $this->db->update('peminjaman', [
                'status' => 'Dikembalikan',
                'tanggal_kembali' => date('Y-m-d H:i:s')
            ]);

            $alat = $this->db->get_where('alat', ['id' => $peminjaman->alat_id])->row();
            $new_stok = $alat->stok + $peminjaman->stok_dipinjam;
            
            $this->db->where('id', $peminjaman->alat_id);
            $this->db->update('alat', [
                'stok' => $new_stok,
                'status' => ($kondisi == 'Rusak') ? 'Rusak' : 'Tersedia'
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memproses pengembalian');
            }

            $this->session->set_flashdata('success', 'Pengembalian berhasil diproses');
            redirect('pengembalian');

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', $e->getMessage());
            redirect('pengembalian');
        }
    }

    public function detail($id) {
        $query = $this->db->query("SELECT p.*, a.nama as nama_alat, a.kode_seri, u.first_name, pk.kondisi, pk.tanggal_pengembalian FROM peminjaman p JOIN alat a ON p.alat_id = a.id JOIN users u ON p.user_id = u.id LEFT JOIN pengembalian pk ON p.id = pk.peminjaman_id WHERE p.id = ?", [$id]);
        $data['detail'] = $query->row();
        if (!$data['detail']) {
            show_404();
        }
        $this->load->view('pengembalian/detail', $data);
    }

    public function get_peminjaman_detail($id) {
        $query = $this->db->query("SELECT p.*, a.nama as nama_alat, a.kode_seri, u.first_name, DATEDIFF(CURRENT_TIMESTAMP, p.tanggal_pinjam) as hari_terlambat FROM peminjaman p JOIN alat a ON p.alat_id = a.id JOIN users u ON p.user_id = u.id WHERE p.id = ?", [$id]);
        $detail = $query->row();
        if (!$detail) {
            $this->output->set_status_header(404);
            echo json_encode(['error' => 'Data tidak ditemukan']);
            return;
        }
        echo json_encode($detail);
    } 
}
