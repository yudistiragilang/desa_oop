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

				$this->conn->beginTransaction();

				$stmt = $this->conn->prepare("INSERT INTO users(username, password, created_date, inactive, role) VALUES (:username, :password, :created_date, :inactive, :role)");

				$stmt->bindParam(":username", $username);
				$stmt->bindParam(":password", $newPassword);
				$stmt->bindParam(":created_date", $this->time);
				$stmt->bindParam(":inactive", $inactive);
				$stmt->bindParam(":role", $role);

				$stmt->execute();
				$idUser = $this->conn->lastInsertId();

				$stmtPelanggan = $this->conn->prepare("INSERT INTO pelanggan(nama, email, alamat, no_telepon, user_id) VALUES (:nama, :email, :alamat, :no_telepon, :user_id)");
				$stmtPelanggan->bindParam(":nama", $realName);
				$stmtPelanggan->bindParam(":email", $email);
				$stmtPelanggan->bindParam(":alamat", $alamat);
				$stmtPelanggan->bindParam(":no_telepon", $phone);
				$stmtPelanggan->bindParam(":user_id", $idUser);
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
				
				$stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
				$stmt->execute(array(":username" => $username));
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
	
	}

?>