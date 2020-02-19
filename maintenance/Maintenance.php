<?php 

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/

class Maintenance
{
	
	private $conn;

	function __construct()
	{
		
		$database = new Connection();
		$db = $database->db_connection();
		$this->conn = $db;

	}

	public function count_data($tabel)
	{

		$stmt = $this->conn->prepare("SELECT * FROM ".$tabel);
		$stmt->execute();
		$userRow = $stmt->fetch(PDO::FETCH_OBJ);

		return $stmt->rowCount();

	}

	public function get_data($tabel)
	{
		$data = array();
		$stmt = $this->conn->prepare("SELECT * FROM ".$tabel);
		$stmt->execute();

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $dt;
		}

		return $data;
	}

	public function save_users($username, $password)
	{

		try{

			$newPassword = password_hash($password, PASSWORD_DEFAULT);

			$stmt = $this->conn->prepare("INSERT INTO users(username, password) VALUES (:username, :password)");
			$stmt->bindParam(":username", $username);
			$stmt->bindParam(":password", $newPassword);

			$this->conn->beginTransaction();
			$stmt->execute();
			$this->conn->commit();
			return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function cek_username($username)
	{

		$stmt = $this->conn->prepare("SELECT username FROM users WHERE username = :username");
		$stmt->execute(array(':username' => $username));

		if ($stmt->rowCount() > 0) {
			return FALSE;
		}else{
			return TRUE;
		}

	}

	public function update_users($idAdmin, $username, $password = "")
	{

		try{

			$passBaru = password_hash($password, PASSWORD_DEFAULT);

			$sql ="UPDATE users SET username = :username";

			if ($password != "") {
				$sql .= " ,password = :password";
			}

			$sql .=" WHERE user_id = :user_id";


			$stmt = $this->conn->prepare($sql);			
			$stmt->bindParam(':user_id',$idAdmin);
			$stmt->bindParam(':username',$username);

			if ($password !="") {
				$stmt->bindParam(':password',$passBaru);
			}

			$this->conn->beginTransaction();
			$stmt->execute();
			$this->conn->commit();

			return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function delete_users($idAdmin)
	{

		try {

		    $stmt_delete = $this->conn->prepare('DELETE FROM users WHERE user_id =:user_id');
		    $stmt_delete->bindParam(":user_id", $idAdmin);
		      
		    $this->conn->beginTransaction();
		    $stmt_delete->execute();
		    $this->conn->commit();

		    return TRUE;

		}catch(PDOException $e){

		    $this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

}

?>