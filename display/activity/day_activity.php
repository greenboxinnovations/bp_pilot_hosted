<?php
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
date_default_timezone_set("Asia/Kolkata");

$pump_id = $_SESSION['pump_id'];

if(isset($_GET['date1'])){

	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];
}
else{
	$date1 = date('Y-m-d');
	$date2 = "";
}


// check if one date is blank
if(($date1=="")||($date2 == "")){
	// find which one is blank
	if($date1 == ""){
		$date = $date2;
	}
	else{
		$date = $date1;
	}


	$sql = "SELECT b.cust_company,b.cust_f_name,b.cust_l_name,b.cust_post_paid, a.cust_id,a.*,c.car_no_plate 
			FROM `transactions` a 
			JOIN `customers` b ON a.cust_id = b.cust_id 
			JOIN `cars` c ON c.car_id = a.car_id 
			WHERE date(a.date) = '".$date."' AND `pump_id` = '".$pump_id."' ORDER BY b.cust_company ASC,a.trans_string DESC;";
}
else if(($date1!="")&&($date2 != "")){
	// find which one is greater
	if(strtotime($date1) > strtotime($date2)){		
		$c = $date1;		
		$date2 = $date1;		
		$date1 = $c;
	}

	$sql = "SELECT b.cust_company,b.cust_f_name,b.cust_l_name,b.cust_post_paid, a.cust_id,a.*,c.car_no_plate 
			FROM `transactions` a 
			JOIN `customers` b ON a.cust_id = b.cust_id 
			JOIN `cars` c ON c.car_id = a.car_id 
			WHERE date(a.date) BETWEEN '".$date1."' AND '".$date2."' AND `pump_id` = '".$pump_id."'	ORDER BY b.cust_company ASC,a.trans_string DESC";
}

$exe = mysqli_query($conn, $sql);

if(mysqli_num_rows($exe) > 0){
	
	echo '<table>';

	// echo '<tr>';
	// 	echo '<th style="text-align:left;">Customer</th>';
	// 	echo '<th class="right_num">App Transactions</th>';
	// echo '</tr>';

	$company = null;

	while($row = mysqli_fetch_assoc($exe)){
		
		$cust_f_name 	= $row['cust_f_name'];
		$cust_l_name 	= $row['cust_l_name'];
		$cust_id 		= $row['cust_id'];

		$trans_id	 	= $row["trans_id"];	
		$trans_id_disp  = $trans_id + 100000;
		$cust_id	 	= $row["cust_id"];
		$car_id	 		= $row["car_id"];
		$liters	 		= $row["liters"];
		$rate	 		= $row["rate"];
		$amount	 		= $row["amount"];
		$date	 		= $row["date"];	
		$cust_post_paid = $row["cust_post_paid"];
		$status 		= $row["billed"];
		$car_no_plate 	= $row["car_no_plate"];
		$trans_time 	= $row["trans_time"];

		$trans_string	= $row["trans_string"];	

		if($company == ""){
			$company = $cust_f_name." ".$cust_l_name;
		}


		if(($company == null) || ($company != $row['cust_company'])){
			$company 	= $row['cust_company'];
			echo '<tr>';
				echo '<td colspan="3">'.ucwords($company).'</td>';
				echo '<td><button class="show_more" custid="'.$cust_id.'">More</button></td>';
			echo '</tr>';
		}
		

		if($trans_string != null){
			echo '<tr class="hide highlight '.$cust_id.'" transstring="'.$trans_string.'">';
		}else{
			echo '<tr class="hide '.$cust_id.'">';
		}
			echo '<td>'.$car_no_plate.'</td>';			
			echo '<td class="right_num">'.$amount.'</td>';			
			if($trans_time == null){
				echo '<td></td>';	
			}
			else{
				echo '<td>'.date('i:s',strtotime($trans_time)).'</td>';		
			}			
			echo '<td>'.date("M d",strtotime($date)).'</td>';	
		echo '</tr>';


		// Car No	Amount	Date	Duration
	}
	echo '</table>';	
}
else{
	echo '<div>No App Transactions Found!</div>';
}

?>