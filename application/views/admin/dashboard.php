<!-- dashboard.php -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-tools"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Alat</span>
                            <span class="info-box-number"><?= $total_alat ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-hand-holding"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Sedang Dipinjam</span>
                            <span class="info-box-number"><?= $total_dipinjam ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tersedia</span>
                            <span class="info-box-number"><?= $total_tersedia ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Rusak</span>
                            <span class="info-box-number"><?= $total_rusak ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peminjaman Terbaru dan Chart -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Peminjaman Terbaru</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Peminjam</th>
                                        <th>Alat</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($peminjaman_terbaru as $pinjam): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($pinjam->tanggal_pinjam)) ?></td>
                                        <td><?= $pinjam->nama_peminjam ?></td>
                                        <td><?= $pinjam->nama_alat ?></td>
                                        <td><?= $pinjam->stok_dipinjam ?></td>
                                        <td>
                                            <span class="badge badge-<?= $pinjam->status == 'Dipinjam' ? 'warning' : ($pinjam->status == 'Dikembalikan' ? 'success' : 'danger') ?>">
                                                <?= $pinjam->status ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Chart Peminjaman & Pengembalian -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Rasio Peminjaman & Pengembalian</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="peminjamanChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengembalian Terbaru -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pengembalian Terbaru</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Tanggal Kembali</th>
                                        <th>Peminjam</th>
                                        <th>Alat</th>
                                        <th>Jumlah</th>
                                        <th>Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pengembalian_terbaru as $kembali): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($kembali->tanggal_pengembalian)) ?></td>
                                        <td><?= $kembali->nama_peminjam ?></td>
                                        <td><?= $kembali->nama_alat ?></td>
                                        <td><?= $kembali->stok_dipinjam ?></td>
                                        <td>
                                            <span class="badge badge-<?= $kembali->kondisi == 'Baik' ? 'success' : 'danger' ?>">
                                                <?= $kembali->kondisi ?>
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
        </div>
        
        <!-- Chart.js Script -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get data from PHP variables
            const totalDipinjam = <?= $total_dipinjam ?>;
            const totalPengembalian = <?= count($pengembalian_terbaru) ?>;
            
            // Create pie chart
            const ctx = document.getElementById("peminjamanChart").getContext("2d");
            new Chart(ctx, {
                type: "pie",
                data: {
                    labels: ["Sedang Dipinjam", "Dikembalikan"],
                    datasets: [{
                        data: [totalDipinjam, totalPengembalian],
                        backgroundColor: [
                            "#ffc107", // warning - for borrowed
                            "#28a745", // success - for returned
                        ],
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "bottom"
                        }
                    }
                }
            });
        });
        </script>
    </section>
</div>