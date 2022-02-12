<?php
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

$cust_f_name	 = null;
$cust_m_name	 = null;
$cust_l_name	 = null;
$cust_ph_no	 	 = null;
$cust_post_paid	 = null;
$cust_balance	 = null;
$cust_outstanding= null;
$cust_company	 = null;
$cust_deposit	 = null;
$cust_gst		 =  null;
$cust_service	 =  null;
$cust_address	 = null;
$cust_credit_limit = null;
$cust_app_limit = null;
$cust_id = null;


if (isset($_GET['cust_id'])) {
		$cust_id = $_GET['cust_id'];

		$sql = "SELECT * FROM `customers` WHERE `cust_id` = '".$cust_id."' ";
		$exe = mysqli_query($conn, $sql);

		while($row = mysqli_fetch_assoc($exe)){
			$cust_f_name	 = $row["cust_f_name"];
			$cust_m_name	 = $row["cust_m_name"];
			$cust_l_name	 = $row["cust_l_name"];
			$cust_ph_no	 	 = $row["cust_ph_no"];
			$cust_post_paid	 = $row["cust_post_paid"];
			$cust_balance	 = $row["cust_balance"];
			$cust_outstanding= $row["cust_outstanding"];
			$cust_company	 = $row['cust_company'];
			$cust_deposit	 = $row['cust_deposit'];
			$cust_gst	 	 = $row['cust_gst'];
			$cust_service	 = $row['cust_service'];
			$cust_address	 = $row['cust_address'];
			$cust_credit_limit= $row['cust_credit_limit'];
			$cust_app_limit = $row['cust_app_limit'];
		}

		if ($cust_post_paid == 'Y') {
			$cust_balance = 0;
		}else{
			$cust_outstanding = 0;
			$cust_credit_limit = 0;
			$cust_deposit = 0;
		}
}



?>

