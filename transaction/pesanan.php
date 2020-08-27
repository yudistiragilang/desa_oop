<?php

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/
session_start();
require_once '../database/Login.php';
require_once 'Transaction.php';

include '../types.php';

$page_content = "Transaction Pesanan";

$db = new Login();
$trans = new Transaction();

if ($db->is_logged_in() == "") {

 $db->redirect('../index.php');

}

$userLogin = $db->user_online();
$namaUser = $userLogin['nama'];
$roleUser = $userLogin['role'];
$idPelangganLoged = $userLogin['id_pelanggan'];
$foto = $userLogin['foto'];

if (isset($_POST['save'])) {
  
  $errorMsg = array();

  $id_pelanggan = strip_tags($_POST['id_pelanggan']);
  $service_id = strip_tags($_POST['service_id']);
  $memo = strip_tags($_POST['memo']);
  $harga = strip_tags($_POST['harga']);

  if ($id_pelanggan == "") {

    $errorMsg[] = "Pelanggan tidak boleh kosong !";

  }elseif ($service_id == "") {

    $errorMsg[] = "Service tidak boleh kosong !";

  }elseif ($memo == "") {

    $errorMsg[] = "Memo tidak boleh kosong !";

  }else{

    $return = $trans->save_pesanan($id_pelanggan, $service_id, $memo, $harga);

    if ($return == TRUE) {

      $successMsg = "Data berhasil disimpan !";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';

    }else{

      $errorMsg[] = "Gagal simpan data !";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';

    }

  }

}

