<!-- application/views/dashboardu/index.php -->

<div class="container-fluid">
    <!-- Welcome Message -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Selamat Datang, <?= $user->first_name ?></h1>
    </div>

    <!-- Peminjaman Aktif Card -->
    <?php if (!empty($peminjaman_aktif)): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Peminjaman Aktif</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Alat</th>
                            <th>Kode Seri</th>
                            <th>Jumlah Dipinjam</th>
                            <th>Tanggal Pinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peminjaman_aktif as $pinjam): ?>
                        <tr>
                            <td><?= $pinjam->nama_alat ?></td>
                            <td><?= $pinjam->kode_seri ?></td>
                            <td><?= $pinjam->stok_dipinjam ?></td>
                            <td><?= date('d-m-Y', strtotime($pinjam->tanggal_pinjam)) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Daftar Alat Tersedia Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Alat Tersedia</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Nama Alat</th>
                            <th>Stok Tersedia</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alat as $item): ?>
                        <tr>
                            <td><?= $item->nama ?></td>
                            <td><?= $item->stok ?></td>
                            <td><?= $item->lokasi ?></td>
                            <td>
                                <span class="badge badge-<?= $item->status == 'Tersedia' ? 'success' : 'warning' ?>">
                                    <?= $item->status ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Script -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>