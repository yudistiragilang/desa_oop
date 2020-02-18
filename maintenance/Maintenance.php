<?php 

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/

// require_once '../connection.php';

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

	public function get_user()
	{
		$user = array();
		$stmt = $this->conn->prepare("SELECT * FROM users");
		$stmt->execute();

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$user[] = $dt;
		}

		return $user;
	}

}

?>