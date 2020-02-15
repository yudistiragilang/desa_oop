<?php

	/**
	 *  Author : Yudistira Gilang Adisetyo
	 *  Email  : yudhistiragilang22@gmail.com
	 *  
	 */

	class Connection
	{
		
		private $servername = "localhost";
		private $username = "root";
		private $password = "";
		private $database = "desa";
		public $conn;

		public function db_connection()
		{
			
			$this->conn = null;

			try {
				$this->conn = new PDO("mysql:host=".$this->servername.";dbname=".$this->database, $this->username, $this->password);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(PDOException $e)
			{
				echo "Connection failed: " . $e->getMessage();
			}
			
			return $this->conn;

		}
		
	}
	
?>