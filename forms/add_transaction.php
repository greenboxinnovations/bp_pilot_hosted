<?php
if(!isset($_SESSION))
{
	session_start();
}
if(isset($_GET['cust_id'])){
	require_once $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
	$cust_id = $_GET['cust_id'];
	date_default_timezone_set("Asia/Kolkata");
	$e_id = $_SESSION['user_id'];

	$car_id_o    = null;
	$fuel_type	 = null;
	$date 		 = date("Y-m-d");
	$rate 		 = null;
	$rs 		 = null;
	$lit 		 = null;
	$receipt_no  = null;
	$trans_id    = null;
	$user_id_o   = null;

	if (isset($_GET['trans_id'])) {
		$trans_id = $_GET['trans_id'];
		$sql = "SELECT * FROM `transactions` WHERE `trans_id` ='".$trans_id."' ";
		$exe = mysqli_query($conn, $sql);

		while($row = mysqli_fetch_assoc($exe)){
				// $car_pump_id	 = $row["car_pump_id"];
				$car_id_o 	 = $row["car_id"];
				$receipt_no	 = $row["receipt_no"];
				$fuel_type	 = $row["fuel"];
				$rs	 		 = $row["amount"];
				$rate  		 = $row["rate"];
				$lit  		 = $row["liters"];
				$date  		 = $row["date"];
				$user_id_o 	 = $row['user_id'];				
		}		
	}
 

	echo '<div style="background-color: rgb(249,250,251);width: 500px;padding: 20px;border: 1px solid rgb(222,227,231);border-radius: 3px;">';

	$sql = "SELECT * FROM `cars` WHERE `car_cust_id` ='".$cust_id."' AND `status` = 'active' ";
	$exe = mysqli_query($conn, $sql);

	$row_count = mysqli_num_rows($exe);

	if($row_count > 0){
		echo '<div class="form_header">NEW TRANSACTION</div>';

		echo '<div style="margin-top:5px;"><input type="text" id="trans_date" value="'.date('d-m-Y', strtotime($date)).'"></div>';
		echo '<div style="margin-top: 20px;"></div>';

		echo '<select id="shift" style="margin-bottom:6px;">';
			echo '<option value="1" selected>First shift</option>';
			echo '<option value="2">Second shift</option>';
		echo '</select>';

		echo'<br/>';


		echo '<select id="select_car" >';
			echo '<option value="-1" disabled selected>Select Car</option>';
			while($row = mysqli_fetch_assoc($exe)){
				$car_id	 = $row["car_id"];
				// $car_pump_id	 = $row["car_pump_id"];
				$car_brand	 	 = $row["car_brand"];
				$car_sub_brand	 = ucwords($row["car_sub_brand"]);
				$car_no_plate	 = strtoupper($row["car_no_plate"]);
				$car_fuel_type	 = $row["car_fuel_type"];
				// $car_cust_id	 = $row["car_cust_id"];
				$car_qr_code	 = $row["car_qr_code"];

				if ($car_id_o == $car_id) {
					echo '<option value="'.$car_id.'" selected  ftype="'.$car_fuel_type.'">'.$car_sub_brand.' - '.$car_no_plate.'</option>';
				}else{

					echo '<option value="'.$car_id.'" ftype="'.$car_fuel_type.'">'.$car_sub_brand.' - '.$car_no_plate.'</option>';
				}

			}
		echo '</select>';

		// echo '<div style="margin-top:5px;"><input type="date" id="trans_date" value="'.date('Y-m-d', strtotime($date)).'"></div>';
	
		

		echo '<div><input type="number" id="rate" placeholder="Rate" class="single_decimal trans_key" value="'.$rate.'"></div>';
		echo '<div style="margin-top:20px;"><input type="number" id="rs" placeholder="Rupees" class="single_decimal trans_key" value="'.$rs.'"></div>';
		echo '<div><input type="number" id="lit" placeholder="Litres" class="single_decimal trans_key" value="'.$lit.'"></div>';

		echo '<div style="margin-top: 20px;"></div>	';
		echo '<div><div class="mat_btn" id="btn_clear_all">CLEAR ALL</div></div>';


		$sql = "SELECT `name`,`user_id` FROM `users` WHERE role = 'operator' AND `user_pump_id` IN (SELECT `user_pump_id` FROM `users` WHERE user_id = '".$e_id."') ";
		$exe = mysqli_query($conn, $sql);


		echo '<select id="user_id" style="margin-bottom:6px;">';
			echo '<option value="-1" selected disabled>Select User</option>';

			while($row = mysqli_fetch_assoc($exe)){
				$user_id	 = $row["user_id"];
				$name	 	 = $row["name"];

				if ($user_id_o == $user_id) {
					echo '<option value="'.$user_id.'" selected>'.ucfirst($name).'</option>';
				}else{
					echo '<option value="'.$user_id.'">'.ucfirst($name).'</option>';
				}
				
			}
		echo '</select>';

		echo '<div style="margin-top:20px;font-style:italic;font-size:14px;">Optional</div>';
		if ($receipt_no != null) {
			echo '<div><input type="number" id="rbook_input" placeholder="Receipt Number" class="single_decimal" value="'.$receipt_no.'" disabled></div>';
		}else{
			echo '<div><input type="number" id="rbook_input" placeholder="Receipt Number" class="single_decimal" value="'.$receipt_no.'"></div>';
		}
		

		echo '<div style="margin-top: 30px;"></div>	';

		if ($trans_id != null) {
			echo '<div><div class="mat_btn" id="btn_cancel_transaction">CANCEL</div><div class="mat_btn"  style="background-color: #0087C1;" id="btn_new_transaction" type="update" transid="'.$trans_id.'" >UPDATE</div></div>';	
		}else{
			echo '<div><div class="mat_btn" id="btn_cancel_transaction">CANCEL</div><div class="mat_btn"  style="background-color: #0087C1;" id="btn_new_transaction" type="new" transid="'.$trans_id.'">ADD</div></div>';	
		}

		
	echo '</div>'; // wrapper

	}
	else{
		echo 'No Cars Found';
		echo '</div>'; // wrapper
	}


}

?>



	<!-- in_car_cust_id -->


	


<!-- 	<div>
		<select id="select_car">
			<option value="-1">Select Car</option>
			<?php
			
				
			?>
			<option value="999">Other</option>
		</select>
	</div> -->
	


