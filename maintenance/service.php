
<?php

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/

session_start();

require_once '../database/Login.php';
require_once 'Maintenance.php';

$page_content = "Maintenance Service";

$db = new Login();
$usr = new Maintenance();

if ($db->is_logged_in() == "") {

 $db->redirect('../index.php');

}

$userLogin = $db->user_online();
$namaUser = $userLogin['nama'];
$roleUser = $userLogin['role'];

if (isset($_POST['save'])) {
  
  $errorMsg = array();

  $kode_service = strip_tags($_POST['kode_service']);
  $description = strip_tags($_POST['deskripsi']);

  if ($kode_service == "") {

    $errorMsg[] = "Kode service tidak boleh kosong !";

  }elseif ($usr->cek_kode_service($kode_service) == FALSE) {

    $errorMsg[] = "Kode service tidak tersedia !";

  }elseif ($description == "") {

    $errorMsg[] = "Description tidak boleh kosong !";

  }else{

    $return = $usr->save_service($kode_service, $description);

    if ($return == TRUE) {

      $successMsg = "Data berhasil disimpan !";
      $username="";

    }else{

      $errorMsg[] = "Gagal simpan data !";

    }

  }

}

if (isset($_POST['save-update'])) {

  $errorMsg = array();

  $idService = strip_tags($_POST['service_id']);
  $description = strip_tags($_POST['deskripsi']);

  if ($description == "") {

    $errorMsg[] = "Description tidak boleh kosong !";

  }else{

    $res = $usr->update_service($description, $idService);
    if ($res == TRUE) {
      $successMsg = "Data berhasil diupdate !";
    }else{
      $errorMsg[] = "Data gagal diupdate !";
    }

  }


}

if (isset($_GET['id'])) {
  
  $res = $usr->delete_service($_GET['id']);

  if ($res == TRUE) {
    $successMsg = "Data berhasil dihapus !";
  }else{
    $errorMsg[] = "Gagal hapus data !";
  }

}

