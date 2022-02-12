<?php
date_default_timezone_set("Asia/Kolkata");

class users
{
	private $_db;
	private $_method;
	private $_getParams = null;
	private $_postParams = null;

	public function __construct($db,$method,$getParams,$postParams){

		$this->_db = $db->getInstance();
		$this->_method = $method;
		$this->_getParams = $getParams;
		$size = sizeof($this->_getParams);
		$this->_postParams = $postParams;

		if($this->_method=='GET')
		{
			
			switch ($size) {
				case 0:
					//$this->getUsers();
					break;
				case 1:
					$this->getUsers($this->_getParams[0]);
					break;
				case 2:
					//$this->getAllCarsShifts(0,$today);
					break;
				default:
					$output = array();
					$output['error'] = 'no date provided';
					echo json_encode($output);
					break;
			}
		}
		elseif ($this->_method == 'POST')
		{
		}
	}

	private function getUsers($pump_id){
		$sql = "SELECT * FROM `users` WHERE `user_pump_id` = '".$pump_id."' AND `role` = 'operator' ;";
		$this->_db->query($sql);
		$this->_db->execute();

		$output=array();
		$json=array();
		
		if($this->_db->rowCount() > 0)
		{
			$r = $this->_db->resultset();
			foreach ($r as $row) {
				
				$json["user_id"]		= $row["user_id"];
				$json["name"]	 		= $row["name"];
				$json["user_pump_id"]	= $row["user_pump_id"];
				array_push($output, $json);
			}
		}
		echo json_encode($output,JSON_NUMERIC_CHECK);
	}
}
?>