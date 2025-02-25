<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo site_url(); ?>/dashboard" class="brand-link text-center">
        <i class="fas fa-book mr-2"></i>
        <span class="brand-text font-weight-light">SiPinjam ALAT</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <span class="text-light">
                    <?php
                    $user = $this->ion_auth->user()->row();
                    echo $user->first_name;
                    ?>
                </span>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?php echo site_url(); ?>/dashboard" class="nav-link <?php echo $this->uri->segment(1) == 'dashboard' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url(); ?>/riwayat" class="nav-link <?php echo $this->uri->segment(1) == 'riwayat' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>Riwayat pengembalian</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url(); ?>/alat" class="nav-link <?php echo $this->uri->segment(1) == 'alat' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>Manajemen Alat</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url(); ?>/pengembalian" class="nav-link <?php echo $this->uri->segment(1) == 'pengembalian' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-rotate"></i>
                        <p>Jadwal_layanan</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>