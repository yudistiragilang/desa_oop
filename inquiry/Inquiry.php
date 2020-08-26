<?php

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/

class Inquiry
{
	
	function __construct()
	{

		$database = new Connection();
		$db = $database->db_connection();
		$this->conn = $db;

		date_default_timezone_set("Asia/Jakarta");
		$this->time = date('Y/m/d H:i:s');

	}

	public function get_data_pesanan($status = '', $id_pelanggan = '')
	{
		$data = array();
		$sql = "";
		if ($status !='') {
			$sql .= " WHERE status = :status";
		}

		if ($id_pelanggan !="") {
			$sql .= "AND pelanggan.id_pelanggan = :id_pelanggan";
		}

		$stmt = $this->conn->prepare("SELECT * FROM pemesanan JOIN pelanggan ON(pemesanan.id_pelanggan=pelanggan.id_pelanggan) JOIN service_master ON(service_master.service_id=pemesanan.service_id)".$sql);

		if ($status !='') {

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

	public function get_data_trans_service($status = '', $id_pelanggan = '')
	{
		$data = array();
		$sql = "";
		if ($status !='') {
			$sql .= " WHERE status = :status";
		}

		if ($id_pelanggan !="") {
			$sql .= "AND pelanggan.id_pelanggan = :id_pelanggan";
		}

		$stmt = $this->conn->prepare("SELECT * FROM service JOIN pelanggan ON(service.id_pelanggan=pelanggan.id_pelanggan) JOIN service_master ON(service_master.service_id=service.service_id)".$sql);

		if ($status !='') {

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

	public function update_status($table, $field, $id, $value = '')
	{
		try{

			$sql ="UPDATE ".$table." SET status = :status WHERE ".$field." = :".$field;
			$stmt = $this->conn->prepare($sql);			
			$stmt->bindParam(':'.$field.'', $id);
			$stmt->bindParam(':status', $value);

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

	public function get_pesanan_filter($id_service = '', $id_pelanggan = '', $date_from = '', $date_to = '')
	{
		
		$data = array();
		$sql = "";

		if ($date_from !='') {
			$sql .= " WHERE pemesanan.created_date BETWEEN :from_date AND :to_date";
		}

		if ($id_pelanggan !='') {
			$sql .= " AND pelanggan.id_pelanggan = :id_pelanggan";
		}

		if ($id_service !='') {
			$sql .= " AND service_master.service_id = :service_id";
		}

		$stmt = $this->conn->prepare("SELECT * FROM pemesanan JOIN pelanggan ON(pemesanan.id_pelanggan=pelanggan.id_pelanggan) JOIN service_master ON(service_master.service_id=pemesanan.service_id)".$sql);

		if ($date_from !='') {

			$stmt->bindParam(":from_date", $date_from);
			$stmt->bindParam(":to_date", $date_to);

		}

		if ($id_pelanggan !='') {

			$stmt->bindParam(":id_pelanggan", $id_pelanggan);

		}

		if ($id_service !='') {

			$stmt->bindParam(":service_id", $id_service);

		}

		$stmt->execute();

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $dt;
		}

		return $data;
	}

	public function get_service_filter($id_service = '', $id_pelanggan = '', $date_from = '', $date_to = '')
	{
		
		$data = array();
		$sql = "";

		if ($date_from !='') {
			$sql .= " WHERE service.created_date BETWEEN :from_date AND :to_date";
		}

		if ($id_pelanggan !='') {
			$sql .= " AND pelanggan.id_pelanggan = :id_pelanggan";
		}

		if ($id_service !='') {
			$sql .= " AND service_master.service_id = :service_id";
		}

		$stmt = $this->conn->prepare("SELECT * FROM service JOIN pelanggan ON(service.id_pelanggan=pelanggan.id_pelanggan) JOIN service_master ON(service_master.service_id=service.service_id)".$sql);

		if ($date_from !='') {

			$stmt->bindParam(":from_date", $date_from);
			$stmt->bindParam(":to_date", $date_to);

		}

		if ($id_pelanggan !='') {

			$stmt->bindParam(":id_pelanggan", $id_pelanggan);

		}

		if ($id_service !='') {

			$stmt->bindParam(":service_id", $id_service);

		}

		$stmt->execute();

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $dt;
		}

		return $data;
	}

	public function get_data_service($id = '')
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

	public function get_data_penjualan_per_hari()
	{
		$sql = "SELECT
					created_date,
					SUM(harga+biaya_tambahan) AS amount
				FROM
					service
				GROUP BY
					created_date";

		$stmt = $this->conn->prepare($sql);
		$stmt->execute();

		$data_tanggal = array();
		$data_total = array();
		while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
	      $data_tanggal[] = date('d-m-Y', strtotime($data['created_date']));
	      $data_total[] = $data['amount'];
	    }

	    return array($data_tanggal, $data_total);
	}

}

?>