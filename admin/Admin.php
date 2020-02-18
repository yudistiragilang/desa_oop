<?php 

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/

// require_once '../connection.php';

class Admin
{
	
	private $conn;

	function __construct()
	{
		
		$database = new Connection();
		$db = $database->db_connection();
		$this->conn = $db;

	}

	public function count_user()
	{

		$stmt = $this->conn->prepare("SELECT * FROM users");
		$stmt->execute();
		$userRow = $stmt->fetch(PDO::FETCH_OBJ);

		return $stmt->rowCount();

	}

	public function get_user($value='')
	{
		$stmt = $this->conn->prepare("SELECT * FROM users");
		$stmt->execute();
		$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

		return $userRow;
	}

}

?>