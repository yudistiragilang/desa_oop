<?php

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/

class Transaction
{
	
	private $conn;
	public $time;

	function __construct()
	{
		
		$database = new Connection();
		$db = $database->db_connection();
		$this->conn = $db;

		date_default_timezone_set("Asia/Jakarta");
		$this->time = date('Y/m/d H:i:s');

	}

	public function get_data($tabel, $only_active = false)
	{
		$data = array();
		$sql = "";
		if ($only_active !=false) {
			$sql .= " WHERE inactive = :inactive";
		}

		$stmt = $this->conn->prepare("SELECT * FROM ".$tabel.$sql);

		if ($only_active !=false) {
			
			$yes = 0;
			$stmt->bindParam();
			$stmt->bindParam(":inactive", $yes);

		}

		$stmt->execute();

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $dt;
		}

		return $data;
	}

	public function get_status($id)
	{
		$status = "";
		switch ($id) {
			    case 0:
			        $status = "OPEN";
			        break;
			    case 1:
			        $status = "APPROVED";
			        break;
			    case -1:
			        $status = "REJECT";
			        break;
			    default:
			        $status;
			}

		return $status;

	}

	public function reset()
	{
		unset($_POST);
	}

	public function get_data_pesanan($status = false, $id_pelanggan = '')
	{
		$data = array();
		$sql = "";
		if ($status !=false) {
			$sql .= " WHERE status = :status";
		}

		if ($id_pelanggan !="") {
			$sql .= "AND pelanggan.id_pelanggan = :id_pelanggan";
		}

		$stmt = $this->conn->prepare("SELECT * FROM pemesanan JOIN pelanggan ON(pemesanan.id_pelanggan=pelanggan.id_pelanggan) JOIN service_master ON(service_master.service_id=pemesanan.service_id)".$sql);

		if ($status !=false) {

			$stmt->bindParam(":status", $status);

		}

		if ($id_pelanggan !="") {

			$stmt->bindParam(":id_pelanggan", $id_pelanggan);

		}

		$stmt->execute();

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $dt;
		}

		return $data;
	}

	public function save_pesanan($id_pelanggan, $service_id, $memo)
	{

		try{

			$this->conn->beginTransaction();
			$status = 0;
			$created_by = $_SESSION['user_session'];

			$stmt = $this->conn->prepare("INSERT INTO pemesanan(id_pelanggan, service_id, memo, created_date, created_by, status) VALUES (:id_pelanggan, :service_id, :memo, :created_date, :created_by, :status)");
			$stmt->bindParam(":id_pelanggan", $id_pelanggan);
			$stmt->bindParam(":service_id", $service_id);
			$stmt->bindParam(":memo", $memo);
			$stmt->bindParam(":created_date", $this->time);
			$stmt->bindParam(":created_by", $created_by);
			$stmt->bindParam(":status", $status);
			$stmt->execute();

			$this->conn->commit();
			return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function update_pesanan($idPesan, $id_pelanggan, $service_id, $memo)
	{

		try{

			$this->conn->beginTransaction();

			$stmt = $this->conn->prepare("UPDATE pemesanan SET id_pelanggan = :id_pelanggan, service_id = :service_id, memo = :memo WHERE id_pesan = :id_pesan");			
			$stmt->bindParam(':id_pesan', $idPesan);
			$stmt->bindParam(':id_pelanggan', $id_pelanggan);
			$stmt->bindParam(':service_id', $service_id);
			$stmt->bindParam(':memo', $memo);
			$stmt->execute();

			$this->conn->commit();

			return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function delete_pesanan($idPesan)
	{

		try {

		    $this->conn->beginTransaction();

		    $stmt_delete = $this->conn->prepare('DELETE FROM pemesanan WHERE id_pesan =:id_pesan');
		    $stmt_delete->bindParam(":id_pesan", $idPesan);  
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