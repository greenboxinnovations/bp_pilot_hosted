 <?php
if(!isset($_SESSION)) {
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
$pump_id = $_SESSION['pump_id'];

function sortPostPre(&$post_paid_arr, &$pre_paid_arr){
	global $conn;
	global $pump_id;
	// sort by cust_post_paid
	$sql = "SELECT * FROM `customers` WHERE `cust_pump_id` = '".$pump_id."' ORDER BY `cust_post_paid` ASC, `cust_id` ASC ";
	$exe = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($exe);

	if ($count > 0) {	
		while($row = mysqli_fetch_assoc($exe)){
			if($row["cust_post_paid"] == "Y"){
				array_push($post_paid_arr, $row);
			}
			else{
				array_push($pre_paid_arr, $row);
			}
		}
	}	
}


function sortDisplayName(&$array){
	foreach ($array as $key => $value) {
		$display_name = "";
		if($value['cust_company'] == ""){
			$display_name = $value['cust_f_name']." ".$value['cust_l_name'];
		}
		else{
			$display_name = $value['cust_company'];
		}		
		$array[$key]['display_name'] = $display_name;		
	}	
}

function sortByName($a, $b) {
	return strcmp(strtolower($a["display_name"]), strtolower($b["display_name"]));
}

function renderTable($array,$postpaid) {
	$i = 1;
	echo '<table id="header-fixed"></table>';
	echo '<table id="table-2">';

	echo '<thead>';
		echo '<tr style="border:1px solid rgb(207,216,220);">';
			echo '<th>#</th>';
			echo '<th>Customer Name</th>';
			
			if ($postpaid) {
				echo '<th class="right_num">Outstanding</th>';
			}else{
				echo '<th class="right_num">Balance</th>';
			}
			echo '<th>Previous Balance</th>';

			echo '<th>Last Updated</th>';
			// if ($_SESSION['role'] == "admin") {
					echo '<th ></th>';
			// }
		echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	foreach ($array as $key => $row) {
		
	

		$limit = false;
		$upper_limit = 0;
		
		$cust_id	 	= $row["cust_id"];
		$cust_dis_name	= ucwords($row["display_name"]);
		$cust_ph_no	 	= $row["cust_ph_no"];		


		$cust_post_paid	= $row["cust_post_paid"];
		
		$cust_balance	= $row["cust_balance"];
		$cust_credit_limit	 = $row["cust_credit_limit"];
		$cust_outstanding	 = $row["cust_outstanding"];
		$cust_last_updated	 = $row["cust_last_updated"];
		$cust_payment_balance	 = $row["payment_balance"];
		$cust_last_updated = date("M-d, g:i a", strtotime($cust_last_updated));

		if ($cust_credit_limit == 0) {
			$cust_credit_limit = 1;
		}		

		if ($cust_post_paid == "Y") {
			$cust_post_paid = "Postpaid";
			$upper_limit = round(($cust_outstanding/$cust_credit_limit)*100);
			$payment = 1;
		}
		else{
			$payment = 0;
			$cust_post_paid = "Prepaid";

			if ($cust_balance < 10000) {
				$upper_limit = 90;
			}
		}

		if($upper_limit > 80){
			$limit = true;
		}


		if($limit){
			echo '<tr custid="'.$cust_id.'" class="highlight red">';
		}else{
			echo '<tr custid="'.$cust_id.'" class="highlight">';
		}

				echo '<td style=" min-width: 20px;">'.$i.'</td>';
				echo '<td style=" min-width: 300px;">'.$cust_dis_name.'</td>';

				if ($payment == 0) {
					echo '<td class="right_num">'.$cust_balance.'</td>';
				}else{
					echo '<td class="right_num">'.$cust_outstanding.'</td>';
				}

				echo '<td>'.$cust_payment_balance.'</td>';
				
				echo '<td>'.$cust_last_updated.'</td>';
				// if ($_SESSION['role'] == "admin") {
					echo '<td custid='.$cust_id.' class="edit" >Edit</td>';
				// }
				
		echo '</tr>';		
		$i++;
	}
	// echo '<pre>';
	// foreach ($array as $key => $row) {
	// 	print_r($row);
	// }
	// echo '</pre>';

	echo '</tbody>';
	echo '</table>';
}


// postpaid array
$post_paid_arr = array();
// prepaid array
$pre_paid_arr = array();

// sort array
sortPostPre($post_paid_arr, $pre_paid_arr);

// Notify if no customers
if((sizeof($post_paid_arr) == 0) && (sizeof($pre_paid_arr) == 0)){
	echo '<h3>No Customers Added</h3>';
}
// customers exist
else{

	// prepaid first
	sortDisplayName($pre_paid_arr);
	usort($pre_paid_arr, "sortByName");	
	
	if(sizeof($pre_paid_arr) > 0) {

		echo "<h3>Prepaid Accounts</h3>";
		echo "<br/>";

		renderTable($pre_paid_arr,false);
	}
	else{
		echo "<h3>No Prepaid Accounts Added</h3>";
		echo "<br/>";
	}


	// postpaid first
	sortDisplayName($post_paid_arr);
	usort($post_paid_arr, "sortByName");	
	
	if(sizeof($post_paid_arr) > 0) {

		echo "<br/>";
		echo "<h3>Postpaid Accounts</h3>";
		echo "<br/>";

		renderTable($post_paid_arr,true);
	}
	else{
		echo "<h3>No Postpaid Accounts Added</h3>";
		echo "<br/>";
	}



}


?>
