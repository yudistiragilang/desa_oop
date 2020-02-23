<?php

	/**
	 *  Author : Yudistira Gilang Adisetyo
	 *  Email  : yudhistiragilang22@gmail.com
	 *  
	 */

session_start();

require_once 'database/Login.php';

$db = new Login();

if ($db->is_logged_in() != "") {
	
	$db->redirect('home.php');

}

if (isset($_POST['btn-register'])) {
	
	$error = 0;
	$errorMessage = array();


	$fullname = strip_tags($_POST['fullname']);
	$dbname = strip_tags($_POST['username']);
	$email = strip_tags($_POST['email']);
	$phone = strip_tags($_POST['phone']);
	$alamat = strip_tags($_POST['alamat']);
	$password = strip_tags($_POST['password']);
	$rePassword = strip_tags($_POST['repassword']);

	if ($fullname == "") {
		
		$errorMessage[] = "Nama tidak boleh kosong !";
		$error = 1;
	
	}elseif ($alamat == "") {

		$errorMessage[] = "Alamat tidak boleh kosong !";
		$error = 1;

	}else if ($dbname == "") {
		
		$errorMessage[] = "Username tidak boleh kosong !";
		$error = 1;
	
	}else if ($email == "") {
		
		$errorMessage[] = "Email tidak boleh kosong !";
		$error = 1;
	
	}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

		$errorMessage[] = "Email tidak valid !";
		$error = 1;

	}else if ($phone == "") {
		
		$errorMessage[] = "Phone tidak boleh kosong !";
		$error = 1;
	
	}else if ($password == "") {
		
		$errorMessage[] = "Password tidak boleh kosong !";
		$error = 1;
	
	}else if (strlen($password) < 6 ) {
		
		$errorMessage[] = "Password harus lebih dari 6 karakter !";
		$error = 1;
	
	} else if ($password != $rePassword) {
		
		$errorMessage[] = "Password dan Re Password tidak sama !";
		$error = 1;
	
	}else{

		try{

			$stmt = $db->run_query("SELECT username FROM users WHERE username = :username");
			$stmt->execute(array(':username' => $dbname));
			$row = $stmt->fetch(PDO::FETCH_OBJ);

			if ($row->username == $dbname) {

				$errorMessage[] = "Username tidak tersedia !";
				$error = 1;
				
			}

		}catch(PDOException $e){

			echo $e->getMessage();

		}
	
	}

	if($error == 0){

		if ($db->register($dbname, $password, $fullname, $phone, $email, $alamat)) {
			
			$db->redirect('index.php');

		}else{

			$error = "Failed create a account !";
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

	<title>Register</title>
	<link rel="shortcut icon" href="assets/img/favicon.ico">
	<link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

	<div class="container">

		<div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
			<div class="card-body p-0">
				<div class="row">

					<div class="col-lg">

						<div class="p-5">

							<div class="text-center">
								<h1 class="h4 text-gray-900 mb-4">Create an Account !</h1>
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

							?>

							<form class="user" method="POST" action="">

								<div class="form-group">
									<input type="text" class="form-control form-control-user" value="<?= isset($_POST['btn-register']) ? $_POST['fullname']:""; ?>" name="fullname" id="fullname" placeholder="Fullname">
								</div>

								<div class="form-group">
									<input type="text" class="form-control form-control-user" value="<?= isset($_POST['btn-register']) ? $_POST['username']:""; ?>" name="username" id="username" placeholder="Username">
								</div>

								<div class="form-group">
									<input type="email" class="form-control form-control-user" value="<?= isset($_POST['btn-register']) ? $_POST['email']:""; ?>" name="email" id="email" placeholder="Email Address">
								</div>

								<div class="form-group">
									<input type="text" class="form-control form-control-user" value="<?= isset($_POST['btn-register']) ? $_POST['phone']:""; ?>" name="phone" id="phone" placeholder="Phone Number">
								</div>

								<div class="form-group">
									<input type="text" class="form-control form-control-user" value="<?= isset($_POST['btn-register']) ? $_POST['alamat']:""; ?>" name="alamat" id="alamat" placeholder="Alamat">
								</div>

								<div class="form-group row">
									<div class="col-sm-6 mb-3 mb-sm-0">
										<input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password">
									</div>
									<div class="col-sm-6">
										<input type="password" class="form-control form-control-user" name="repassword" id="repassword" placeholder="Repeat Password">
									</div>
								</div>

								<div class="form-group">
									<button type="submit" name="btn-register" class="btn btn-primary btn-user btn-block">Register Account</button>
								</div>

							</form>

							<hr>
							<div class="text-center">
								<a class="small" href="forgot_password.php">Forgot Password?</a>
							</div>
							<div class="text-center">
								<a class="small" href="index.php">Already have an account? Login!</a>
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
