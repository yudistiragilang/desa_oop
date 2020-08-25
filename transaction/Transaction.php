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

	public function get_data_pesanan($status = '', $id_pelanggan = '')
	{
		$data = array();
		$sql = "";
		if ($status !='') {
			$sql .= " WHERE status = :status";
		}

		if ($id_pelanggan !='') {
			$sql .= " AND pelanggan.id_pelanggan = :id_pelanggan";
		}

		$stmt = $this->conn->prepare("SELECT * FROM pemesanan JOIN pelanggan ON(pemesanan.id_pelanggan=pelanggan.id_pelanggan) JOIN service_master ON(service_master.service_id=pemesanan.service_id)".$sql);

		if ($status !='') {

			$stmt->bindParam(":status", $status);

		}

		if ($id_pelanggan !='') {

			$stmt->bindParam(":id_pelanggan", $id_pelanggan);

		}

		$stmt->execute();

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $dt;
		}

		return $data;
	}

	public function save_pesanan($id_pelanggan, $service_id, $memo, $harga)
	{

		try{

			$this->conn->beginTransaction();
			$status = 0;
			$created_by = $_SESSION['user_session'];

			$stmt = $this->conn->prepare("INSERT INTO pemesanan(id_pelanggan, service_id, harga, memo, created_date, created_by, status) VALUES (:id_pelanggan, :service_id, :harga, :memo, :created_date, :created_by, :status)");
			$stmt->bindParam(":id_pelanggan", $id_pelanggan);
			$stmt->bindParam(":service_id", $service_id);
			$stmt->bindParam(":harga", $harga);
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

	public function update_pesanan($idPesan, $id_pelanggan, $service_id, $memo, $harga)
	{

		try{

			$this->conn->beginTransaction();

			$stmt = $this->conn->prepare("UPDATE pemesanan SET id_pelanggan = :id_pelanggan, service_id = :service_id, harga = :harga, memo = :memo WHERE id_pesan = :id_pesan");			
			$stmt->bindParam(':id_pesan', $idPesan);
			$stmt->bindParam(':id_pelanggan', $id_pelanggan);
			$stmt->bindParam(':service_id', $service_id);
			$stmt->bindParam(':harga', $harga);
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

	public function change_status_pesanan($id_pesan, $value = '')
	{
		
		try{

			$this->conn->beginTransaction();
			$approve_by = $_SESSION['user_session'];

			$stmt = $this->conn->prepare("UPDATE pemesanan SET status = :status, approve_by = :approve_by, approve_date = :approve_date WHERE id_pesan = :id_pesan");
			$stmt->bindParam(':id_pesan', $id_pesan);
			$stmt->bindParam(':status', $value);
			$stmt->bindParam(':approve_by', $approve_by);
			$stmt->bindParam(':approve_date', $this->time);
			$stmt->execute();

			$this->conn->commit();

			return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function save_trans_service($id_pesan, $id_pelanggan, $service_id, $memo, $harga, $biaya_tambahan, $memo_biaya_tambahan)
	{
		
		try{

			$this->conn->beginTransaction();
			$status = 0;
			$created_by = $_SESSION['user_session'];

			$stmt = $this->conn->prepare("INSERT INTO service(id_pesan, id_pelanggan, service_id, harga, memo, created_date, created_by, status, biaya_tambahan, memo_biaya_tambahan) VALUES (:id_pesan, :id_pelanggan, :service_id, :harga, :memo, :created_date, :created_by, :status, :biaya_tambahan, :memo_biaya_tambahan)");

			$stmt->bindParam(":id_pesan", $id_pesan);
			$stmt->bindParam(":id_pelanggan", $id_pelanggan);
			$stmt->bindParam(":service_id", $service_id);
			$stmt->bindParam(":harga", $harga);
			$stmt->bindParam(":memo", $memo);
			$stmt->bindParam(":created_date", $this->time);
			$stmt->bindParam(":created_by", $created_by);
			$stmt->bindParam(":status", $status);
			$stmt->bindParam(":biaya_tambahan", $biaya_tambahan);
			$stmt->bindParam(":memo_biaya_tambahan", $memo_biaya_tambahan);

			$stmt->execute();

			$this->conn->commit();
			return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function cek_foreign($tabel, $field, $value)
	{

		$sql ="SELECT * FROM ".$tabel." WHERE ".$field." = :".$field;

		$stmt = $this->conn->prepare($sql);

		$stmt->bindParam(':'.$field, $value);
		
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			return FALSE;
		}else{
			return TRUE;
		}

	}

	public function get_data_service($status = '', $id_pelanggan = '')
	{
		$data = array();
		$sql = "";
		if ($status !='') {
			$sql .= " WHERE service.status = :status";
		}

		if ($id_pelanggan !='') {
			$sql .= " AND pelanggan.id_pelanggan = :id_pelanggan";
		}

		$stmt = $this->conn->prepare("SELECT * FROM service JOIN pelanggan ON(service.id_pelanggan=pelanggan.id_pelanggan) JOIN service_master ON(service_master.service_id=service.service_id)".$sql);

		if ($status !='') {

			$stmt->bindParam(":status", $status);

		}

		if ($id_pelanggan !='') {

			$stmt->bindParam(":id_pelanggan", $id_pelanggan);

		}

		$stmt->execute();

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $dt;
		}

		return $data;
	}

	public function get_data_service_by_id($id = '')
	{
		$data = array();
		$sql = "";
		if ($id !='') {
			$sql .= " WHERE service.id_service = :id_service";
		}

		$stmt = $this->conn->prepare("SELECT * FROM service JOIN pelanggan ON(service.id_pelanggan=pelanggan.id_pelanggan) JOIN service_master ON(service_master.service_id=service.service_id)".$sql);

		if ($id !='') {

			$stmt->bindParam(":id_service", $id);

		}

		$stmt->execute();

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $dt;
		}

		return $data;
	}


}

?>