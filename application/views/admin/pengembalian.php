<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengembalian Alat</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Peminjaman Aktif</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Peminjam</th>
                                    <th>Alat</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($peminjaman_aktif as $index => $pinjam): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $pinjam->first_name ?></td>
                                    <td><?= $pinjam->nama_alat ?></td>
                                    <td><?= $pinjam->stok_dipinjam ?></td>
                                    <td><?= date('d/m/Y', strtotime($pinjam->tanggal_pinjam)) ?></td>
                                    <td>
                                        <span class="badge badge-warning">
                                            <?= $pinjam->status ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" 
                                                onclick="showPengembalianModal(<?= $pinjam->id ?>)">
                                            <i class="fas fa-undo"></i> Proses Pengembalian
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Pengembalian -->
<div class="modal fade" id="modalPengembalian" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Pengembalian</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?= site_url('pengembalian/proses_pengembalian') ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="peminjaman_id" id="peminjaman_id">
                    
                    <div class="form-group">
                        <label>Peminjam</label>
                        <input type="text" class="form-control" id="modal_peminjam" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Alat</label>
                        <input type="text" class="form-control" id="modal_alat" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Kondisi</label>
                        <select name="kondisi" class="form-control" required>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Proses Pengembalian</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.datatable').DataTable();
});

function showPengembalianModal(id) {
    $.get('<?= site_url('pengembalian/get_peminjaman_detail/') ?>' + id, function(data) {
        const detail = JSON.parse(data);
        $('#peminjaman_id').val(detail.id);
        $('#modal_peminjam').val(detail.first_name);
        $('#modal_alat').val(detail.nama_alat);
        $('#modalPengembalian').modal('show');
    });
}
</script>