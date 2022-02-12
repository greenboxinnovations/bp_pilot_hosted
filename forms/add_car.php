<div style="background-color: rgb(249,250,251);width: 500px;padding: 20px;border: 1px solid rgb(222,227,231);border-radius: 3px;">
	<!-- in_car_cust_id -->


	<div class="form_header">NEW CAR</div>


	<div>
		<select id="select_fuel_type">
			<option value="-1">Fuel Type</option>
			<option value="petrol">Petrol</option>
			<option value="diesel">Diesel</option>
		</select>
	</div>

	<div style="margin-top: 20px;"></div>
	<!-- <div><input type="text" id="in_car_no_plate" placeholder="No plate"></div> -->
	<div id="validate_car">
		<!-- VALIDATION 
		<input type="text" placeholder="MH" maxlength="2" id="in_car_no_plate_state" class="only_letter">
		<input type="number" placeholder="12" id="in_car_no_plate_city" onKeyPress="if(this.value.length==2) return false;">
		<input type="text" maxlength="3" placeholder="CT" class="only_letter" id="in_car_no_plate_letter">
		<input type="number" placeholder="1374" onKeyPress="if(this.value.length==4) return false;" id="in_car_no_plate_number">
		 -->

		<!-- NO VALIDATION -->
		<input type="text" placeholder="CAR NO " onKeyPress="if(this.value.length==11) return false;" id="in_car_no_plate_number">
	</div>

	


	<div style="margin-top: 40px;"></div>
	<div style="font-style: italic;font-size: 15px;">Optional</div>
	<div><input type="text" id="in_car_qr_code" placeholder="QR code" style="width: 174px;"></div>

	
<!-- 	<div>
		<select id="select_brand">
			<option value="-1">Car Brand</option>
			
			// require '../query/conn.php';
			// $sql = "SELECT distinct(`cb_brand`) as brand FROM `car_brands` WHERE 1";
			// $exe = mysqli_query($conn, $sql);
			// while($row = mysqli_fetch_assoc($exe)){
			// 	$cb_brand	 = $row["brand"];
			// 	echo '<option value="'.$cb_brand.'">'.ucwords($cb_brand).'</option>';
			// }		
			
			<option value="999">Other</option>
		</select>
	</div> -->

	<!-- <div><select id="select_sub_brand" style="display: none;"></select></div> -->



	<!-- <div><input type="text" id="in_car_brand" style="display: none;" placeholder="Car Brand"></div>
	<div><input type="text" id="in_car_sub_brand" style="display: none;" placeholder="Car sub brand"></div> -->


	<div style="margin-top: 40px;"></div>

	<!-- <div><button id="btn_cancel_new_car">Cancel</button><button id="btn_new_car">Create</button></div> -->
	<div><div class="mat_btn" id="btn_cancel_new_car">CANCEL</div><div class="mat_btn"  style="background-color: #0087C1;" id="btn_new_car">CREATE</div></div>
</div>