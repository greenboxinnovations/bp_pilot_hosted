<?php
if(!isset($_SESSION))
{
	session_start();
}
$user_id = $_SESSION['user_id'];
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';


// $date1 = date("Y-m-01",strtotime("this month"));
// $date2 = date('Y-m-t',strtotime('this month'));

echo'<div>';

	echo'<div>Operator Name <input type="text" id="new_cashier" style="margin-left:10px;"></input><button id="save_cashier" style="margin-left:10px;">SAVE</button></div>';


	echo'<div style="display:inline-block;margin-right:10px;">Transaction Date<br>';
		// echo' <input type="date" id="date" value="'.date("Y-m-d").'"></input>';
		echo' <input type="text" id="date" value="'.date("d-m-Y").'" placeholder="Transaction Date"></input>';
	echo'</div>';

	// echo'<div style="display:inline-block;margin-right:10px;">To<br>';
	// 	echo'<input type="date" id="date2" value="'.$date2.'"></input>';
	// echo'</div>';

	echo'<div style="display:inline-block;margin-right:10px;"><br>';
		echo'<input type="number" id="petrol_rate" value="" class="single_decimal_twodigit" placeholder="Petrol Rate"></input>';
	echo'</div>';

	echo'<div style="display:inline-block;margin-right:10px;"><br>';
		echo'<input type="number" id="diesel_rate" value="" class="single_decimal_twodigit" placeholder="Diesel Rate"></input>';
	echo'</div>';


	$sql = "SELECT `name`,`user_id` FROM `users` WHERE role = 'operator' AND `user_pump_id` IN (SELECT `user_pump_id` FROM `users` WHERE user_id = '".$user_id."') ";
	$exe = mysqli_query($conn, $sql);

	echo '<select id="user_id" style="margin-bottom:6px;">';
		echo '<option value="-1" selected disabled>Select User</option>';

		while($row = mysqli_fetch_assoc($exe)){
			$user_id	 = $row["user_id"];
			$name	 	 = $row["name"];
			echo '<option value="'.$user_id.'">'.ucfirst($name).'</option>';
		}
	echo '</select>';

	echo '<br/>';

	echo'<div style="display:inline-block;margin-right:10px;">';

		echo '<select id="shift" style="margin-bottom:6px;">';
			echo '<option value="1" selected>First shift</option>';
			echo '<option value="2">Second shift</option>';
		echo '</select>';

		
		echo'<input type="number" id="rbook_input" tabindex="5" placeholder="Receipt Number" style="margin-left:10px;">';


	echo'</div>';

	echo '<div id="m_trans_result"></div>';

echo'</div>';



?>