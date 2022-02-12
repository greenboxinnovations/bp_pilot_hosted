<?php
date_default_timezone_set("Asia/Kolkata");

class Payments
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
					// by cust id
					// $this->getCustPayments($this->_getParams[0]);
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
			$this->newPayment($this->_postParams);
		}
	}

	private function newPayment($postParams){


		$cust_id	 		= $postParams['cust_id'];
		$cust_id	 		= trim($cust_id);

		$amount_paid		= $postParams['payment_amount'];
		$amount_paid		= trim($amount_paid);


		$payment_comment 	= $postParams['payment_comment'];
		$payment_comment 	= trim($payment_comment);

		if($payment_comment == ""){
			$payment_comment = NULL;
		}

		$time 				= date("H:i:s");

		$date 				= $postParams['payment_date']." ".$time;
		$last_updated		=  date("Y-m-d H:i:s");


		//new variables to be posted

		$invoice_no 		= $postParams['invoice_no'];
		$invoice_amount 	= $postParams['invoice_amount'];

		$new_out = 0;
		$new_bal = 0;

		$prev_out			= $invoice_amount;

		$sql = "SELECT `cust_pump_id`,`cust_post_paid`,`payment_balance`,`cust_ph_no` FROM `customers` WHERE `cust_id` = '".$cust_id."' ";
		$this->_db->query($sql);
		$this->_db->execute();

		if($this->_db->rowCount() == 1)
		{
			$r = $this->_db->single();
			$pump_id	 		= $r["cust_pump_id"];
			$is_postpaid	 	= $r["cust_post_paid"];
			$payment_balance	= $r["payment_balance"];
			$ph_no 				= $r['cust_ph_no'];
			$ph_no 				= str_replace("|", ",", $ph_no);
			
		}


		$sql = "SELECT `new_out` FROM `payments` WHERE `cust_id` = '".$cust_id."' AND `invoice_no` = '".$invoice_no."' ORDER BY `payment_id` DESC LIMIT 1";
		$this->_db->query($sql);
		$this->_db->execute();
		if($this->_db->rowCount() == 1)
		{
			$r = $this->_db->single();
			$prev_out	 		= $r["new_out"];			
		}

		$new_out	 = round($prev_out - $amount_paid);

		if ($payment_balance < 1) {
			$payment_balance = $new_out;
		}else{
			$payment_balance =  $payment_balance - $amount_paid;
		}

		$sql = "INSERT INTO `payments` (`cust_id`,`pump_id`,`prev_out`,`amount_paid`,`new_out`,`date`,`last_updated`,`is_postpaid`,`comment`,`invoice_no`,`invoice_amount`) 
				VALUES (:field1,:field2,:field4,:field5,:field7,:field8,:field9,:field10,:field11,:field12,:field13);";

		$this->_db->query($sql);

		$this->_db->bind(':field1', $cust_id);
		$this->_db->bind(':field2', $pump_id);
		$this->_db->bind(':field4', $prev_out);
		$this->_db->bind(':field5', $amount_paid);
		$this->_db->bind(':field7', $new_out);
		$this->_db->bind(':field8', $date);
		$this->_db->bind(':field9', $last_updated);
		$this->_db->bind(':field10', $is_postpaid);
		$this->_db->bind(':field11', $payment_comment);
		$this->_db->bind(':field12', $invoice_no);
		$this->_db->bind(':field13', $invoice_amount);

		$this->_db->execute();

		// update CUSTOMERS table from payment method

		 $sql = "UPDATE `customers` SET `payment_balance` = :field1, `cust_last_updated` = :field3 WHERE `cust_id` = '".$cust_id."' ;";

		 $this->_db->query($sql);

		 $this->_db->bind(':field1', $payment_balance);
		 $this->_db->bind(':field3', $last_updated);

		 $this->_db->execute();

		 //$table_name	  = "customers";
		// $id           = "cust_id";
		 //$unix = $last_updated;

		// Globals::updateSyncTable($table_name,$id,$unix);

		//if (Globals::SEND_MSG) {
		//	$this->sendMSG($ph_no,$invoice_no,$invoice_amount,$amount_paid,$payment_balance);
	//	}

	}

	private function sendMSG($ph_no,$invoice_no,$invoice_amount,$amount_paid,$payment_balance){

		$timestamp = date("d/m/Y H:i:s");
		echo "Formatted date from timestamp:" . $timestamp;

		$newline = "\n";

		$message = "Dear Customer".$newline."Your Payment of Rs ".$amount_paid." against Invoice no ".$invoice_no." of Invoice amount ".$invoice_amount." has been received.".$newline."Your total payable amount is ".$payment_balance.$newline.$timestamp;
		$encodedMessage = urlencode($message);

		$api = Globals::msgString($encodedMessage,$ph_no, false);

	    // Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $api,
		    //CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
	}
}
?>