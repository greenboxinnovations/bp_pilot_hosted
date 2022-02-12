<?php
require 'exe/lock.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>R-Books</title>
	<link rel="apple-touch-icon" sizes="57x57" href="css/favi/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="css/favi/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="css/favi/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="css/favi/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="css/favi/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="css/favi/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="css/favi/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="css/favi/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="css/favi/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="css/favi/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="css/favi/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="css/favi/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="css/favi/favicon-16x16.png">
	<link rel="manifest" href="css/favi/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="css/favi/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
 
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/main.css">

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			// 
			$('tr').on('click', function(){
				var cust_id = $(this).attr('custid');
				// console.log(cust_id);

				window.location.href = 'customer_details.php?cust_id='+cust_id+'&tab=rbooks';					
			});
		});
	</script>

	<style type="text/css">
		#rbook_table{margin: 20px auto;}
		.d_name{width: 500px;}
		.push_right{text-align: right;width: 80px;}
		.d_date{width: 100px;text-align: right;}

		tr:hover{cursor: pointer;background-color: rgb(220,220,220);}
	</style>
</head>
<body>

<!-- app nav -->
<div id="app_bar">  
	<div id="menu">
		<img src="css/icons/ic_menu.png">
	</div>
	<div id="app_name"><a href="#">FuelCam</a></div>
</div>


<!-- side nav -->
<?php 
	$active_page = 'rbooks';
	require 'nav.php';
?>


<!-- wrapper -->
<div id="wrapper">
	<?php
	    if(!isset($_SESSION)){session_start();}
		$pump_id = $_SESSION['pump_id'];
		$sql = "SELECT a.*,b.* FROM `receipt_books` a 
				JOIN `customers` b
				ON a.cust_id = b.cust_id
				WHERE b.cust_pump_id = '".$pump_id."' order by a.cust_id ASC;";

		$exe = mysqli_query($conn, $sql);



		echo '<table id="rbook_table">';
		while($row = mysqli_fetch_assoc($exe)){

			// display 
			$display_name = ucwords($row['cust_disp_name']);
			$cust_id = $row['cust_id'];

			$min = $row["min"];
			$max = $row["max"];
			$date_added = date('d-m-Y', strtotime($row['date']));

			echo '<tr custid="'.$cust_id.'">';
				echo '<td class="d_name">'.$display_name.'</td>';
				echo '<td class="push_right">'.$min.'</td>';
				echo '<td class="push_right">'.$max.'</td>';
				echo '<td class="d_date">'.$date_added.'</td>';
			echo '<tr>';
		}
		echo '</table>';
	?>
</div>


<!-- snackbar -->
<div id="snackbar"></div>

</body>
</html>