<?php

require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

function getCustId($rbook_num, &$conn){

	$ret = array();

	//$sql = "SELECT 1 FROM `transactions` WHERE `receipt_no` =  ".$rbook_num." ";

	// duplicate check
	$sql = "SELECT a.cust_id,a.amount,date(a.date) as tdate,b.cust_disp_name FROM transactions  a
			JOIN customers b
			ON a.cust_id = b.cust_id
			WHERE a.receipt_no = '".$rbook_num."';";

	$exe = mysqli_query($conn, $sql);
	
	if(mysqli_num_rows($exe) < 1){
		// rbook num not found in transactions
		// might exist in rbooks, because unused
		// range check
		$sql = "SELECT a.cust_id, b.cust_disp_name, b.cust_post_paid FROM `receipt_books` a 
				JOIN `customers` b 
				ON a.cust_id = b.cust_id 
				WHERE ".$rbook_num." BETWEEN a.min and a.max";
		$exe = mysqli_query($conn, $sql);

		if(mysqli_num_rows($exe) == 1){
			// found, unused
			// return cust id
			$row = mysqli_fetch_assoc($exe);
			$ret['cust_id'] = $row["cust_id"];	
			$ret['cust_disp_name'] = $row['cust_disp_name'];
			$ret['cust_post_paid'] = $row['cust_post_paid'];		
		}
		else{
			// rnum does not exist
			$ret['cust_id'] = -1;
		}
	}
	else{
		$row = mysqli_fetch_assoc($exe);
		// duplicate receipt number
		$ret['cust_id'] = -2;
		$ret['amount'] = $row['amount'];
		$ret['tdate'] = $row['tdate'];
		$ret['cust_disp_name'] = $row['cust_disp_name'];
	}
	//print_r($ret);
	return $ret;
}




if(isset($_GET['rbook_num'])){

	$rbook_num = $_GET['rbook_num'];

	// get cust id from rbook range
	$ret = getCustId($rbook_num, $conn);	
	
	
	// receipt number not found
	if($ret['cust_id'] == -1){
		// no id found
		echo 'nothing found';
	}
	// rnum found but duplicate
	else if($ret['cust_id'] == -2){
		echo 'Duplicate :'.$ret['cust_disp_name'].' Amount: '.$ret['amount'].' Date: '.$ret['tdate'];
	}
	// rnum valid and unused
	else{

		$full_name = ucwords($ret['cust_disp_name']);

		

		// print name
		echo '<div style="margin-top:20px;margin-bottom:15px;font-size:20px;">'.$full_name.'</div>';


		// get customer cars
		$sql = "SELECT car_id, car_no_plate,car_fuel_type FROM `cars` WHERE `car_cust_id` = '".$ret['cust_id']."' AND `status` = 'active';";
		$exe = mysqli_query($conn, $sql);
		echo '<select id="sel_car" style="margin-bottom:6px;">';
		echo '<option value="-1" ftype="invalid_fuel">Choose Car</option>';
		echo '<option value="-1" ftype="unknown">Unknown</option>';
		while($row = mysqli_fetch_assoc($exe)){
			$car_id	 		= $row["car_id"];
			$car_no_plate	= $row["car_no_plate"];
			$car_fuel_type	= $row["car_fuel_type"];
			echo '<option value="'.$car_id.'" ftype="'.$car_fuel_type.'">'.$car_no_plate.'</option>';
		}
		
		echo '</select>';

		// NO VALIDATION
		echo '<div id="form_unknown_car">
			<div>
				<input type="text" placeholder="Car Number" onKeyPress="if(this.value.length==112 return false;" id="in_car_no_plate_number">
			</div>
			<div >
				<input type="radio" name="unknown_fuel" value="petrol" checked>Petrol
  				<input type="radio" name="unknown_fuel" value="diesel">Diesel
			</div>
		</div>';

		/* WITH VALIDATION
		echo '<div id="form_unknown_car">
			<div>
				<input type="text" value="MH" maxlength="2" id="in_car_no_plate_state" class="only_letter" tabindex="7">
				<input type="number" value="12" id="in_car_no_plate_city" onKeyPress="if(this.value.length==2) return false;" tabindex="8">
				<input type="text" maxlength="3" placeholder="**" class="only_letter" id="in_car_no_plate_letter" tabindex="9">
				<input type="number" placeholder="****" onKeyPress="if(this.value.length==4) return false;" id="in_car_no_plate_number" tabindex="10">
			</div>
			<div >
				<input type="radio" name="unknown_fuel" value="petrol" checked>Petrol
  				<input type="radio" name="unknown_fuel" value="diesel">Diesel
			</div>
		</div>';
		*/

 		echo '<div><input type="hidden" id="cust_post_paid" custid="'.$ret['cust_id'].'" value="'.$ret['cust_post_paid'].'"></div>';

		// show input boxes
		echo '<div><input type="number" id="m_trans_rs" placeholder="Rupees" class="single_decimal_twodigit" tabindex="11"></div>';
		echo '<div><input type="number" id="m_trans_lit" placeholder="Litres" class="single_decimal_twodigit" tabindex="12"></div>';
		echo '<div style="margin-top:20px;"><div class="mat_btn" id="btn_manual_t_cancel">CANCEL</div><div class="mat_btn" style="background-color: #0087C1;" id="btn_manual_t_confirm" tabindex="9">CONFIRM</div></div>';
	}
	
	
			
}


?>