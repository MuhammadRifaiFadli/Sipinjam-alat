<main>
<!-- peminjaman.php -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Peminjaman Alat</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Peminjaman Aktif</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                            <i class="fas fa-plus"></i> Tambah Peminjaman
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama Alat</th>
                                <th>Kode Seri</th>
                                <th>Jumlah</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php foreach ($peminjaman as $pinjam): ?>
        <?php if ($pinjam->status == 'Dipinjam'): ?>
            <tr>
                <td><?= $pinjam->nama_alat ?></td>
                <td><?= $pinjam->kode_seri ?></td>
                <td><?= $pinjam->stok_dipinjam ?></td>
                <td><?= date('d/m/Y', strtotime($pinjam->tanggal_pinjam)) ?></td>
                <td><?= $pinjam->tanggal_kembali ? date('d/m/Y', strtotime($pinjam->tanggal_kembali)) : '-' ?></td>
                <td>
                    <span class="badge badge-warning"><?= $pinjam->status ?></span>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
</main>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Peminjaman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= site_url('peminjaman/create') ?>" method="post" id="formPeminjaman">
                <div class="modal-body">
                    <!-- Hidden User ID -->
                    <input type="hidden" name="user_id" value="<?= $this->user_id ?>">

                    <div class="form-group">
                        <label>Alat</label>
                        <select name="alat_id" class="form-control" required onchange="getAlatDetail(this.value)">
                            <option value="">Pilih Alat</option>
                            <?php foreach ($alat as $item): ?>
                                <option value="<?= $item->id ?>"><?= $item->nama ?><?php if (!empty($item->kode_seri)) { ?> (<?= $item->kode_seri ?>)<?php } ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Dipinjam</label>
                        <input type="number" class="form-control" name="stok_dipinjam" required min="1">
                    </div>

                    <div class="form-group">
                        <label>Tanggal Pinjam</label>
                        <input type="date" class="form-control" name="tanggal_pinjam" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Kembali</label>
                        <input type="date" class="form-control" name="tanggal_kembali" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Validasi sebelum submit -->
<script>
    document.getElementById('formPeminjaman').addEventListener('submit', function(event) {
        let stokTersedia = parseInt(document.getElementById('stok_tersedia').value) || 0;
        let jumlahDipinjam = parseInt(document.querySelector('input[name="stok_dipinjam"]').value) || 0;
        let tanggalPinjam = new Date(document.querySelector('input[name="tanggal_pinjam"]').value);
        let tanggalKembali = new Date(document.querySelector('input[name="tanggal_kembali"]').value);

        if (jumlahDipinjam > stokTersedia) {
            alert('Jumlah peminjaman tidak boleh lebih dari stok tersedia!');
            event.preventDefault();
        }

        if (tanggalKembali <= tanggalPinjam) {
            alert('Tanggal kembali harus setelah tanggal pinjam!');
            event.preventDefault();
        }
    });

    // Contoh fungsi untuk mengambil detail alat (sesuaikan dengan backend)
    function getAlatDetail(alat_id) {
        if (alat_id) {
            fetch('<?= site_url("peminjaman/getAlatDetail/") ?>' + alat_id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('stok_tersedia').value = data.stok;
                });
        } else {
            document.getElementById('stok_tersedia').value = '';
        }
    }
</script>
