<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Peminjaman</h6>
    </div>
    <div class="card-body">
        <table id="peminjamanTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Stok Dipinjam</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; foreach ($peminjaman as $p) : ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $p->first_name . ' ' . $p->last_name ?></td>
        <td><?= $p->nama_alat ?></td>
        <td><?= $p->stok_dipinjam ?></td>
        <td><?= date('d/m/Y', strtotime($p->tanggal_pinjam)) ?></td>
<td><?= $p->tanggal_kembali ? date('d/m/Y', strtotime($p->tanggal_kembali)) : '-' ?></td>

        <td>
            <span class="badge <?= $p->status == 'Dipinjam' ? 'badge-warning' : ($p->status == 'Terlambat' ? 'badge-danger' : 'badge-success') ?>">
                <?= $p->status ?>
            </span>
        </td>
    </tr>
<?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#peminjamanTable').DataTable({
        "responsive": true,
        "order": [[0, "desc"]],
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});
</script>
