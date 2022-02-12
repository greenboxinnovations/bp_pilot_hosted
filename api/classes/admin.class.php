<?php
date_default_timezone_set("Asia/Kolkata");

class Admin
{
	private $_db;
	private $_method;
	private $_getParams = null;
	private $_postParams = null;

	public function __construct($db,$method,$getParams,$postParams){

		if (version_compare(phpversion(), '7.1', '>=')) {
			ini_set( 'serialize_precision', -1 );
		}


		$this->_db = $db->getInstance();
		$this->_method = $method;
		$this->_getParams = $getParams;
		$size = sizeof($this->_getParams);
		$this->_postParams = $postParams;

		if($this->_method=='GET')
		{
			
			switch ($size) {
				case 0:
					// $this->getUsers();
					// echo 'working admin';
				break;
				case 1:
				if($this->_getParams[0] == "transactions"){
					$this->getTransactions(date('Y-m-d'));
				}

				if($this->_getParams[0] == "local_server"){
					$this->getLocalServer();
				}

				// show missing and server uptime
				if($this->_getParams[0] == "missing"){
					$this->getMissing();							
					// echo $_SERVER['DOCUMENT_ROOT'];
					// echo '<br>';
					// $mypath = $_SERVER['DOCUMENT_ROOT']."/videos/2019-11-14/EdkcZmz3yo.mp4";

					// if(!file_exists($mypath)){
					// 	echo 'doesnt exist';
					// }
					// else{
					// 	echo 'exists';
					// }
				}

					// echo '<pre>';
					// print_r($this->_getParams[0]);
					// echo '</pre>';


				break;
				case 2:
					//$this->getAllCarsShifts(0,$today);
				if($this->_getParams[0] == "transactions"){
					$date = $this->_getParams[1];
					if($this->validateDate($date)){
						$this->getTransactions($date);
					}
					
							// echo 'shjow trancs';
				}
				break;
				default:
					// $output = array();
					// $output['error'] = 'no date provided';
					// echo json_encode($output);
				break;
			}
		}
		elseif ($this->_method == 'POST')
		{
		}
	}

	// private function getUsers($pump_id){
	// 	$sql = "SELECT * FROM `users` WHERE `user_pump_id` = '".$pump_id."' AND `role` = 'operator' ;";
	// 	$this->_db->query($sql);
	// 	$this->_db->execute();

	// 	$output=array();
	// 	$json=array();

	// 	if($this->_db->rowCount() > 0)
	// 	{
	// 		$r = $this->_db->resultset();
	// 		foreach ($r as $row) {

	// 			$json["user_id"]		= $row["user_id"];
	// 			$json["name"]	 		= $row["name"];
	// 			$json["user_pump_id"]	= $row["user_pump_id"];
	// 			array_push($output, $json);
	// 		}
	// 	}
	// 	echo json_encode($output,JSON_NUMERIC_CHECK);
	// }

	private function getTransactions($date){
		// echo $date;

		$sql = "SELECT b.cust_company,b.cust_disp_name,b.cust_post_paid, a.cust_id,a.*,c.car_no_plate 
		FROM `transactions` a 
		JOIN `customers` b ON a.cust_id = b.cust_id 
		JOIN `cars` c ON c.car_id = a.car_id 
		WHERE date(a.date) = :field1 ORDER BY b.cust_company ASC,a.trans_string DESC;";

		$this->_db->query($sql);
		$this->_db->bind(':field1', $date);
		$this->_db->execute();


		$output=array();
		if($this->_db->rowCount() > 0)
		{
			$r = $this->_db->resultset();
			foreach ($r as $row) {
				$json=array();
				$json["cust_id"]	 	= $row["cust_id"];
				$json["cust_disp_name"]	= $row['cust_disp_name'];
				$json["liters"]	 		= $row["liters"];
				$json["rate"]	 		= $row["rate"];
				$json["amount"]	 		= $row["amount"];
				$json["timestamp"] 		= $row["date"];	
				$json["date"] 			= date('Y-m-d',strtotime($row["date"]));	
				$json["car_no_plate"] 	= strtoupper($row["car_no_plate"]);
				$json["trans_time"] 	= $row["trans_time"];
				$json["trans_string"]	= $row["trans_string"];	
				array_push($output, $json);				
			}
			
		}
		echo json_encode($output,JSON_NUMERIC_CHECK);
	}



