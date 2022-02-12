<?php
date_default_timezone_set("Asia/Kolkata");

class Customers
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
			$today = date("Y-m-d");
			switch ($size) {
				case 0:
					// 
					break;
				case 1:
					$this->getAllCustomers($this->_getParams[0]);
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
			$this->addCustomer($this->_postParams);			
		}
	}


	// add customer
	private function addCustomer($postParams){

		if(!isset($_SESSION))
		{
			session_start();
		}

		$output = array();
		$valid = true;

		$cust_type  	 = $postParams['cust_type'];
		$cust_id    	 = $postParams['cust_id'];


		$cust_f_name	 = $postParams['cust_f_name'];
		$cust_f_name	 = strtolower(trim($cust_f_name)); 

		$cust_m_name	 = $postParams['cust_m_name'];
		$cust_m_name	 = strtolower(trim($cust_m_name));

		$cust_l_name	 = $postParams['cust_l_name'];
		$cust_l_name	 = strtolower(trim($cust_l_name));

		$cust_ph_no	 	 = $postParams['cust_ph_no'];

		$cust_service	 = $postParams['cust_service'];

		$cust_address	 = $postParams['cust_address'];
		$cust_address	 = strtolower(trim($cust_address));

		$cust_company	 = $postParams['cust_company'];
		$cust_company	 = trim($cust_company);
		if($cust_company == ""){ 
			$cust_company == NULL; 
			$cust_disp_name = $cust_f_name.' '.$cust_l_name;
		}else{
			$cust_disp_name = $cust_company;
		}

		$cust_disp_name = ucwords($cust_disp_name);

		$cust_gst	 	 = $postParams['cust_gst'];
		$cust_gst	 	 = strtolower(trim($cust_gst));
		if($cust_gst == ""){ $cust_gst == NULL; }


		// check is first digit is not 0, 
		// check if numeric
		// check if 10 digits
		if(!$this->checkNumber($cust_ph_no)){
			$valid = false;
			$output['msg'] = 'Invalid Phone Number';
		}

				
		//$cust_pump_id	 = 1;
		$pump_id	 = $postParams['pump_id'];
		$pump_id	 = trim($pump_id);
		
				
		$cust_post_paid	 = $postParams['cust_post_paid'];
		$cust_post_paid	 = trim($cust_post_paid);

		$cust_balance	 	 = $postParams['cust_balance'];		

		$cust_outstanding	 = $postParams['cust_outstanding'];		

		$cust_credit_limit   = $postParams['cust_credit_limit'];

		$cust_app_limit   = $postParams['cust_app_limit'];

		$cust_deposit		 = $postParams['cust_deposit'];	

		if ($cust_id == null ) {
			$account_num = $this->generateAccountNumber($cust_f_name, $cust_l_name);		
		}

		// ensure values are numeric
		if(!is_numeric($cust_balance) && !is_numeric($cust_outstanding) && !is_numeric($cust_credit_limit)){
			$valid = false;
			$output['msg'] = 'Invalid Customer Amounts';
		}



		if($valid && ($cust_id == NULL)){

			date_default_timezone_set("Asia/Kolkata");
			$date = date("Y-m-d H:i:s");		
			$table_name	  = "customers";
			$last_updated = strtotime($date);
			$id           = "cust_id";
			$unix = $last_updated;

			$sql = "INSERT INTO `customers` (`cust_f_name`,`cust_m_name`,`cust_l_name`,`cust_ph_no`,`cust_pump_id`,`cust_post_paid`,`cust_balance`,`cust_outstanding`,`cust_credit_limit`,`cust_app_limit`,`cust_address`,`cust_company`,`cust_gst`,`cust_acc_no`,`cust_deposit`,`cust_service`,`cust_disp_name`,`cust_last_updated`) 
			VALUES (:field1,:field2,:field3,:field4,:field5,:field6,:field7,:field8,:field9,:field10,:field11,:field12,:field13,:field14,:field15,:field16,:field17,:field18);";

			$this->_db->query($sql);

			$this->_db->bind(':field1', $cust_f_name);
			$this->_db->bind(':field2', $cust_m_name);
			$this->_db->bind(':field3', $cust_l_name);
			$this->_db->bind(':field4', $cust_ph_no);
			$this->_db->bind(':field5', $pump_id);
			$this->_db->bind(':field6', $cust_post_paid);
			$this->_db->bind(':field7', $cust_balance);
			$this->_db->bind(':field8', $cust_outstanding);
			$this->_db->bind(':field9', $cust_credit_limit);
			$this->_db->bind(':field10', $cust_app_limit);
			$this->_db->bind(':field11', $cust_address);
			$this->_db->bind(':field12', $cust_company);
			$this->_db->bind(':field13', $cust_gst);
			$this->_db->bind(':field14', $account_num);
			$this->_db->bind(':field15', $cust_deposit);
			$this->_db->bind(':field16', $cust_service);
			$this->_db->bind(':field17', $cust_disp_name);
			$this->_db->bind(':field18', $date);
			$this->_db->execute();

			
			
			// $this->updateSyncTable($table_name,$id,$unix);
			Globals::updateSyncTable($table_name, $id, $unix, $pump_id);
			
			$output['success'] = true;			
			echo json_encode($output);
		}
		else if($valid && ($cust_id != NULL)){


			date_default_timezone_set("Asia/Kolkata");
			$date = date("Y-m-d H:i:s");		
			$table_name	  = "customers";
			$last_updated = strtotime($date);

			$sql = "UPDATE `customers` SET `cust_f_name`= :field1,`cust_m_name`= :field2,`cust_l_name`= :field3,`cust_ph_no`= :field4,`cust_service`= :field5,`cust_address`= :field6,`cust_post_paid`= :field7,`cust_balance`= :field8,`cust_outstanding`= :field9,`cust_credit_limit`= :field10,`cust_app_limit`= :field11,`cust_deposit`= :field12,`cust_company`= :field13,`cust_gst`= :field14,`cust_last_updated`= :field15 WHERE `cust_id` = :field16 ;";

			$this->_db->query($sql);

			$this->_db->bind(':field1', $cust_f_name);
			$this->_db->bind(':field2', $cust_m_name);
			$this->_db->bind(':field3', $cust_l_name);
			$this->_db->bind(':field4', $cust_ph_no);
			$this->_db->bind(':field5', $cust_service);
			$this->_db->bind(':field6', $cust_address);
			$this->_db->bind(':field7', $cust_post_paid);
			$this->_db->bind(':field8', $cust_balance);
			$this->_db->bind(':field9', $cust_outstanding);
			$this->_db->bind(':field10', $cust_credit_limit);
			$this->_db->bind(':field11', $cust_app_limit);
			$this->_db->bind(':field12', $cust_deposit);
			$this->_db->bind(':field13', $cust_company);
			$this->_db->bind(':field14', $cust_gst);					
			$this->_db->bind(':field15', $date);
			$this->_db->bind(':field16', $cust_id);
			$this->_db->execute();				

			date_default_timezone_set("Asia/Kolkata");
			$date = date("Y-m-d H:i:s");		
			$table_name	  = "customers";
			$last_updated = strtotime($date);
			$id           = "cust_id";
			$unix = $last_updated;

			// $this->updateSyncTable($table_name,$id,$unix);
			Globals::updateSyncTable($table_name, $id, $unix, $pump_id);

			$output['success'] = true;			
			echo json_encode($output);	
		}
		else{
			$output['success'] = false;		
			echo json_encode($output);
		}		
	}


	// get all 
	private function getAllCustomers($pump_id){

		$sql = "SELECT * FROM `customers` WHERE  `cust_pump_id` = '".$pump_id."' ORDER BY `cust_company`;";
		$this->_db->query($sql);
		$this->_db->execute();

		$output=array();
		

		if($this->_db->rowCount() > 0)
		{
			$r = $this->_db->resultset();
			foreach ($r as $row) {
				$json=array();
				$json["cust_id"]	 	 		= $row["cust_id"];
				$json["cust_f_name"]	 		= $row["cust_f_name"];
				$json["cust_m_name"]	 		= $row["cust_m_name"];
				$json["cust_l_name"]	 		= $row["cust_l_name"];
				$json["cust_ph_no"]	 	 		= $row["cust_ph_no"];
				$json["cust_pump_id"]	 		= $row["cust_pump_id"];
				$json["cust_car_num"]	 		= $row["cust_car_num"];
				$json["cust_post_paid"]	 		= $row["cust_post_paid"];
				$json["cust_balance"]	 		= $row["cust_balance"];
				$json["cust_outstanding"]	 	= $row["cust_outstanding"];
				$json["cust_company"]	 		= $row["cust_company"];
				$json["cust_gst"]	 			= $row["cust_gst"];
				$json["cust_credit_limit"]	 	= $row["cust_credit_limit"];
				$json["cust_last_updated"]	 	= $row["cust_last_updated"];
				array_push($output, $json);
			}
			
		}
		echo json_encode($output,JSON_NUMERIC_CHECK);
	}


	// helper functions
	private function generateRandomNumber($length) {
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	private function generateAccountNumber($cust_f_name, $cust_l_name){
		$rand_num = $this->generateRandomNumber(4);
		$account_num = substr($cust_f_name, 0, 1).substr($cust_l_name, 0, 1).$rand_num;

		if(!$this->checkAccountNumber($account_num)){
			$account_num = $this->generateAccountNumber($cust_f_name, $cust_l_name);
			return $account_num;
		}
		else{
			return $account_num;
		}
	}

	private function checkAccountNumber($account_num){

		$sql = "SELECT 1 FROM `customers` WHERE `cust_acc_no` = '".$account_num."';";
		$this->_db->query($sql);
		$this->_db->execute();

		if($this->_db->rowCount() > 0){
			return false;
		}
		else{
			return true;	
		}		
	}

	// private function updateSyncTable($table_name, $id, $unix){
	// 	date_default_timezone_set("Asia/Kolkata");
	// 	$date = date("Y-m-d H:i:s");
		
	// 	$upload_dir =  realpath(__DIR__ . '/../../mysql_uploads');		
	// 	$filename = $upload_dir ."/".$table_name.'.sql';
	// 	// $db_name = "pump_master";
		
	// 	// exec("/usr/bin/mysqldump -u\"pump_master_user\" --password=\"pump_master_user123!@#\" \"".$db_name."\" \"".$table_name."\" > ".$filename);
	// 	// exec("/usr/bin/mysqldump -u\"".Globals::DB_USER_NAME."\" --password=\"".Globals::DB_PASSWORD."\" \"".Globals::DB_NAME."\" \"".$table_name."\" > ".$filename);
	// 	exec(Globals::MYSQLDUMP_PATH." -u\"".Globals::DB_USER_NAME."\" --password=\"".Globals::DB_PASSWORD."\" \"".Globals::DB_NAME."\" \"".$table_name."\" > ".$filename);

	// 	$sql = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = '".$table_name."';";
		
	// 	$this->_db->query($sql);
	// 	$this->_db->execute();
		
	// }

	private function checkNumber($cust_string){
		$success = true;				 	
		$ph_array = explode("|", $cust_string);


		// return valid digits if == 10, and not starting with 0
		foreach ($ph_array as $no) {
			// check is first digit is not 0, 
			// check if numeric
			// check if 10 digits
			if (preg_match("/^[1-9][0-9]{0,9}$/", $no, $match)){
				// less than 10 digits pass
				// check if exactly 10
				if (!preg_match('/^\d{10}$/', $no)) {
					// less or greater than 10					
					$success = false;					
				}			
			}
			else{				
				$success = false;				
			}
		}

		return $success;
	}

}
?>