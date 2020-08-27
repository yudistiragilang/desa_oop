<?php

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/
session_start();
require_once '../database/Login.php';
require_once 'Maintenance.php';

$page_content = "Maintenance Users";

$db = new Login();
$usr = new Maintenance();

if ($db->is_logged_in() == "") {

 $db->redirect('../index.php');

}

$userLogin = $db->user_online();
$namaUser = $userLogin['nama'];
$roleUser = $userLogin['role'];
$foto = $userLogin['foto'];

if (isset($_POST['save'])) {
  
  $errorMsg = array();

  $username = strip_tags($_POST['username']);
  $password = strip_tags($_POST['password']);
  $password1 = strip_tags($_POST['password1']);

  if ($username == "") {

    $errorMsg[] = "Username tidak boleh kosong !";

  }elseif ($password == "") {

    $errorMsg[] = "Password tidak boleh kosong !";

  }elseif (strlen($password) < 6 ) {

    $errorMsg[] = "Password kurang dari 6 karakter !";

  }elseif ($password != $password1){

    $errorMsg[] = "Password dan repassword tidak sama !";

  }else{


    $cekUsername = $usr->cek_username($username);
    if($cekUsername == TRUE){

      $return = $usr->save_users($username, $password);

      if ($return == TRUE) {

        $successMsg = "Data berhasil disimpan !";
        $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';

      }else{

        $errorMsg[] = "Gagal simpan data !";
        $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';

      }

    }else{
      $errorMsg[] = "Username sudah digunakan !";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
    }

  }

}

if (isset($_POST['save-update'])) {

  $errorMsg = array();

  $id = strip_tags($_POST['idAdmin']);
  $username = strip_tags($_POST['username']);
  $password = strip_tags($_POST['password']);
  $rePassword = strip_tags($_POST['rePassword']);

  if ($username == "") {
    $errorMsg[] = "Username tidak boleh kosong !";
  }elseif ($password !="" && $password != $rePassword) {
    $errorMsg[] = "Password tidak sama !";
  }else{

    $res = $usr->update_users($id, $username, $password);
    if ($res == TRUE) {
      $successMsg = "Data berhasil diupdate !";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
    }else{
      $errorMsg[] = "Data gagal diupdate !";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
    }

  }


}

if (isset($_GET['id'])) {

  // cek di pelanggan ada gak
  $pelanggan = $usr->get_pelanggan($_GET['id']);
  if (count($pelanggan) > 0) {
    $errorMsg[] = "Data tidak dapat dihapus karena ada turunannya !";
  }else{
    $res = $usr->delete_users($_GET['id']);

    if ($res == TRUE) {
      $successMsg = "Data berhasil dihapus !";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
    }else{
      $errorMsg[] = "Gagal hapus data !";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
    }
  }

}

if (isset($_GET['inactive_id'])) {

  if ($_GET['value'] == 1) {
    $res = $usr->update_active_inactive('users', 'user_id', $_GET['inactive_id'], 0);
  }else{
    $res = $usr->update_active_inactive('users', 'user_id', $_GET['inactive_id'], 1);
  }
  
  if ($res == TRUE) {
    $successMsg = "Status berhasil diubah !";
    $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
  }else{
    $errorMsg[] = "Status Gagal diubah !";
    $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
  }

}