if (isset($_POST['save-update'])) {

  $errorMsg = array();

  $idPesan = strip_tags($_POST['id_pesan']);
  $id_pelanggan = strip_tags($_POST['id_pelanggan']);
  $service_id = strip_tags($_POST['service_id']);
  $memo = strip_tags($_POST['memo']);
  $harga = strip_tags($_POST['harga']);

  if ($id_pelanggan == "") {

    $errorMsg[] = "Pelanggan tidak boleh kosong !";

  }elseif ($service_id == "") {

    $errorMsg[] = "Service tidak boleh kosong !";

  }elseif ($memo == "") {

    $errorMsg[] = "Memo tidak boleh kosong !";

  }else{

    $res = $trans->update_pesanan($idPesan, $id_pelanggan, $service_id, $memo, $harga);
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

  if($trans->cek_foreign('service', 'id_pesan', $_GET['id']) == TRUE){

    $res = $trans->delete_pesanan($_GET['id']);

    if ($res == TRUE) {
      $successMsg = "Data berhasil dihapus !";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
    }else{
      $errorMsg[] = "Gagal hapus data !";
      $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
    }
  
  }else{

    $errorMsg[] = "Data tidak dapat dihapus sudah ada transaction !";
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
                <button class="btn btn-success btn-icon-split" data-toggle="modal" data-target="#addModal">
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
                          <th>Kode Pesanan</th>
                          <th>Pelanggan</th>
                          <th>Service</th>
                          <th>Harga</th>
                          <th>Tanggal</th>
                          <th>Status</th>
                          <th>Memo</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $no=1;
                        ?>

                        <?php
                          $idFilterPelanggan = ""; 
                          if($roleUser==1){
                            $idFilterPelanggan = "";
                          }else{
                            $idFilterPelanggan = $idPelangganLoged;
                          }
                        ?>

                        <?php foreach ($trans->get_data_pesanan('STATUS_OPEN', $idFilterPelanggan) as $dt) : ?>

                        <tr>
                          <td><?= $no++; ?></td>
                          <td><?php echo $dt['id_pesan']; ?></td>
                          <td><?php echo $dt['nama']; ?></td>
                          <td><?php echo $dt['description']; ?></td>
                          <td><?php echo "Rp ".number_format($dt['harga'],2,",","."); ?></td>
                          <td><?php echo $db->sql_to_date($dt['created_date']); ?></td>
                          <td><?php echo $trans->get_status($dt['status']); ?></td>
                          <td><?php echo $dt['memo']; ?></td>
                          <td>
                            <a href="#" class="btn btn-info btn-circle" data-toggle="modal" data-target="#editModal<?= $dt['id_pesan'];?>">
                              <i class="fas fa-edit"></i>
                            </a>
                            <a onclick="return hapus()" href="pesanan.php?id=<?= $dt['id_pesan'];?>" class="btn btn-danger btn-circle">
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

              <?php if($roleUser==1){ ;?>
                <div class="form-group">
                  <label>Pelanggan</label>
                  <select class="form-control" name="id_pelanggan">
                    <option value=""> Pilih Pelanggan </option>
                    <?php foreach ($trans->get_data('pelanggan JOIN users ON(pelanggan.user_id=users.user_id AND users.role=2)', true) as $dt) : ?>
                    <option value="<?= $dt['id_pelanggan'] ;?>"> <?= $dt['nama'] ;?> </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              <?php }else{ ;?>

                <input type="text" class="form-control" hidden="hidden" name="id_pelanggan" value="<?= $idPelangganLoged; ?>">
                <div class="form-group">
                  <label>Pelanggan</label>
                  <input type="text" class="form-control" readonly="readonly" name="" value="<?= $namaUser; ?>">
                </div>

              <?php } ;?>

                <div class="form-group">
                  <label>Jasa Service</label>
                  <select class="form-control" name="service_id" onchange="changeValue(this.value)">
                    <option value=""> Pilih Jenis Service </option>

                    <?php $service = "var kode = new Array();\n"; ?>
                    
                    <?php foreach ($trans->get_data('service_master', true) as $dt) : ?>
                    
                    <option value="<?= $dt['service_id'] ;?>"> <?= $dt['description'] ;?> </option>
                    
                    <?php $service .= "kode['" . $dt['service_id'] . "'] = {hargax:'" . addslashes($dt['harga_service']) . "'};\n"; ?>
                    
                    <?php endforeach; ?>
                  
                  </select>
                </div>

                <div class="form-group">
                  <label>Harga</label>
                  <input type="number" readonly="readonly" class="form-control" name="harga" id="tampil_harga">
                </div>

                <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" class="form-control" name="memo" placeholder="Masukan Memo. .">
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
      <?php foreach ($trans->get_data_pesanan(STATUS_OPEN) as $edit) : ?>
      <div class="modal fade" id="editModal<?= $edit['id_pesan'];?>" tabindex="-1" role="dialog" aria-labelledby="Modaledit" aria-hidden="true">
        <div class="modal-dialog" role="document">

          <form action="" method="POST">
            <div class="modal-content">
                
              <div class="modal-header">
                <h5 class="modal-title" id="Modaledit">Edit Data Pesanan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>

              <div class="modal-body">

                <input type="text" hidden="hidden" name="id_pesan" value="<?= $edit['id_pesan']; ?>">

                <?php if($roleUser==1){ ;?>
                <div class="form-group">
                  <label>Pelanggan</label>
                  <select class="form-control" name="id_pelanggan">
                    <option value=""> Pilih Pelanggan </option>
                    <?php foreach ($trans->get_data('pelanggan JOIN users ON(pelanggan.user_id=users.user_id AND users.role=2)', true) as $dt) : ?>

                    <?php
                      if ($edit['id_pelanggan']==$dt['id_pelanggan']) {
                        $select="selected";
                      }else{
                        $select="";
                      } 
                    ?>

                    <option <?= $select; ?> value="<?= $dt['id_pelanggan'] ;?>"> <?= $dt['nama'] ;?> </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <?php }else{ ;?>

                <input type="text" class="form-control" hidden="hidden" name="id_pelanggan" value="<?= $idPelangganLoged; ?>">
                <div class="form-group">
                  <label>Pelanggan</label>
                  <input type="text" class="form-control" readonly="readonly" name="" value="<?= $namaUser; ?>">
                </div>

                <?php } ;?>

                <div class="form-group">
                  <label>Jasa Service</label>
                  <select class="form-control" name="service_id" onchange="changeValueEdit(this.value)">
                    <option value=""> Pilih Jenis Service </option>
                    
                    <?php $serviceEdit = "var kodes = new Array();\n"; ?>
                    <?php foreach ($trans->get_data('service_master', true) as $dt) : ?>

                    <?php
                      if ($edit['service_id']==$dt['service_id']) {
                        $select="selected";
                      }else{
                        $select="";
                      } 
                    ?>

                    <option <?= $select; ?> value="<?= $dt['service_id'] ;?>"> <?= $dt['description'] ;?> </option>
                    <?php $serviceEdit .= "kodes['" . $dt['service_id'] . "'] = {hargae:'" . addslashes($dt['harga_service']) . "'};\n"; ?>
                    <?php endforeach; ?>
                  
                  </select>
                </div>

                <div class="form-group">
                  <label>Harga</label>
                  <input type="number" readonly="readonly" class="form-control" name="harga" id="edit_harga" value="<?= $edit['harga'] ;?>">
                </div>

                <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" class="form-control" name="memo" value="<?= $edit['memo'] ;?>">
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
      <script type="text/javascript">    
      
        <?php echo $service; ?>
        function changeValue(x){
          document.getElementById('tampil_harga').value = kode[x].hargax;   
        };
      
      </script>

      <script type="text/javascript">    
      
        <?php echo $serviceEdit; ?>
        function changeValueEdit(x){
          document.getElementById('edit_harga').value = kodes[x].hargae;   
        };
      
      </script>

    </body>

    </html>
