
<?php

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/

session_start();

require_once '../database/Login.php';
require_once 'Inquiry.php';

include '../types.php';

$page_content = "Inquiry Service";

$db = new Login();
$trans = new Inquiry();

if ($db->is_logged_in() == "") {

 $db->redirect('../index.php');

}

$userLogin = $db->user_online();
$namaUser = $userLogin['nama'];
$roleUser = $userLogin['role'];
$idPelangganLoged = $userLogin['id_pelanggan'];
$foto = $userLogin['foto'];

if (isset($_GET['status'])) {

  if ($_GET['value'] == 1) {
    $res = $trans->update_status('service', 'id_service', $_GET['status'], 0);
  }else{
    $res = $trans->update_status('service', 'id_service', $_GET['status'], 1);
  }
  
  if ($res == TRUE) {
    $successMsg = "Status berhasil diubah !";
    $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
  }else{
    $errorMsg[] = "Status Gagal diubah !";
    $foword = '<meta http-equiv="refresh" content="1; url='.$_SERVER['PHP_SELF'].'">';
  }

}

if (isset($_POST['cetak'])) {
  
  $id_pelanggan = strip_tags($_POST['q_pelanggan']);
  $id_service = strip_tags($_POST['q_service']);
  $date_from = $_POST['q_from'];
  $date_to = $_POST['q_to'];
  $destination = $_POST['q_destinasi'];

  if ($destination == 1) {
    
    // cetak pdf
    $db->redirect('cetak_pdf_service.php?id_pelanggan='.$id_pelanggan.'&id_service='.$id_service.'&from='.$date_from.'&to='.$date_to);

  }else{
    
    // cetak excel
    $db->redirect('cetak_excel_service.php?id_pelanggan='.$id_pelanggan.'&id_service='.$id_service.'&from='.$date_from.'&to='.$date_to);
  
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

            <a class="collapse-item" href="../transaction/pesanan.php">Pesanan</a>

            <?php if($roleUser == 1) :?>
            <a class="collapse-item" href="../transaction/service.php">Service</a>
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

              <div class="row">
                <div class="col-md-12">
                  <form class="form-inline" action="" method="POST">
                    
                    <?php if($roleUser == 1) :?>
                    <select class="form-control mb-2 mr-sm-2" name="q_pelanggan">
                      <option value=""> All Pelanggan </option>
                      <?php foreach ($trans->get_data('pelanggan JOIN users ON(pelanggan.user_id=users.user_id AND users.role=2)', true) as $dt) : ?>
                      <option value="<?= $dt['id_pelanggan'] ;?>"> <?= $dt['nama'] ;?> </option>
                      <?php endforeach; ?>
                    </select>

                    <?php elseif($roleUser == 2) :?>

                      <input type="text" hidden="hidden" name="q_pelanggan" value="<?= $idPelangganLoged; ?>">
                      <input type="text" class="form-control mb-2 mr-sm-2" readonly="readonly" name="" value="<?= $namaUser; ?>">
                      
                    <?php endif;?>

                    <select class="form-control mb-2 mr-sm-2" name="q_service">
                      <option value=""> All Service </option>
                      <?php foreach ($trans->get_data('service_master', true) as $dt) : ?>
                      <option value="<?= $dt['service_id'] ;?>"> <?= $dt['description'] ;?> </option>
                      <?php endforeach; ?>
                    </select>

                    <input type="date" name="q_from" class="form-control mb-2 mr-sm-2" required="required">

                    <input type="date" name="q_to" class="form-control mb-2 mr-sm-2" required="required">

                    <select class="form-control mb-2 mr-sm-2" name="q_destinasi" required="required">
                      <option value=""> Destination </option>
                      <option value="1"> Pdf </option>
                      <option value="2"> Excel </option>
                    </select>

                    <button type="submit" name="cetak" class="btn btn-primary mb-2">Cetak</button>

                  </form>
                </div>
              </div>

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
                          <th>Memo</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $no=1;
                        ?>

                        <?php foreach ($trans->get_data_trans_service() as $dt) : ?>

                        <tr>
                          <td><?= $no++; ?></td>
                          <td><?php echo $dt['id_pesan']; ?></td>
                          <td><?php echo $dt['nama']; ?></td>
                          <td><?php echo $dt['description']; ?></td>
                          <td><?php echo "Rp ".number_format($dt['harga'],2,",","."); ?></td>
                          <td><?php echo $db->sql_to_date($dt['created_date']); ?></td>
                          <td><?php echo $dt['memo']; ?></td>
                          <td>
                            <?php if($roleUser == 1) :?>

                              <?php if($dt['status'] == 1){ ?>
                                  <a href="trans_service.php?status=<?= $dt['id_service'];?>&value=1" class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                                    <span class="text">DONE</span>
                                  </a>
                              <?php }else{ ?>
                                  <a href="trans_service.php?status=<?= $dt['id_service'];?>&value=0" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                      <i class="fas fa-times"></i>
                                    </span>
                                    <span class="text">REPAIRED</span>
                                  </a>
                              <?php } ?>

                            <?php endif ?>

                            <?php if ($roleUser == 2) : ?>
                              <?= $dt['status'] == 1 ? "DONE":"REPAIRED"; ?>
                            <?php endif ?>

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

      <!-- Proses Modal-->
      <?php foreach ($trans->get_data_pesanan(STATUS_OPEN) as $edit) : ?>
      <div class="modal fade" id="prosesModal<?= $edit['id_pesan'];?>" tabindex="-1" role="dialog" aria-labelledby="Modalproses" aria-hidden="true">
        <div class="modal-dialog" role="document">

          <form action="" method="POST">
            <div class="modal-content">
                
              <div class="modal-header">
                <h5 class="modal-title" id="Modalproses">Proses Data Pesanan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>

              <div class="modal-body">
                
                <div class="form-group">
                  <label>Kode Pesan</label>
                  <input type="text" class="form-control" readonly="readonly" name="id_pesan" value="<?= $edit['id_pesan']; ?>">
                </div>

                <input type="text" class="form-control" hidden="hidden" name="id_pelanggan" value="<?= $edit['id_pelanggan']; ?>">
                <div class="form-group">
                  <label>Pelanggan</label>
                  <input type="text" class="form-control" readonly="readonly" name="" value="<?= $namaUser; ?>">
                </div>

                <input type="text" class="form-control" hidden="hidden" name="service_id" value="<?= $edit['service_id']; ?>">
                <div class="form-group">
                  <label>Jasa Service</label>
                  <input type="text" class="form-control" readonly="readonly" name="" value="<?= $edit['description']; ?>">
                </div>

                <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" readonly="readonly" class="form-control" name="memo" value="<?= $edit['memo'] ;?>">
                </div>

              </div>

              <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <input type="submit" name="reject" class="btn btn-danger" value="Reject">
                <input type="submit" name="approve" class="btn btn-success" value="Approve">
              </div>

            </div>
          </form>

        </div>
      </div>
      <?php endforeach; ?>
      <!-- Proses Modal-->


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
