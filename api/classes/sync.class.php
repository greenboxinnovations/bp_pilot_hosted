<?php

class Sync
{
	private $_db;
	private $_method;	
	private $_getParams = null;
	private $_postParams = null;

	public function __construct($db,$method,$getParams,$postParams){

		$this->_db = $db->getInstance();
		$this->_method = $method;
		$this->_getParams = $getParams;
		$this->_postParams = $postParams;

		$size = sizeof($this->_getParams);

		if($this->_method == 'GET'){
					
			$this->getAllSyncTable($this->_getParams[0]);
			
		} else if ($this->_method == 'POST') {

			$table_name = $this->_postParams['table_name'];
			$pump_id = $this->_postParams['pump_id'];
			$id = $this->_postParams['id'];
			$this->updateSyncFromLocal($table_name,$id, $pump_id);
		}
	}
	

	private function getAllSyncTable($pump_id){

		$sql = "SELECT MAX(`trans_id`) as max FROM `transactions` WHERE `pump_id` = '".$pump_id."';";
		$this->_db->query($sql);
		$r = $this->_db->single();
		$max = $r['max'];

		$sql = "SELECT * FROM `sync` WHERE `pump_id` = '".$pump_id."';";
		$this->_db->query($sql);
		$r = $this->_db->resultset();

		$json = array();		



		foreach($r as $row)
		{
			if ($row['table_name']=='transactions') {
				$t = array();
				$t['id'] = $max;
				$t['table_name'] = $row['table_name'];
				$t['pump_id'] = $row['pump_id'];
				$t['last_updated'] = $row['last_updated'];
				$t['sync_id'] = $row['sync_id'];
				
				array_push($json, $t);
			}
			// else if ($row['local_server']=='transactions') {}
			else{
				array_push($json, $row);
			}
			
		}
		
		
		// update local_server time
		$unix = strtotime(date("Y-m-d H:i:s"));
		//$unix = 123;
		$sql = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = 'local_server' AND `pump_id` = '".$pump_id."';";	
		$this->_db->query($sql);
		$this->_db->execute();		

		
		// $upload_dir =  realpath(__DIR__ . '/../../mysql_uploads');
		// $filename = $upload_dir ."/".$table_name.'.sql';		
		// if ($table_name != "transactions") {			
		// 	$json['command'] =  (Globals::MYSQLDUMP_PATH." -u\"".Globals::DB_USER_NAME."\" --password=\"".Globals::DB_PASSWORD."\" \"".Globals::DB_NAME."\" \"".$table_name."\" > ".$filename);
		// }
		
		echo json_encode($json);
	}


	private function updateSyncFromLocal($table_name, $id, $pump_id){
		$json = array();	

		$sql = "UPDATE `sync` SET `id` = '".$id."' WHERE `table_name` = '".$table_name."' AND `pump_id` = '".$pump_id."';";
		
		$this->_db->query($sql);
		$this->_db->execute();		
		$json['success'] =true;

		echo json_encode($json);
	}

	// private function updateSyncTable($table_name){
	// 	date_default_timezone_set("Asia/Kolkata");
	// 	$date = date("Y-m-d H:i:s");
	// 	$unix = strtotime($date);

	// 	//if exists update else insert
	// 	$sql = "SELECT 1 FROM `sync` WHERE `table_name` = '".$table_name."';";

	// 	$this->_db->query($sql);
	// 	$this->_db->execute();

	// 	if($this->_db->rowCount() == 0)
	// 	{
	// 		$sql = "INSERT INTO `sync`(`table_name`, `last_updated`) VALUES ('".$table_name."','".$unix."');";			
	// 	}else{
	// 		$sql = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = '".$table_name."';";
	// 	}
	// 	$this->_db->query($sql);
	// 	$this->_db->execute();
		
	// }
}

?>