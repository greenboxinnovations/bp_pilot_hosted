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

	$sql = "SELECT b.cust_company,b.cust_f_name,b.cust_l_name, count(a.cust_id) as total, a.cust_id 	FROM `transactions` a
		JOIN `customers` b
		ON a.cust_id = b.cust_id
		WHERE date(a.date) = '".$date."' AND `trans_string` != '' AND `pump_id` = '".$pump_id."' GROUP BY a.cust_id";
}
else if(($date1!="")&&($date2 != "")){
	// find which one is greater
	if(strtotime($date1) > strtotime($date2)){		
		$c = $date1;		
		$date2 = $date1;		
		$date1 = $c;
	}

	$sql = "SELECT b.cust_company,b.cust_f_name,b.cust_l_name, count(a.cust_id) as total, a.cust_id 	FROM `transactions` a
		JOIN `customers` b
		ON a.cust_id = b.cust_id
		WHERE date(a.date) BETWEEN '".$date1."' AND '".$date2."' AND `trans_string` != '' AND `pump_id` = '".$pump_id."' GROUP BY a.cust_id";

}



$exe = mysqli_query($conn, $sql);

if(mysqli_num_rows($exe) > 0){
	
	echo '<table>';

	echo '<tr>';
		echo '<th style="text-align:left;">Customer</th>';
		echo '<th class="right_num">App Transactions</th>';
	echo '</tr>';
	while($row = mysqli_fetch_assoc($exe)){
		$company 	= $row['cust_company'];
		$f_name 	= $row['cust_f_name'];
		$l_name 	= $row['cust_l_name'];
		$total 		= $row['total'];
		$cust_id 	= $row['cust_id'];

		if($company == ""){
			$company = $cust_f_name." ".$cust_l_name;
		}

		echo '<tr>';
			echo '<td>'.ucwords($company).'</td>';
			echo '<td class="right_num">'.$total.'</td>';
		echo '</tr>';
	}
	echo '</table>';	
}
else{
	echo '<div>No App Transactions Found!</div>';
}


?>