<div style="background-color: rgb(249,250,251);width: 500px;padding: 20px;border: 1px solid rgb(222,227,231);border-radius: 3px;">
	<div class="form_header">NEW CUSTOMER</div>

	<div><textarea style="padding: 5px;" id="in_cust_comp_name" placeholder="Company Name"  rows="3" cols="28" ><?php echo $cust_company;?></textarea></div>
	<div><input type="text" id="in_cust_gst" placeholder="GST Number" value=<?php echo $cust_gst;?>></div>
	<div style="margin-top: 20px;"></div>
	<div><input type="text" id="in_cust_f_name" placeholder="First Name" value=<?php echo $cust_f_name;?>></div>
	<div><input type="text" id="in_cust_m_name" placeholder="Middle Name" value=<?php echo $cust_m_name;?>></div>
	<div><input type="text" id="in_cust_l_name" placeholder="Last Name" value=<?php echo $cust_l_name;?>></div>

	<div style="margin-top: 25px;"></div>

	<!-- <div><input type="number" id="in_cust_ph_no" placeholder="Phone Number" maxlength="10" value=<?php echo $cust_ph_no;?>></div> -->
	<!-- <div><input type="number" id="in_cust_ph_no" placeholder="Phone Number" maxlength="10" value=<?php echo $cust_ph_no;?>><button id="add_ph_no">Add Phone Number</button><button id="del_ph_no">Delete</button></div> -->

	<?php

	if($cust_ph_no != null){
		$ph_array = explode("|", $cust_ph_no);
		if(sizeof($ph_array) > 1){
			foreach ($ph_array as $no) {
				echo '<div class="div_ph_no"><input type="number" class="in_cust_ph_no" placeholder="Phone Number" maxlength="10" value="'.$no.'"><button class="add_ph_no">Add Phone Number</button><button class="del_ph_no">Delete</button></div>';
				
			}			
		}
		else{
			echo '<div class="div_ph_no"><input type="number" class="in_cust_ph_no" placeholder="Phone Number" maxlength="10" value="'.$cust_ph_no.'"><button class="add_ph_no">Add Phone Number</button><button class="del_ph_no">Delete</button></div>';
		}
	}
	else{
		echo '<div class="div_ph_no"><input type="number" class="in_cust_ph_no" placeholder="Phone Number" maxlength="10" value=""><button class="add_ph_no">Add Phone Number</button><button class="del_ph_no">Delete</button></div>';
	}
	?>

	

	<div><input type="number" id="in_cust_service" placeholder="Service Percentage" class="single_decimal" value=<?php echo $cust_service;?> ></div>

	<div style="margin-top: 25px;"></div>
	<div>
		<?php
			echo'<select id="select_is_postpaid">';
			echo'<option value="-1"  disabled>Payment Type</option>';
			if ($cust_post_paid != null) {
				if ($cust_post_paid == "Y") {
					echo'<option value="Y" selected>Postpaid</option>';
				}else{
					echo'<option value="N" selected>Prepaid</option>';
				}
			}else{
				echo'<option value="Y" >Postpaid</option>
					<option value="N" >Prepaid</option>';
			}
			echo'</select>';
		?>
		
	</div>
	<div style="margin-top: 20px;"></div>
	<div><textarea style="padding: 5px;" id="in_cust_address" placeholder="Address"  rows="5" cols="28" ><?php echo $cust_address;?></textarea></div>

	<div style="margin-top: 25px;"></div>

	<?php

	if(($cust_post_paid == "Y")||($cust_post_paid == null)){
		echo '<div class="small_headers">CURRENT BALANCE</div>';
		echo '<div><input type="number" id="in_cust_balance" placeholder="Current Balance" class="single_decimal" disabled value='.$cust_balance.'></div>';
		echo '<div class="small_headers">OUTSTANDING AMOUNT</div>';
		echo '<div><input type="number" id="in_cust_outstanding" placeholder="Outstanding Amount" class="single_decimal" value='.$cust_outstanding.'></div>';
		echo '<div class="small_headers">CREDIT LIMIT</div>';
		echo '<div><input type="number" id="in_cust_credit_limit" placeholder="Credit limit" class="single_decimal" value='.$cust_credit_limit.'></div>';
		echo '<div class="small_headers">APP LIMIT</div>';
		echo '<div><input type="number" id="in_cust_app_limit" placeholder="App Limit" class="single_decimal" value='.$cust_app_limit.'></div>';
		echo '<div class="small_headers">DEPOSIT PAID</div>';
		echo '<div><input type="number" id="in_cust_deposit" placeholder="Deposit Paid" class="single_decimal" value='.$cust_deposit.'></div>';
		echo '<div style="margin-top: 20px;"></div>';
		echo '<div style="margin-top: 20px;"></div>';
	}
	else if($cust_post_paid == "N"){
		echo '<div class="small_headers">CURRENT BALANCE</div>';
		echo '<div><input type="number" id="in_cust_balance" placeholder="Current Balance" class="single_decimal" value='.$cust_balance.'></div>';
		echo '<div class="small_headers">OUTSTANDING AMOUNT</div>';
		echo '<div><input type="number" id="in_cust_outstanding" placeholder="Outstanding Amount" class="single_decimal" disabled  value='.$cust_outstanding.'></div>';
		echo '<div class="small_headers">CREDIT LIMIT</div>';
		echo '<div><input type="number" id="in_cust_credit_limit" placeholder="Credit limit" class="single_decimal" disabled value='.$cust_credit_limit.'></div>';
		echo '<div class="small_headers">APP LIMIT</div>';
		echo '<div><input type="number" id="in_cust_app_limit" placeholder="App Limit" class="single_decimal" value='.$cust_app_limit.'></div>';
		echo '<div class="small_headers">DEPOSIT PAID</div>';
		echo '<div><input type="number" id="in_cust_deposit" placeholder="Deposit Paid" class="single_decimal" disabled value='.$cust_deposit.'></div>';	
		echo '<div style="margin-top: 20px;"></div>';
		echo '<div style="margin-top: 20px;"></div>';
	}
	?>
<!-- 		<div class="small_headers">CURRENT BALANCE</div>
		<div><input type="number" id="in_cust_balance" placeholder="Current Balance" class="single_decimal" value=></div>
		<div class="small_headers">OUTSTANDING AMOUNT</div>
		<div><input type="number" id="in_cust_outstanding" placeholder="Outstanding Amount" class="single_decimal" disabled  value=></div>
		<div class="small_headers">CREDIT LIMIT</div>
		<div><input type="number" id="in_cust_credit_limit" placeholder="Credit limit" class="single_decimal" disabled value=></div>
		<div class="small_headers">App Limit</div>
		<div><input type="number" id="in_cust_disp_limit" placeholder="App Limit" class="single_decimal" value=></div>
		<div class="small_headers">DEPOSIT PAID</div>
		<div><input type="number" id="in_cust_deposit" placeholder="Deposit Paid" class="single_decimal" disabled value=></div>		
		<div style="margin-top: 20px;"></div>
		<div style="margin-top: 20px;"></div> -->
	
	<!-- <div><button id="btn_cancel_cust">Cancel</button><button id="btn_new_cust">Create</button></div> -->

	<?php
	if ($cust_id != null) {
		echo'<div><div class="mat_btn" id="btn_cancel_cust">CANCEL</div><div class="mat_btn" style="background-color: #0087C1;" id="btn_new_cust" type="update" custid="'.$cust_id.'">UPDATE</div></div>';
	}
	else{
		echo'<div><div class="mat_btn" id="btn_cancel_cust">CANCEL</div><div class="mat_btn" style="background-color: #0087C1;" id="btn_new_cust" type="new">CREATE</div></div>';
	}
	?>

	
</div>