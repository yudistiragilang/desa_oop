<?php
	/**
	 *  Author : Yudistira Gilang Adisetyo
	 *  Email  : yudhistiragilang22@gmail.com
	 *  
	 */
	
	require __DIR__.'/Connection.php';

	class Login
	{
		
		private $conn;
		public $time;

		public function __construct()
		{
			
			$database = new Connection();

			$db = $database->db_connection();
			$this->conn = $db;

			date_default_timezone_set("Asia/Bangkok");
			$this->time = date('Y/m/d H:i:s');
		
		}

		public function run_query($sql)
		{
			
			$stmt = $this->conn->prepare($sql);
			return $stmt;
		
		}

		public function register($username, $password, $realName, $phone, $email, $alamat)
		{
			
			try{
				
				$newPassword = password_hash($password, PASSWORD_DEFAULT);
				$inactive = 0;
				$role = 2;
				$available = 0;

				$this->conn->beginTransaction();

				$stmt = $this->conn->prepare("INSERT INTO users(username, password, created_date, inactive, role, available) VALUES (:username, :password, :created_date, :inactive, :role, :available)");

				$stmt->bindParam(":username", $username);
				$stmt->bindParam(":password", $newPassword);
				$stmt->bindParam(":created_date", $this->time);
				$stmt->bindParam(":inactive", $inactive);
				$stmt->bindParam(":role", $role);
				$stmt->bindParam(":available", $available);

				$stmt->execute();
				$idUser = $this->conn->lastInsertId();
				$foto = "default.jpg";

				$stmtPelanggan = $this->conn->prepare("INSERT INTO pelanggan(nama, email, alamat, no_telepon, user_id, foto) VALUES (:nama, :email, :alamat, :no_telepon, :user_id, :foto)");
				$stmtPelanggan->bindParam(":nama", $realName);
				$stmtPelanggan->bindParam(":email", $email);
				$stmtPelanggan->bindParam(":alamat", $alamat);
				$stmtPelanggan->bindParam(":no_telepon", $phone);
				$stmtPelanggan->bindParam(":user_id", $idUser);
				$stmtPelanggan->bindParam(":foto", $foto);
				$stmtPelanggan->execute();

				$this->conn->commit();
				return $stmt;
			
			}catch(PDOException $e){

				$this->conn->rollback();
				echo $e->getMessage();

			}
		
		}

		public function do_login($username, $password)
		{
			
			try{
				
				$inactive = 0;

				$stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username AND inactive = :inactive");
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':inactive', $inactive);
				$stmt->execute();
				$userRow = $stmt->fetch(PDO::FETCH_OBJ);

				if ($stmt->rowCount() == 1) {
					
					if (password_verify($password, $userRow->password)) {

						$_SESSION['user_session'] = $userRow->user_id;

						$this->conn->beginTransaction();

						$stmt = $this->conn->prepare("UPDATE users SET last_visit = :last_visit WHERE user_id = :user_id");
						$stmt->execute(array(':user_id' => $userRow->user_id, ':last_visit' => $this->time));
						
						$this->conn->commit();

						return TRUE;
					
					}else{

						$this->conn->rollback();
						return FALSE;
					
					}
				
				}


			}catch(PDOException $e){

				echo $e->getMessage();

			}
		
		}

		public function is_logged_in()
		{
			if (isset($_SESSION['user_session'])) {
				
				return TRUE;
			
			}
		
		}

		public function redirect($url)
		{
			
			header("Location:".$url);

		}

		public function do_logout()
		{
			
			session_destroy();
			unset($_SESSION['user_session']);
			return TRUE;
		
		}

		public function user_online()
		{
			$stmt = $this->conn->prepare("SELECT * FROM users JOIN pelanggan ON pelanggan.user_id=users.user_id WHERE users.user_id = :user_id");
			$stmt->execute(array(":user_id" => $_SESSION['user_session']));
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			return $userRow;
		}

		public function sql_to_date($original_date)
		{
			$timestamp = strtotime($original_date);
			$new_date = date("d-M-Y", $timestamp);
			return $new_date;
		}

		public function date_to_sql($original_date)
		{
			$timestamp = strtotime($original_date);
			$new_date = date("Y-m-d", $timestamp);
			return $new_date;
		}

		public function update_profil($id_pelanggan, $nama, $alamat, $no_telepon, $foto='')
		{

			try{

				$this->conn->beginTransaction();

				$sql = "UPDATE pelanggan SET nama = :nama, alamat = :alamat, no_telepon = :no_telepon";

				if ($foto != '') {

					$sql .=", foto = :foto";

				}

				$sql .=" WHERE id_pelanggan = :id_pelanggan";

				$stmt = $this->conn->prepare($sql);			
				
				$stmt->bindParam(':id_pelanggan', $id_pelanggan);

				$stmt->bindParam(':nama', $nama);
				$stmt->bindParam(':alamat', $alamat);
				$stmt->bindParam(':no_telepon', $no_telepon);
				
				if ($foto !='') {

					$stmt->bindParam(':foto', $foto);
				
				}
				
				$stmt->execute();

				$this->conn->commit();

				return TRUE;

			}catch(PDOException $e){

				$this->conn->rollback();
				echo $e->getMessage();
				return FALSE;

			}

		}

		public function change_password($idUserActive, $passwordNew, $passwordActive)
		{

			$stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
			$stmt->bindParam(':user_id', $idUserActive);
			$stmt->execute();
			$userRow = $stmt->fetch(PDO::FETCH_OBJ);

			if (password_verify($passwordActive, $userRow->password)) {

				$this->conn->beginTransaction();
				$newPassword = password_hash($passwordNew, PASSWORD_DEFAULT);

				$stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
				
				$stmt->bindParam(':password', $newPassword);
				$stmt->bindParam(':user_id', $idUserActive);
				
				$stmt->execute();
						
				$this->conn->commit();

				return TRUE;
					
			}else{

				$this->conn->rollback();
				return FALSE;
					
			}

		}

		public function cek_foto_used($idUserActive)
		{
			
			$stmt = $this->conn->prepare("SELECT * FROM pelanggan WHERE user_id = :user_id");
			$stmt->bindParam(':user_id', $idUserActive);
			$stmt->execute();
			$userRow = $stmt->fetch(PDO::FETCH_OBJ);

			return $userRow->foto;
		
		}

	}

?>