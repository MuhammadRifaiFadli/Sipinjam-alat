<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manajemen Alat</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Alat</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                        <i class="fas fa-plus"></i> Tambah Alat
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <table id="tabelAlat" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Alat</th>
                            <th>Stok</th>
                            <th>Dipinjam</th>
                            <th>Lokasi</th>
                            <th>Kode Seri</th> <!-- Kolom baru untuk Kode Seri -->
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alat as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $item->nama ?></td>
                            <td><?= $item->stok ?></td>
                            <td><?= $item->total_dipinjam ?? 0 ?></td>
                            <td><?= $item->lokasi ?></td>
                            <td><?= $item->kode_seri ?></td> <!-- Menampilkan Kode Seri -->
                            <td>
                                <span class="badge badge-<?= $item->status == 'Tersedia' ? 'success' : ($item->status == 'Dipinjam' ? 'warning' : 'danger') ?>">
                                    <?= $item->status ?>
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm btn-edit" 
                                   data-id="<?= $item->id ?>" 
                                   data-nama="<?= $item->nama ?>" 
                                   data-deskripsi="<?= $item->deskripsi ?>" 
                                   data-stok="<?= $item->stok ?>" 
                                   data-kode-seri="<?= $item->kode_seri ?>" 
                                   data-lokasi="<?= $item->lokasi ?>" 
                                   data-toggle="modal" data-target="#modalEdit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= site_url('alat/destroy/' . $item->id) ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus alat ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('alat/store') ?>" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Alat</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Alat *</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Stok *</label>
                        <input type="number" class="form-control" name="stok" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Seri</label>
                        <input type="text" class="form-control" name="kode_seri">
                    </div>
                    <div class="form-group">
                        <label>Lokasi *</label>
                        <input type="text" class="form-control" name="lokasi" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('alat/update') ?>" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Alat</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>Nama Alat *</label>
                        <input type="text" class="form-control" name="nama" id="edit_nama" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="edit_deskripsi" rows="3"></textarea>
                    </div>
                    <div class="form-group">
    <label>Stok *</label>
    <input type="number" class="form-control" name="stok" id="edit_stok" min="1" required readonly>
</div>

                    <div class="form-group">
                        <label>Kode Seri</label>
                        <input type="text" class="form-control" name="kode_seri" id="edit_kode_seri">
                    </div>
                    <div class="form-group">
                        <label>Lokasi *</label>
                        <input type="text" class="form-control" name="lokasi" id="edit_lokasi" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('.btn-edit').on('click', function() {
        $('#edit_id').val($(this).data('id'));
        $('#edit_nama').val($(this).data('nama'));
        $('#edit_deskripsi').val($(this).data('deskripsi'));
        $('#edit_stok').val($(this).data('stok'));
        $('#edit_kode_seri').val($(this).data('kode-seri'));
        $('#edit_lokasi').val($(this).data('lokasi'));
    });
});
</script>