	private function getMissing(){


		$start_date = date("Y-m-d", strtotime("-1 month"));
		$end_date 	= date('Y-m-d');

		$sql = "SELECT `trans_string`, date(`date`) as 'date' FROM `transactions` WHERE date(`date`) BETWEEN '$start_date' AND '$end_date' AND `trans_string` IS NOT NULL ORDER BY `trans_id` DESC;";

		// $sql = "SELECT `trans_string`, date(`date`) as 'date' FROM `transactions` WHERE date(`date`) ='$end_date' AND `trans_string` IS NOT NULL ORDER BY `trans_id` DESC;";

		$this->_db->query($sql);
		$this->_db->execute();

		// transaction videos
		$video_dir = $_SERVER['DOCUMENT_ROOT'].'/videos';

		// $video_dir = '../videos';
		// echo getcwd();
		// echo $_SERVER['DOCUMENT_ROOT'];

		$output=array();
		if($this->_db->rowCount() > 0)
		{
			$r = $this->_db->resultset();
			foreach ($r as $row) {

				$trans_string 	= $row['trans_string'];
				$date 			= $row['date'];

				$vid_path = $video_dir."/".$date."/".$trans_string.".mp4";

				// transaction videos		
				if(!file_exists($vid_path)){								
					$new = array('date' => $date, 't_string' => $trans_string,'items' => array('V'));
					array_push($output, $new);
				}


				// transaction photos
				// $upload_dir = 'uploads';
				$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/uploads';

				$check 			= ['_start.jpeg','_start_top.jpeg','_stop.jpeg','_stop_top.jpeg'];
				$description 	= ['Zero Photo','Zero Overhead Photo','Completion Photo','Completed Overhead Photo'];

				foreach ($check as $i => $extention) {

					$file_path = $upload_dir."/".$date."/".$trans_string.$extention;

					if(!file_exists($file_path)) {
						// check if exists
						$key = array_search($trans_string, array_column($output, 't_string'));

						if(!is_bool($key)){
						// found
						// print_r($main_array[$key]);
							array_push($output[$key]["items"], $extention);
						}
						else{
							$new = array('date' => $date, 't_string' => $trans_string,'items' => array($extention));
							array_push($output, $new);
						}
					}

				}			
			}
			
		}

		// spagheti code need to be refactored		
		for ($i=0; $i < sizeof($output); $i++) { 
			$temp = "";
			foreach ($output[$i]['items'] as $value) {
				$temp.= $value.' ';
			}
			
			
			$temp = str_replace("_start.jpeg","1",$temp);
			$temp = str_replace("_start_top.jpeg","2",$temp);
			$temp = str_replace("_stop.jpeg","3",$temp);
			$temp = str_replace("_stop_top.jpeg","4",$temp);			
			$output[$i]['items'] = trim($temp);
		}
		

		echo json_encode($output,JSON_NUMERIC_CHECK);
	}


	private function getLocalServer(){
		// echo $date;

		$sql = "SELECT `last_updated` FROM `sync` WHERE `table_name` = 'local_server';";

		$this->_db->query($sql);		
		$this->_db->execute();


		$output=array();
		if($this->_db->rowCount() > 0)
		{
			$r = $this->_db->single();
			$last_updated = $r["last_updated"];
			

			$unix = strtotime(date("Y-m-d H:i:s"));

			$output["server_timestamp"]	= date("Y-m-d H:i:s",$last_updated);
			$output["local_server"]	 	= date("H:i:s",$last_updated);
			$output["last_sync"]	 	= ($unix - $last_updated);
		}
		echo json_encode($output,JSON_NUMERIC_CHECK);
	}



	private function validateDate($date, $format = 'Y-m-d') {
		$d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $date;
	}
}
?>