if (isset($_GET['inactive_id'])) {

  if ($_GET['value'] == 1) {
    $res = $usr->update_active_inactive('service_master', 'service_id', $_GET['inactive_id'], 0);
  }else{
    $res = $usr->update_active_inactive('service_master', 'service_id', $_GET['inactive_id'], 1);
  }
  
  if ($res == TRUE) {
    $successMsg = "Status berhasil diubah !";
  }else{
    $errorMsg[] = "Status Gagal diubah !";
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

  <title><?= $page_content; ?></title>
  <link rel="shortcut icon" href="../assets/img/favicon.ico">
  <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../home.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">CV. FPJ</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="../home.php">
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

            <a class="collapse-item" href="../maintenance/users.php">Users</a>
            <a class="collapse-item" href="../maintenance/pelanggan.php">Pelanggan</a>
            <a class="collapse-item" href="../maintenance/service.php">Service</a>

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

            <a class="collapse-item" href="">Pesanan</a>

            <?php if($roleUser == 1) :?>
            <a class="collapse-item" href="">Service</a>
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

            <?php if($roleUser == 1) :?>
            <a class="collapse-item" href="">Users</a>
            <a class="collapse-item" href="">Pelanggan</a>
            <?php endif; ?>
            <a class="collapse-item" href="">Pesanan</a>
            <a class="collapse-item" href="">Service</a>

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
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
              <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
              </button>
              <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown no-arrow">
                  <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $namaUser; ?></span>
                    <img class="img-profile rounded-circle" src="../assets/img/profiles/default.jpg">
                  </a>
                  <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                      <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                      Profile
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

            <!-- Begin Page Content -->
            <div class="container-fluid">

              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"><?= $page_content; ?></h1>
              </div>

              <div class="mb-2">
                <button class="btn btn-success btn-icon-split" data-toggle="modal" data-target="#addModal"">
                  <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                  </span>
                  <span class="text">Add</span>
                </button>
              </div>

              <?php

                if (isset($errorMsg)) {

                  for ($i=0; $i < count($errorMsg) ; $i++) { 
                    ?>

                    <div class="alert alert-danger">
                      <?= $errorMsg[$i]; ?>
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

              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Kode</th>
                          <th>Description</th>
                          <th>Inactive</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $no=1;
                        ?>

                        <?php foreach ($usr->get_data('service_master') as $dt) : ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td><?php echo $dt['kode_service']; ?></td>
                          <td><?php echo $dt['description']; ?></td>
                          <td>
                            <?php if($dt['inactive'] == 1){ ?>
                                <a href="service.php?inactive_id=<?= $dt['service_id'];?>&value=1" class="btn btn-success btn-icon-split">
                                  <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                                  <span class="text">Yes</span>
                                </a>
                            <?php }else{ ?>
                                <a href="service.php?inactive_id=<?= $dt['service_id'];?>&value=0" class="btn btn-danger btn-icon-split">
                                  <span class="icon text-white-50">
                                    <i class="fas fa-times"></i>
                                  </span>
                                  <span class="text">No</span>
                                </a>
                            <?php } ?>
                          </td>
                          <td>
                            <a href="#" class="btn btn-info btn-circle" data-toggle="modal" data-target="#editModal<?= $dt['service_id'];?>">
                              <i class="fas fa-edit"></i>
                            </a>
                            <a onclick="return hapus()" href="service.php?id=<?= $dt['service_id'];?>" class="btn btn-danger btn-circle">
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
      </div>
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
              <a class="btn btn-primary" href="../logout.php">Logout</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Logout Modal-->

      <!-- Add Modal-->
      <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="Modaladd" aria-hidden="true">
        <div class="modal-dialog" role="document">

          <form action="" method="POST">
            <div class="modal-content">
                
              <div class="modal-header">
                <h5 class="modal-title" id="Modaladd">Tambah Data Service</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>

              <div class="modal-body">

                <div class="form-group">
                  <input type="text" class="form-control" name="kode_service" placeholder="Masukan Kode. .">
                </div>

                <div class="form-group">
                  <input type="text" class="form-control" name="deskripsi" placeholder="Masukan Description. .">
                </div>

              </div>

              <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input type="submit" name="save" class="btn btn-primary" value="Simpan">
              </div>

            </div>
          </form>

        </div>
      </div>
      <!-- Add Modal-->

      <!-- Edit Modal-->
      <?php foreach ($usr->get_data('service_master') as $edit) : ?>
      <div class="modal fade" id="editModal<?= $edit['service_id'];?>" tabindex="-1" role="dialog" aria-labelledby="Modaledit" aria-hidden="true">
        <div class="modal-dialog" role="document">

          <form action="" method="POST">
            <div class="modal-content">
                
              <div class="modal-header">
                <h5 class="modal-title" id="Modaledit">Edit Data Service</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>

              <div class="modal-body">

                <input type="text" hidden="hidden" name="service_id" value="<?= $edit['service_id']; ?>">
                <div class="form-group">
                  <input type="text" class="form-control" name="kode_service" value="<?= $edit['kode_service']; ?>" readonly>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="deskripsi" value="<?= $edit['description']; ?>">
                </div>

              </div>

              <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input type="submit" name="save-update" class="btn btn-primary" value="Simpan">
              </div>

            </div>
          </form>

        </div>
      </div>
      <?php endforeach; ?>
      <!-- Edit Modal-->


      <script src="../assets/vendor/jquery/jquery.min.js"></script>
      <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
      <script src="../assets/js/sb-admin-2.min.js"></script>
      <script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
      <script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
      <script src="../assets/js/demo/datatables-demo.js"></script>
      <script type="text/javascript" language="JavaScript">
        function hapus(){
          takon = confirm("Anda Yakin Akan Menghapus Data ?");
            if (takon == true) return true;
            else return false;
            }
      </script>

    </body>

    </html>
