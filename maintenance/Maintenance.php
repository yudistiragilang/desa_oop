<?php 

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/

class Maintenance
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

	public function count_data($tabel)
	{

		$stmt = $this->conn->prepare("SELECT * FROM ".$tabel);
		$stmt->execute();
		$userRow = $stmt->fetch(PDO::FETCH_OBJ);

		return $stmt->rowCount();

	}

	public function get_data($tabel, $available = false)
	{
		$data = array();
		$sql = "";
		if ($available !=false) {
			$sql .= " WHERE available = :available";
		}

		$stmt = $this->conn->prepare("SELECT * FROM ".$tabel.$sql);

		if ($available !=false) {
			
			$yes = 1;
			$stmt->bindParam();
			$stmt->bindParam(":available", $yes);

		}

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
			$role = 2;
			$inactive = 0;
			$available = 1;

			$stmt = $this->conn->prepare("INSERT INTO users(username, password, created_date, inactive, role, available) VALUES (:username, :password, :created_date, :inactive, :role, :available)");
			$stmt->bindParam(":username", $username);
			$stmt->bindParam(":password", $newPassword);
			$stmt->bindParam(":created_date", $this->time);
			$stmt->bindParam(":inactive", $inactive);
			$stmt->bindParam(":role", $role);
			$stmt->bindParam(":available", $available);

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

	public function update_users($idUser, $username, $password = "")
	{

		try{

			$passBaru = password_hash($password, PASSWORD_DEFAULT);

			$sql ="UPDATE users SET username = :username";

			if ($password != "") {
				$sql .= " ,password = :password";
			}

			$sql .=" WHERE user_id = :user_id";


			$stmt = $this->conn->prepare($sql);			
			$stmt->bindParam(':user_id',$idUser);
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

	public function delete_users($idUser)
	{

		try {

		    $stmt_delete = $this->conn->prepare('DELETE FROM users WHERE user_id =:user_id');
		    $stmt_delete->bindParam(":user_id", $idUser);
		      
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

	public function update_status_user($idUser, $value = '')
	{
		try{

			$sql ="UPDATE users SET available = :available WHERE user_id = :user_id";
			$stmt = $this->conn->prepare($sql);			
			$stmt->bindParam(':user_id', $idUser);
			$stmt->bindParam(':available', $value);

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

	public function cek_role($user_id)
	{

		$stmt = $this->conn->prepare("SELECT role FROM users WHERE user_id = :user_id");
		$stmt->execute(array(':user_id' => $user_id));

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data = $dt['role'];
		}

		return $data;

	}

	public function get_pelanggan($idUser = '', $idPelanggan = '')
	{

		$data = array();

		$sql ="SELECT * FROM pelanggan LEFT JOIN users ON users.user_id=pelanggan.user_id";
		
		if($idUser !=''){

			$sql .=" WHERE users.user_id=".$idUser;

		}

		if($idPelanggan !=''){

			$sql .=" WHERE pelanggan.id_pelanggan=".$idPelanggan;

		}

		$stmt = $this->conn->prepare($sql);
		$stmt->execute();

		while ($dt = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $dt;
		}

		return $data;
	}

	public function delete_pelanggan($idPelanggan)
	{

		try {

		    $this->conn->beginTransaction();

			$stmt_delete = $this->conn->prepare('DELETE FROM pelanggan WHERE id_pelanggan =:id_pelanggan');
			$stmt_delete->bindParam(":id_pelanggan", $idPelanggan);  
			$stmt_delete->execute();

		    $this->conn->commit();
		    return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function save_pelanggan($nama, $email, $alamat, $telepon, $user)
	{

		try{

			$this->conn->beginTransaction();

			$stmt = $this->conn->prepare("INSERT INTO pelanggan(nama, email, alamat, no_telepon, user_id) VALUES (:nama, :email, :alamat, :no_telepon, :user_id)");
			$stmt->bindParam(":nama", $nama);
			$stmt->bindParam(":email", $email);
			$stmt->bindParam(":alamat", $alamat);
			$stmt->bindParam(":no_telepon", $telepon);
			$stmt->bindParam(":user_id", $user);

			$stmt->execute();
			$this->conn->commit();
			return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function update_pelanggan($idPelanggan, $nama, $email, $alamat, $telepon)
	{

		try{

			$this->conn->beginTransaction();

			$sql ="UPDATE pelanggan SET nama = :nama, email = :email, alamat = :alamat, no_telepon = :telepon WHERE id_pelanggan = :id_pelanggan";
			$stmt = $this->conn->prepare($sql);	

			$stmt->bindParam(':id_pelanggan', $idPelanggan);
			$stmt->bindParam(':nama', $nama);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':alamat', $alamat);
			$stmt->bindParam(':telepon', $telepon);

			$stmt->execute();
			$this->conn->commit();

			return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function cek_kode_service($kode)
	{

		$sql ="SELECT * FROM service_master WHERE kode_service = :kode_service";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(":kode_service", $kode);
		$stmt->execute();

		if($stmt->rowCount() > 0){

			return FALSE;

		}else{

			return TRUE;

		}

	}

	public function save_service($kode_service, $description)
	{

		try{

			$this->conn->beginTransaction();
			$inactive = 0;

			$data = $this->get_pelanggan($_SESSION['user_session']);
			foreach ($data as $key) {
				$idPelanggan = $key['id_pelanggan'];
			}

			$stmt = $this->conn->prepare("INSERT INTO service_master(kode_service, description, created_by, created_date, inactive) VALUES (:kode_service, :description, :created_by, :created_date, :inactive)");
			$stmt->bindParam(":kode_service", $kode_service);
			$stmt->bindParam(":description", $description);
			$stmt->bindParam(":created_by", $idPelanggan);
			$stmt->bindParam(":created_date", $this->time);
			$stmt->bindParam(":inactive", $inactive);

			$stmt->execute();
			$this->conn->commit();
			return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function update_service($description, $idService)
	{

		try{

			$this->conn->beginTransaction();

			$sql ="UPDATE service_master SET description = :description WHERE service_id = :service_id";
			$stmt = $this->conn->prepare($sql);	

			$stmt->bindParam(':service_id', $idService);
			$stmt->bindParam(':description', $description);

			$stmt->execute();
			$this->conn->commit();

			return TRUE;

		}catch(PDOException $e){

			$this->conn->rollback();
			echo $e->getMessage();
			return FALSE;

		}

	}

	public function delete_service($idService)
	{

		try {

		    $stmt_delete = $this->conn->prepare('DELETE FROM service_master WHERE service_id =:service_id');
		    $stmt_delete->bindParam(":service_id", $idService);
		      
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

	public function update_active_inactive($table, $field, $id, $value = '')
	{
		try{

			$sql ="UPDATE ".$table." SET inactive = :inactive WHERE ".$field." = :".$field;
			$stmt = $this->conn->prepare($sql);			
			$stmt->bindParam(':'.$field.'', $id);
			$stmt->bindParam(':inactive', $value);

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


}

?>