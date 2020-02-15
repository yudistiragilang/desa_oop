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

		public function register($username, $password, $realName, $phone, $email)
		{
			
			try{
				
				$newPassword = password_hash($password, PASSWORD_DEFAULT);
				$inactive = 0;
				$delete_mark = 0;

				$stmt = $this->conn->prepare("INSERT INTO users(user_id, password, real_name, phone, email, created_date, inactive, delete_mark) VALUES (:username, :password, :real_name, :phone, :email, :created_date, :inactive, :delete_mark)");

				$stmt->bindParam(":username", $username);
				$stmt->bindParam(":password", $newPassword);
				$stmt->bindParam(":real_name", $realName);
				$stmt->bindParam(":phone", $phone);
				$stmt->bindParam(":email", $email);
				$stmt->bindParam(":created_date", $this->time);
				$stmt->bindParam(":inactive", $inactive);
				$stmt->bindParam(":delete_mark", $delete_mark);

				$stmt->execute();
				return $stmt;
			
			}catch(PDOException $e){

				echo $e->getMessage();

			}
		
		}

		public function do_login($username, $email, $password)
		{
			
			try{
				
				$stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = :username OR email = :email");
				$stmt->execute(array(":username" => $username, ":email" => $email));
				$userRow = $stmt->fetch(PDO::FETCH_OBJ);

				if ($stmt->rowCount() == 1) {
					
					if (password_verify($password, $userRow->password)) {

						$_SESSION['user_session'] = $userRow->id;

						$stmt = $this->conn->prepare("UPDATE users SET last_visit = :last_visit WHERE id = :id");
						$stmt->execute(array(':id' => $userRow->id, ':last_visit' => $this->time));
						
						return TRUE;
					
					}else{

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
	
	}

?>