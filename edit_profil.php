<?php

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/
session_start();
require_once 'database/Login.php';
require_once 'maintenance/Maintenance.php';

$db = new Login();
$usr = new Maintenance();

if ($db->is_logged_in() == "") {

 $db->redirect('index.php');

}

$userLogin = $db->user_online();
$namaUser = $userLogin['nama'];
$roleUser = $userLogin['role'];
$idPelangganLoged = $userLogin['id_pelanggan'];
$foto = $userLogin['foto'];
$emailUser = $userLogin['email'];
$alamatUser = $userLogin['alamat'];
$teleponUser = $userLogin['no_telepon'];

$idUserActive = $userLogin['user_id'];

$page_content = "Edit Profil";

if (isset($_POST['update-user'])) {
  
  $error = 0;
  $errorMessage = array();


  $fullname = strip_tags($_POST['nama']);
  $no_telepon = strip_tags($_POST['telepon']);
  $alamat = strip_tags($_POST['alamat']);

  if ($fullname == "") {
    
    $errorMessage[] = "Nama tidak boleh kosong !";
  
  }elseif ($alamat == "") {

    $errorMessage[] = "Alamat tidak boleh kosong !";

  }else if ($no_telepon == "") {
    
    $errorMessage[] = "Telepon tidak boleh kosong !";
  
  }else{

    $imgFile = "";

    if($_FILES['item_image']['name'] !=""){

      $imgFile = $_FILES['item_image']['name'];
      $tmp_dir = $_FILES['item_image']['tmp_name'];
      $imgSize = $_FILES['item_image']['size'];
      $upload_dir = 'assets/img/profiles/';
      $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

      // cek foto sebelumnya apa
      if($db->cek_foto_used($idUserActive) != $imgFile AND $db->cek_foto_used($idUserActive) != "default.jpg"){

          unlink($upload_dir.$db->cek_foto_used($idUserActive));

      }

      if(in_array($imgExt, $valid_extensions)){

        if($imgSize < 1000000){
          move_uploaded_file($tmp_dir, $upload_dir.$imgFile);
                
        }else{

          $errorMessage[] = "Ukuran gambar terlalu besar !";
          $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';

        }
              
      }else{

        $errorMessage[] = "Format gambar tidak di dukung !";
        $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';

      }
    
    }

    $return = $db->update_profil($idPelangganLoged, $fullname, $alamat, $no_telepon, $imgFile);
    
    if ($return == TRUE) {

      $successMsg = "Berhasil Update Profil ! ";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';

    }else{

      $errorMessage[] = "Gagal Update Profil ! ";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';

    }

  }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  
  <?php if(isset($foword)) : ?>
  <?= $foword; ?>
  <?php endif;?>
  
  <title><?= $page_content; ?></title>
  <link rel="shortcut icon" href="assets/img/favicon.ico">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <div id="wrapper">

    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">CV. FPJ</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="home.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        MENU
      </div>

      <?php if($roleUser == 1) :?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaintenance" aria-expanded="true" aria-controls="collapseMaintenance">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Maintenance</span>
        </a>

        <div id="collapseMaintenance" class="collapse" aria-labelledby="headingMaintenance" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">List Maintenance</h6>

            <a class="collapse-item" href="maintenance/users.php">Users</a>
            <a class="collapse-item" href="maintenance/pelanggan.php">Pelanggan</a>
            <a class="collapse-item" href="maintenance/service.php">Service</a>

          </div>
        </div>

      </li>
      <?php endif; ?>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTransaction" aria-expanded="true" aria-controls="collapseTransaction">
          <i class="fas fa-fw fa-feather-alt"></i>
          <span>Transaction</span>
        </a>
        <div id="collapseTransaction" class="collapse" aria-labelledby="headingTransaction" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">List Transaction:</h6>

            <a class="collapse-item" href="transaction/pesanan.php">Pesanan</a>

            <?php if($roleUser == 1) :?>
            <a class="collapse-item" href="transaction/service.php">Service</a>
            <a class="collapse-item" href="transaction/tagihan.php">Invoice</a>
            <?php endif; ?>

          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInquiry" aria-expanded="true" aria-controls="collapseInquiry">
          <i class="fas fa-fw fa-book"></i>
          <span>Inquiry</span>
        </a>
        <div id="collapseInquiry" class="collapse" aria-labelledby="headingInquiry" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Custom Utilities:</h6>
            
            <a class="collapse-item" href="inquiry/trans_pesan.php">Pesanan</a>
            <a class="collapse-item" href="inquiry/trans_service.php">Service</a>
            <?php if($roleUser == 1) :?>
            <a class="collapse-item" href="inquiry/margin.php">Pendapatan</a>
            <?php endif; ?>

          </div>
        </div>
      </li>

      <hr class="sidebar-divider d-none d-md-block">
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $namaUser; ?></span>
                <img class="img-profile rounded-circle" src="assets/img/profiles/<?= $foto; ?>">
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="edit_profil.php">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="change_password.php">
                  <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                  Change Password
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><?= $page_content; ?></h1>
          </div>

          <?php

          if (isset($errorMessage)) {

            for ($i=0; $i < count($errorMessage) ; $i++) { 
          ?>

            <div class="alert alert-danger">
              <?= $errorMessage[$i]; ?>
            </div>

          <?php
            }
                
          }

          if (isset($successMsg)) {
          ?>
            <div class="alert alert-success">
              <?= $successMsg; ?>
            </div>
          <?php
          }

          ?>

          <div class="row">
            <div class="col-lg-8">
              <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group row">
                  <label for="email" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" readonly value="<?= $emailUser; ?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Full name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="nama" value="<?= $namaUser; ?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="alamat" value="<?= $alamatUser; ?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Telepon</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="telepon" value="<?= $teleponUser; ?>">
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-sm-2">Picture</div>
                  <div class="col-sm-10">
                    <div class="row">
                      <div class="col-sm-3">
                        <img src="assets/img/profiles/<?= $foto; ?>" class="img-thumbnail">
                      </div>
                      <div class="col-sm-9">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="item_image" name="item_image">
                          <label class="custom-file-label" for="item_image">Choose file</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group row justify-content-end">
                  <div class="col-sm-10">
                    <button type="submit" name="update-user" class="btn btn-primary">Edit</button>
                  </div>
                </div>

              </form>

            </div>
          </div>

        </div>
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website <?= date('Y'); ?></span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="assets/js/sb-admin-2.min.js"></script>
  <script src="assets/vendor/chart.js/Chart.min.js"></script>
  <script src="assets/js/demo/chart-area-demo.js"></script>
  <script src="assets/js/demo/chart-pie-demo.js"></script>
  <script type="text/javascript">
    $('.custom-file-input').on('change', function(){

      let fileName = $(this).val().split('\\').pop();
      $(this).next('.custom-file-label').addClass("selected").html(fileName);

    });
  </script>

</body>

</html>
