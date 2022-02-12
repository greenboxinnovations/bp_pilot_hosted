<?php
if(!isset($_SESSION))
{
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
	
$in_id = $_POST['in_id'];

$date = date("Y-m-d H:i:s");

$sql = "SELECT * FROM `invoices` WHERE `in_id` =  '".$in_id."' ";
$exe = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($exe)){
	$date1 		= $row['from'];
	$date2 		= $row['to'];
	$cust_id 	= $row['cust_id'];
	$total      = $row['amount'];
}

$sql2 = "SELECT `cust_post_paid` FROM `customers` WHERE `cust_id` =  '".$cust_id."' ";
$exe2 = mysqli_query($conn, $sql2);
while($row2 = mysqli_fetch_assoc($exe2)){
	$cust_post_paid      = $row2['cust_post_paid'];
}


$sql1 = "UPDATE `transactions` SET `billed`= 'N', `last_updated` = '".$date."'  WHERE `cust_id` = '".$cust_id."' AND date(`date`) BETWEEN '".$date1."' AND '".$date2."' ";
$exe1 = mysqli_query($conn, $sql1);

if ($cust_post_paid == "Y") {
	$sql4 = "UPDATE `customers` SET `cust_outstanding` = `cust_outstanding` + '".$total."', `payment_balance` = `payment_balance`-'".$total."' WHERE `cust_id` = '".$cust_id."' ;";
}else{
	$sql4 = "UPDATE `customers` SET `cust_balance` = `cust_balance` - '".$total."', `payment_balance` = `payment_balance`-'".$total."' WHERE `cust_id` = '".$cust_id."' ;";
}

$exe4 = mysqli_query($conn, $sql4);


$sql5 = "UPDATE `invoices` SET `status` = 'N'  WHERE `in_id` = '".$in_id."' ;";
$exe5 = mysqli_query($conn, $sql5);

?>