if (isset($_GET['role'])) {

  if ($_GET['valueRole'] == 1) {
    $res = $usr->update_role_user('users', 'user_id', $_GET['role'], 2);
  }else{
    $res = $usr->update_role_user('users', 'user_id', $_GET['role'], 1);
  }
  
  if ($res == TRUE) {
    $successMsg = "Status berhasil diubah !";
    $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
  }else{
    $errorMsg[] = "Status Gagal diubah !";
    $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
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

            <?php if($roleUser == 2) :?>
            <a class="collapse-item" href="../transaction/pesanan.php">Pesanan</a>
            <?php endif; ?>
            
            <?php if($roleUser == 1) :?>
            <a class="collapse-item" href="../transaction/service.php">Service</a>
            <a class="collapse-item" href="../transaction/tagihan.php">Invoice</a>
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

            <a class="collapse-item" href="../inquiry/trans_pesan.php">Pesanan</a>
            <a class="collapse-item" href="../inquiry/trans_service.php">Service</a>
            <?php if($roleUser == 1) :?>
            <a class="collapse-item" href="../inquiry/margin.php">Pendapatan</a>
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
              <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
              </button>
              <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown no-arrow">
                  <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $namaUser; ?></span>
                    <img class="img-profile rounded-circle" src="../assets/img/profiles/<?= $foto; ?>">
                  </a>
                  <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="../edit_profil.php">
                      <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                      Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="../change_password.php">
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
                          <th>Username</th>
                          <th>Available</th>
                          <th>Inactive</th>
                          <th>Role</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $no=1;
                        ?>

                        <?php foreach ($usr->get_data('users') as $dt) : ?>
                        <?php $idAdmin = $dt['user_id']; ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td><?php echo $dt['username']; ?></td>
                          <td><?php echo $dt['available'] == 1 ? "Yes":"No" ; ?></td>
                          <td>
                            <?php if($dt['inactive'] == 1){ ?>
                                <a href="users.php?inactive_id=<?= $dt['user_id'];?>&value=1" class="btn btn-success btn-icon-split">
                                  <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                                  <span class="text">Yes</span>
                                </a>
                            <?php }else{ ?>
                                <a href="users.php?inactive_id=<?= $dt['user_id'];?>&value=0" class="btn btn-danger btn-icon-split">
                                  <span class="icon text-white-50">
                                    <i class="fas fa-times"></i>
                                  </span>
                                  <span class="text">No</span>
                                </a>
                            <?php } ?>
                          </td>
                          <td>
                            <?php if($dt['role'] == 1){ ?>
                                <a href="users.php?role=<?= $dt['user_id'];?>&valueRole=1" class="btn btn-success btn-icon-split">
                                  <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                                  <span class="text">Admin</span>
                                </a>
                            <?php }else{ ?>
                                <a href="users.php?role=<?= $dt['user_id'];?>&valueRole=2" class="btn btn-info btn-icon-split">
                                  <span class="icon text-white-50">
                                    <i class="fas fa-user"></i>
                                  </span>
                                  <span class="text">Pelanggan</span>
                                </a>
                            <?php } ?>  
                          </td>
                          <td>
                            <a href="#" class="btn btn-info btn-circle" data-toggle="modal" data-target="#editModal<?= $dt['user_id'];?>">
                              <i class="fas fa-edit"></i>
                            </a>
                            <a onclick="return hapus()" href="users.php?id=<?= $dt['user_id'];?>" class="btn btn-danger btn-circle">
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
                <h5 class="modal-title" id="Modaladd">Tambah Data User</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>

              <div class="modal-body">

                <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" name="username" value="<?= isset($_POST['save']) ? $username:""; ?>" placeholder="Masukan username. .">
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" name="password" placeholder="Masukan password. .">
                </div>
                <div class="form-group">
                  <label>Ulangi Password</label>
                  <input type="password" class="form-control" name="password1" placeholder="Ulangi password. .">
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
      <?php foreach ($usr->get_data('users') as $edit) : ?>
      <div class="modal fade" id="editModal<?= $edit['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="Modaledit" aria-hidden="true">
        <div class="modal-dialog" role="document">

          <form action="" method="POST">
            <div class="modal-content">
                
              <div class="modal-header">
                <h5 class="modal-title" id="Modaledit">Edit Data User</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>

              <div class="modal-body">

                <h6 class="mb-2"><b style="color: red;">Jika tidak mau berubah password kosongkan saja</b></h6>

                <input type="text" hidden="hidden" name="idAdmin" value="<?= $edit['user_id']; ?>">
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" name="username" value="<?= $edit['username']; ?>" placeholder="Masukan username. .">
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" name="password" placeholder="Masukan password baru. .">
                </div>
                <div class="form-group">
                  <label>Ulangi Password</label>
                  <input type="password" class="form-control" name="rePassword" placeholder="Masukan lagi password baru. .">
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
