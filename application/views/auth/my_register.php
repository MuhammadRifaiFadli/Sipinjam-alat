<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SiPinjam ALAT - Register</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url(); ?>assets/css/sb-admin-2.min.css" rel="stylesheet">
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gradient-success">
    <div class="container">
        <!-- Outer Row -->
        <div class="d-flex justify-content-center">
            <div class="card o-hidden border-0 shadow-lg my-5" style="width: 35rem;">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-tools fa-3x text-success mb-3"></i>
                            <h1 class="h4 text-gray-900 mb-2">SiPinjam Alat & Barang</h1>
                            <p class="text-muted">Sistem Peminjaman Alat dan Barang</p>
                        </div>

                        <form class="user" method="post" action="<?php echo site_url('login/register_proses'); ?>">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <input type="text" class="form-control form-control-user" name="first_name" placeholder="Nama Awal">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" name="last_name" placeholder="Nama Akhir">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" name="identity" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" name="phone" placeholder="Nomor Telepon">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" name="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-success btn-user btn-block">
                                <i class="fas fa-user-plus mr-2"></i> Buat Akun
                            </button>
                        </form>
                        
                        <hr>
                        <div class="text-center">
                            <a class="small text-success" href="<?php echo site_url('login/index'); ?>">
                                <i class="fas fa-sign-in-alt mr-1"></i> Sudah mempunyai akun? Login!
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url(); ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url(); ?>assets/js/sb-admin-2.min.js"></script>

</body>

</html>
