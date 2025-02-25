<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        $this->load->database();
        $this->user_id = $this->ion_auth->user()->row()->id;
    }

    public function index() {
        // Hanya menampilkan peminjaman milik user yang login
        $query = $this->db->query("
            SELECT p.*, a.nama as nama_alat, a.kode_seri, 
                   DATEDIFF(CURRENT_TIMESTAMP, p.tanggal_pinjam) as durasi_pinjam
            FROM peminjaman p
            JOIN alat a ON p.alat_id = a.id
            WHERE p.user_id = ?
            ORDER BY p.tanggal_pinjam DESC
        ", [$this->user_id]);
        
        // Data untuk modal tambah peminjaman
        $alat_query = $this->db->query("
            SELECT * FROM alat 
            WHERE status = 'Tersedia' 
            AND stok > 0
        ");
        
        $data['peminjaman'] = $query->result();
        $data['alat'] = $alat_query->result();
        
        $this->load->view('inc/navbar');
        $this->load->view('user/peminjaman', $data);
        $this->load->view('inc/footer');
    }

    public function create() {
        if ($this->input->method() == 'post') {
            $this->db->trans_start();
    
            try {
                $alat_id = (int)$this->input->post('alat_id');
                $stok_dipinjam = (int)$this->input->post('stok_dipinjam');
                $tanggal_pinjam = $this->input->post('tanggal_pinjam') ? date('Y-m-d H:i:s', strtotime($this->input->post('tanggal_pinjam'))) : NULL;
                $tanggal_kembali = $this->input->post('tanggal_kembali') ? date('Y-m-d H:i:s', strtotime($this->input->post('tanggal_kembali'))) : NULL;
    
                // Validasi alat
                $alat = $this->db->get_where('alat', [
                    'id' => $alat_id,
                    'status' => 'Tersedia'
                ])->row();
                
                if (!$alat) {
                    throw new Exception('Alat tidak tersedia untuk dipinjam');
                }
                
                if ($alat->stok < $stok_dipinjam) {
                    throw new Exception('Stok tidak mencukupi');
                }
    
                // Cek peminjaman aktif
                $peminjaman_aktif = $this->db->get_where('peminjaman', [
                    'user_id' => $this->user_id,
                    'status' => 'Dipinjam'
                ])->num_rows();
    
                // if ($peminjaman_aktif > 0) {
                //     throw new Exception('Anda masih memiliki peminjaman yang aktif');
                // }
    
                // Insert peminjaman
                $data_peminjaman = [
                    'user_id' => $this->user_id,
                    'alat_id' => $alat_id,
                    'stok_dipinjam' => $stok_dipinjam,
                    'status' => 'Dipinjam'
                ];
    
                // Hanya tambahkan tanggal jika diisi di form
                if ($tanggal_pinjam) {
                    $data_peminjaman['tanggal_pinjam'] = $tanggal_pinjam;
                }
                if ($tanggal_kembali) {
                    $data_peminjaman['tanggal_kembali'] = $tanggal_kembali;
                }
    
                $this->db->insert('peminjaman', $data_peminjaman);
    
                // Update stok alat
                $this->db->where('id', $alat_id);
                $this->db->update('alat', [
                    'stok' => $alat->stok - $stok_dipinjam,
                    'status' => ($alat->stok - $stok_dipinjam == 0) ? 'Dipinjam' : 'Tersedia'
                ]);
    
                $this->db->trans_complete();
    
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Gagal memproses peminjaman');
                }
    
                $this->session->set_flashdata('success', 'Peminjaman berhasil dibuat');
    
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', $e->getMessage());
            }
            
            redirect('peminjaman');
        }
    }

    public function get_alat_detail($id) {
        $alat = $this->db->get_where('alat', [
            'id' => $id,
            'status' => 'Tersedia'
        ])->row();
        
        if (!$alat) {
            $this->output->set_status_header(404);
            echo json_encode(['error' => 'Data tidak ditemukan']);
            return;
        }

        // Hanya kirim data yang diperlukan
        $response = [
            'id' => $alat->id,
            'nama' => $alat->nama,
            'stok' => $alat->stok,
            'kode_seri' => $alat->kode_seri,
            'deskripsi' => $alat->deskripsi
        ];
        
        echo json_encode($response);
    }

}