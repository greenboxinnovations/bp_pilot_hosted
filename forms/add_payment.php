
<div style="background-color: rgb(249,250,251);width: 500px;padding: 20px;border: 1px solid rgb(222,227,231);border-radius: 3px;">
	<div class="form_header">NEW PAYMENT</div>	

	<div style="margin-top: 25px;"></div>
	<div><input type="number" id="payment_amount" placeholder="Payment Amount" class="single_decimal"></div>	
	<input type="date" id="payment_date" style="width: 169px;" value="<?php echo date("Y-m-d");?>">
	<div style="margin-top: 20px;"></div>
	<div><textarea placeholder="Comments" style="padding: 5px;width: 300px;height: 100px;" id="payment_comment"></textarea></div>
	<div style="margin-top: 20px;"></div>
	<div style="margin-top: 20px;"></div>
	<!-- <div><button id="btn_cancel_cust">Cancel</button><button id="btn_new_cust">Create</button></div> -->
	<div><div class="mat_btn" id="btn_cancel_payment">CANCEL</div><div class="mat_btn" style="background-color: #0087C1;" id="btn_confirm_payment" invoiceno="<?php echo $_GET['invoice_no'];?>" invoiceamount="<?php echo $_GET['invoice_amount']; ?>"  >UPDATE</div></div>
	<?php 

	// echo '<input type="hidden" val="'.$_GET['invoice_no'].'" id="invoice_no">';
	// echo '<input type="hidden" val="'.$_GET['invoice_amount'].'" id="invoice_amount">';

	?>
</div>
