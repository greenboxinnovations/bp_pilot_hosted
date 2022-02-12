<?php
if(!isset($_SESSION)) {
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

if (isset($_GET['cust_id'])) {

	$cust_id = $_GET['cust_id'];

	$sql = "SELECT * FROM `receipt_books` WHERE `cust_id` ='".$cust_id."' ";
	$exe = mysqli_query($conn, $sql);

	echo '<table id="header-fixed"></table>';
	echo '<table id="table-1">';
	echo '<thead>';
		echo '<tr style="border:1px solid rgb(207,216,220);">';
			echo '<th>#</th>';
			echo '<th>Min</th>';
			echo '<th>Max</th>';
			echo '<th>Date</th>';
			if ($_SESSION['role'] == "admin") {
					echo '<th ></th>';
			}	

		echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	$i=1;
	while($row = mysqli_fetch_assoc($exe)){
		$rb_id	 = $row["rb_id"];
		$min	 = $row["min"];
		$max	 = $row["max"];
		$cust_id	 = $row["cust_id"];
		$date	 = $row["date"];

		echo '<tr>';
			echo '<td>'.$i.'</td>';
			echo '<td>'.$min.'</td>';
			echo '<td>'.$max.'</td>';			
			echo '<td>'.$date.'</td>';
			if ($_SESSION['role'] == "admin") {
				echo '<td id="'.$rb_id.'" class="delete">Delete</td>';
			}
			
		echo '</tr>';
		++$i;
	}
	echo '</tbody>';
	echo '</table>';
}


?>