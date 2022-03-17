<?php
class MyDB extends mysqli {
	private $db_host  = "localhost";
	private $db_login = "root";
	private $db_pass  = "";
	private $db_table = "fbdata";

	public $last_insert_id;
	public $last_affected_rows;
	public $last_select_data;
	public $last_select_count;
	public $last_select_columns;
	public $last_deleted_rows;

	public function __construct()
	{
		parent::__construct($this->db_host, $this->db_login, $this->db_pass, $this->db_table);

		if (mysqli_connect_error()) {
			$msg = 'Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error();
			Errors::set(__METHOD__, $msg);
			return 1;
		}

		return 0;
	}

	public function executeSelect($query)
	{
		$data = array();
		$result = $this->query($query);

		if (! $this->error) {
			for ($i = 1; $i <= $result->num_rows; $i ++){
				$data[$i] = $result->fetch_array(MYSQLI_ASSOC);
			}
			$this->last_select_data = $data;
			$this->last_select_count = $result->num_rows;
			$this->last_select_columns = $result->fetch_fields();

			$error = 0;
		}else{
			$error = 1;
		}

		return $error;


	}

	public function executeInsert($query)
	{
		$error = 0;
		$this->query($query);
		if (! $this->error){
			$this->last_insert_id = $this->insert_id;
		}else{
			$error = 1;
		}

		return $error;

	}

	public function executeUpdate($query)
	{
		$error = 0;
		$this->query($query);
		if (! $this->error){
			$this->last_affected_rows = $this->affected_rows;
		}else{
			$error = 1;
		}

		return $error;

	}

	public function executeDelete($query)
	{
		$error = 0;
		$this->query($query);
		if (! $this->error){
			$this->last_deleted_rows = $this->affected_rows;
		}else{
			$error = 1;
		}

		return $error;

	}
}
?>
