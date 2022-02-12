<?php
date_default_timezone_set("Asia/Kolkata");

class ReceiptBook
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
					//$this->getAllCarsShifts(0,$today);					
					break;
				case 1:
					//$this->getAllCarsShifts(0,$today);
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
			if ($this->_getParams[0] == 'delete')
			{
				$this->deleteBook($this->_postParams);
			}else{
				$this->addBook($this->_postParams);
			}	
		}
	}

	private function addBook($postParams){

		$valid = false;

		date_default_timezone_set("Asia/Kolkata");
		$date = date("Y-m-d H:i:s");

		$min	 = $postParams['min'];
		$min	 = trim($min);

		$max	 = $postParams['max'];
		$max	 = trim($max);

		$cust_id	 = $postParams['cust_id'];
		$cust_id	 = trim($cust_id);		

		$pump_id	 = $postParams['pump_id'];
		$pump_id	 = trim($pump_id);		


		$sql = "SELECT `cust_id` FROM `receipt_books` WHERE `pump_id`='".$pump_id."' AND '".$min."' BETWEEN `min` and `max` OR `pump_id`='".$pump_id."' AND '".$max."' BETWEEN `min` and `max`;";
		$this->_db->query($sql);
		$this->_db->execute();

		if($this->_db->rowCount() == 0){
			
			$valid = true;
		}
		if ($max <= $min) {
			$valid = false;
		}

		if($valid){

			$sql = "INSERT INTO `receipt_books` (`min`,`max`,`cust_id`,`pump_id`,`date`) VALUES (:field1,:field2,:field3,:field4,:field5);";

			$this->_db->query($sql);

			$this->_db->bind(':field1', $min);
			$this->_db->bind(':field2', $max);
			$this->_db->bind(':field3', $cust_id);
			$this->_db->bind(':field4', $pump_id);
			$this->_db->bind(':field5', $date);

			$this->_db->execute();

			echo "New Receipt Book Added!";
		}else{
			echo "Invalid values Or duplicate reciept book";
		}
	}

	private function deleteBook($postParams){

		date_default_timezone_set("Asia/Kolkata");
		$date = date("Y-m-d H:i:s");

		$id	 = $postParams['id'];
		$id	 = trim($id);		

		$sql = "SELECT 1 FROM `receipt_books` WHERE `rb_id` = '".$id."'";
		$this->_db->query($sql);
		$this->_db->execute();

		if($this->_db->rowCount() == 1){
			
			$sql = "DELETE FROM `receipt_books` WHERE `rb_id` = '".$id."'";
			$this->_db->query($sql);
			$this->_db->execute();
		}	
	}
}
?>