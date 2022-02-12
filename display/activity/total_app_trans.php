<?php
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

$pump_id = $_SESSION['pump_id'];


$sql = "SELECT b.cust_company,b.cust_disp_name, count(a.cust_id) as total, a.cust_id 	FROM `transactions` a
		JOIN `customers` b
		ON a.cust_id = b.cust_id
		WHERE `trans_string` != '' AND `pump_id` = '".$pump_id."' GROUP BY a.cust_id";
$exe = mysqli_query($conn, $sql);

if(mysqli_num_rows($exe) > 0){
	
	echo '<table>';

	echo '<tr>';
		echo '<th style="text-align:left;">Customer</th>';
		echo '<th class="right_num">App Transactions</th>';
	echo '</tr>';
	while($row = mysqli_fetch_assoc($exe)){
		$company 	= $row['cust_company'];
		$cust_disp_name 	= $row['cust_disp_name'];
		$total 		= $row['total'];
		$cust_id 	= $row['cust_id'];

		if($company == ""){
			$company = $cust_disp_name;
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