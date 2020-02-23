<?php

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/

session_start();

require_once __DIR__.'/database/Login.php';

$login = new Login();

if ($login->is_logged_in() != "") {

 $login->redirect('home.php');

}

if (isset($_POST['btn-login'])) {

  $username = strip_tags($_POST['txt_username_email']);
  $password = strip_tags($_POST['password']);

  $error = 0;
  $message = array();

  if ($username == "") {

    $message[] = "Username / Email tidak boleh kosong !";
    $error = 1;

  } elseif ($password == "") {

    $message[] = "Password tidak boleh kosong !";
    $error = 1;

  }

  if ($error == 0) {

    if ($login->do_login($username, $password)) {

      $login->redirect('home.php');
      
    }else{

      $message[] = "Wrong ! Username and Password dont match.";
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

  <title>Login</title>
  <link rel="shortcut icon" href="assets/img/favicon.ico">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-lg-7">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">

              <div class="col-lg">

                <div class="p-5">

                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Login Page</h1>
                  </div>
                  
                  <?php

                  if (isset($message)) {

                    for ($i=0; $i < count($message) ; $i++) { 
                      ?>

                      <div class="alert alert-danger">
                        <?= $message[$i]; ?>
                      </div>

                      <?php
                    }
                    
                  }

                  ?>
                  
                  <form class="user" method="POST" action="">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" value="<?= isset($_POST['btn-login']) ? $_POST['txt_username_email']:""; ?>" name="txt_username_email" id="username" aria-describedby="emailHelp" placeholder="Enter Username or Email Address...">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password">
                    </div>

                    <div class="form-group">
                     <input type="submit" class="btn btn-primary btn-user btn-block" name="btn-login" value="Login">
                   </div>

                 </form>

                 <hr>
                 <div class="text-center">
                  <a class="small" href="forgot_password.php">Forgot Password?</a>
                </div>
                <div class="text-center">
                  <a class="small" href="register.php">Create an Account!</a>
                </div>

              </div>
            </div>

          </div>

        </div>
      </div>

    </div>

  </div>

</div>
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="assets/js/sb-admin-2.min.js"></script>

</body>

</html>
