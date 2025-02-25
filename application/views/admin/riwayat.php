<body>
    <div class="content-wrapper">
        <div class="container mt-4">
            <h2 class="mb-4">Riwayat Pengembalian</h2>
            <table id="tabelPengembalian" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pengembalian as $row): ?>
                    <tr>
                        <td><?php echo $row->id; ?></td>
                        <td><?php echo $row->peminjaman_id; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($row->tanggal_pengembalian)); ?></td>
                        <td>
                            <span class="badge <?php echo ($row->kondisi == 'Baik') ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo $row->kondisi; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Scripts Section -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#tabelPengembalian').DataTable({
                    responsive: true,
                    language: {
                        search: "Pencarian:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        zeroRecords: "Data tidak ditemukan",
                        info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                        infoEmpty: "Tidak ada data yang tersedia",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    },
                    order: [[0, 'desc']]
                });
            });
        </script>
    </div>
</body>