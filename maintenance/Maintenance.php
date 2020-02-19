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

	public function save_admin($username, $password)
	{

		try{

			$newPassword = password_hash($password, PASSWORD_DEFAULT);

			$stmt = $this->conn->prepare("INSERT INTO admin(username, password) VALUES (:username, :password)");
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

	public function cek_username_admin($username)
	{

		$stmt = $this->conn->prepare("SELECT username FROM admin WHERE username = :username");
		$stmt->execute(array(':username' => $username));

		if ($stmt->rowCount() > 0) {
			return FALSE;
		}else{
			return TRUE;
		}

	}

	public function update_admin($username, $passBaru = "", $passLama = "")
	{

		try{

			if ($passBaru !="") {
				// cek pass lama dan generate password baru
			}

			$sql ="UPDATE admin SET username = :username";

			if ($passBaru != "") {
				$sql .= " ,password = :password";
			}

			$sql .=" WHERE id = :id";

			$stmt = $this->conn->prepare("UPDATE users SET last_visit = :last_visit WHERE id = :id");
			$stmt->execute(array(':id' => $userRow->id, ':last_visit' => $this->time));

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function delete_admin($idAdmin)
	{

		try {

		    $stmt_delete = $this->conn->prepare('DELETE FROM admin WHERE id_admin =:id_admin');
		    $stmt_delete->bindParam(":id_admin", $idAdmin);
		      
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