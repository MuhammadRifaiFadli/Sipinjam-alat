<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Menampilkan semua jadwal
    public function index() {
        $query = $this->db->get('schedules');
        $data['schedules'] = $query->result();
        $this->load->view('admin/schedules', $data);
    }

    // Menambahkan jadwal baru (jadwal tetap dari jam 07:00 - 16:00, tiap 2 jam)
    public function generate_schedule() {
        $this->db->trans_start(); // Mulai transaksi

        $tanggal = $this->input->post('tanggal'); // Input tanggal dari form
        $service_id = $this->input->post('service_id');
        $provider_id = $this->input->post('provider_id');

        // Cek apakah jadwal untuk tanggal ini sudah dibuat
        $exists = $this->db->get_where('schedules', ['tanggal' => $tanggal, 'service_id' => $service_id])->num_rows();
        if ($exists > 0) {
            $this->session->set_flashdata('error', 'Jadwal untuk tanggal ini sudah ada.');
            redirect('ScheduleController');
        }

        // Slot waktu mulai dari jam 07:00 sampai 16:00 (tiap 2 jam)
        $start_time = strtotime("07:00:00");
        $end_time = strtotime("16:00:00");

        while ($start_time < $end_time) {
            $this->db->insert('schedules', [
                'service_id' => $service_id,
                'provider_id' => $provider_id,
                'tanggal' => $tanggal,
                'waktu_mulai' => date('H:i:s', $start_time),
                'waktu_selesai' => date('H:i:s', strtotime('+2 hours', $start_time)),
                'status' => 'available'
            ]);
            $start_time = strtotime('+2 hours', $start_time);
        }

        $this->db->trans_complete(); // Selesaikan transaksi

        $this->session->set_flashdata('success', 'Jadwal berhasil dibuat.');
        redirect('ScheduleController');
    }

    // Mengubah status jadwal (misalnya menjadi 'booked')
    public function update_status($id) {
        $status = $this->input->post('status');
        $this->db->where('id', $id);
        $this->db->update('schedules', ['status' => $status]);

        $this->session->set_flashdata('success', 'Status jadwal diperbarui.');
        redirect('ScheduleController');
    }

    // Menghapus jadwal (opsional)
    public function delete($id) {
        $this->db->delete('schedules', ['id' => $id]);
        $this->session->set_flashdata('success', 'Jadwal berhasil dihapus.');
        redirect('ScheduleController');
    }
}
