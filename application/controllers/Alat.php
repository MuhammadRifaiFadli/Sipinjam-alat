<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        if (!$this->ion_auth->is_admin()) {
            redirect('user_dashboard');
        }
    }

    public function index() {
        // Get all equipment data
        $alat = $this->db
            ->select('alat.*, COUNT(peminjaman.id) as total_dipinjam')
            ->from('alat')
            ->join('peminjaman', 'peminjaman.alat_id = alat.id AND peminjaman.status = "Dipinjam"', 'left')
            ->group_by('alat.id')
            ->order_by('alat.nama', 'ASC')
            ->get()
            ->result();

        $data = [
            'alat' => $alat
        ];

        $this->load->view('inc/header');
        $this->load->view('inc/sidebar');
        $this->load->view('admin/alat', $data);
        $this->load->view('inc/footer');
    }

    public function store() {
        $this->form_validation->set_rules('nama', 'Nama Alat', 'required|trim');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('alat'); // Ganti dengan halaman form tambah alat
        } else {
            $data = [
                'nama' => $this->input->post('nama'),
                'deskripsi' => $this->input->post('deskripsi'),
                'stok' => $this->input->post('stok'),
                'kode_seri' => $this->input->post('kode_seri'),
                'lokasi' => $this->input->post('lokasi'),
                'status' => 'Tersedia'
            ];
    
            $insert = $this->db->insert('alat', $data);
    
            if ($insert) {
                $this->session->set_flashdata('success', 'Data alat berhasil ditambahkan');
                redirect('alat'); // Redirect ke halaman daftar alat
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data alat');
                redirect('alat');
            }
        }
    }
    

    public function edit($id) {
        $alat = $this->db->get_where('alat', ['id' => $id])->row();
        
        if (!$alat) {
            $this->session->set_flashdata('error', 'Data alat tidak ditemukan');
            redirect('alat');
        } else {
            $data['alat'] = $alat;
            $this->load->view('alat/edit', $data); // Pastikan ada view 'alat/edit'
        }
    }
    
    

    public function update() {
        $id = $this->input->post('id'); // Ambil ID dari POST
    
        if (!$id) {
            $this->session->set_flashdata('error', 'ID tidak ditemukan');
            redirect('alat');
        }
    
        $this->form_validation->set_rules('nama', 'Nama Alat', 'required|trim');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'required|trim');
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('alat/edit/' . $id);
        } else {
            $data = [
                'nama' => $this->input->post('nama'),
                'deskripsi' => $this->input->post('deskripsi'),
                'stok' => $this->input->post('stok'),
                'kode_seri' => $this->input->post('kode_seri'),
                'lokasi' => $this->input->post('lokasi'),
                'status' => $this->input->post('status')
            ];
    
            $update = $this->db->update('alat', $data, ['id' => $id]);
    
            if ($update) {
                $this->session->set_flashdata('success', 'Data alat berhasil diperbarui');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data alat');
            }
    
            redirect('alat');
        }
    }
    
    
    

    public function destroy($id) {
        // Cek apakah alat sedang dipinjam
        $is_borrowed = $this->db
            ->where('alat_id', $id)
            ->where('status', 'Dipinjam')
            ->from('peminjaman')
            ->count_all_results() > 0;
    
        if ($is_borrowed) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus alat yang sedang dipinjam');
        } else {
            if ($this->db->delete('alat', ['id' => $id])) {
                $this->session->set_flashdata('success', 'Data alat berhasil dihapus');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus data alat');
            }
        }
    
        redirect('alat'); // Ganti dengan halaman tujuan setelah hapus
    }
    

    public function update_status($id) {
        $status = $this->input->post('status');
    
        if (!in_array($status, ['Tersedia', 'Rusak'])) {
            $this->session->set_flashdata('error', 'Status tidak valid');
        } else {
            $update = $this->db->update('alat', ['status' => $status], ['id' => $id]);
    
            if ($update) {
                $this->session->set_flashdata('success', 'Status alat berhasil diperbarui');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui status alat');
            }
        }
    
        redirect('alat'); // Ganti dengan halaman tujuan setelah update
    }
}