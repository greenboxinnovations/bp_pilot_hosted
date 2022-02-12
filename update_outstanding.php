<?php

date_default_timezone_set("Asia/Kolkata");

if (isset($_GET['ok'])) {
	require_once $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

	$pump_ids = array();

	$sql = "SELECT `cust_id`,`cust_pump_id` FROM `customers` WHERE `cust_post_paid` = 'Y';";
	$exe = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($exe)){

			echo $cust_id = $row['cust_id'];
			$pump_id = $row['cust_pump_id'];

			if (!in_array($pump_id, $pump_ids)) {
				array_push($pump_ids, $pump_id);
			}

			echo '<br/>';

			$sql1 = "SELECT SUM(`amount`) as amount FROM `transactions` WHERE `cust_id` = ".$cust_id." AND `billed` = 'N';";
			$exe1 = mysqli_query($conn, $sql1);
			$row1 = mysqli_fetch_assoc($exe1);

			$amount = $row1['amount'];


			$sql2 = "UPDATE `customers` SET `cust_outstanding` = '".$amount."' WHERE `cust_id` = ".$row['cust_id']."  ;";
			$exe2 = mysqli_query($conn, $sql2);


	}

	$sql = "SELECT `cust_id`,`cust_pump_id` FROM `customers` WHERE `cust_post_paid` = 'N';";
	$exe = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($exe)){

			echo $cust_id = $row['cust_id'];
			$pump_id = $row['cust_pump_id'];

			if (!in_array($pump_id, $pump_ids)) {
				array_push($pump_ids, $pump_id);
			}

			echo '<br/>';

			$sql1 = "SELECT SUM(`amount`) as amount FROM `transactions` WHERE `cust_id` = ".$cust_id." AND `billed` = 'N';";
			$exe1 = mysqli_query($conn, $sql1);
			$row1 = mysqli_fetch_assoc($exe1);

			$amount = $row1['amount'];
			//test

			$sql2 = "UPDATE `customers` SET `cust_balance` = 100000-'".$amount."' WHERE `cust_id` = ".$row['cust_id']."  ;";
			$exe2 = mysqli_query($conn, $sql2);

	}


	foreach ($pump_ids as $value) {
		$date = date("Y-m-d H:i:s");		
		$table_name	  = "customers";
		$last_updated = strtotime($date);
		$id           = "cust_id";
		$unix = $last_updated;
				
		Globals::updateSyncTable($table_name,$id,$unix,$value);
	}


	
}
?>