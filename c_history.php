<?php
require __DIR__.'/query/conn.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>GreenBox Innovations</title>

	<!-- favicons -->
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
	<meta name="theme-color" content="#FFCB05">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


	<style type="text/css">
		*{padding: 0;margin: 0;}
		body{font-family: 'Open Sans',sans-serif;background-color: rgb(240,240,240);}

		#top_header{
			padding-top: 10px;
			/*height: 70px;*/
			background-color: #FFCB05;
			/*background-color: #2E66B1;*/
			width: 100%;}
		#top_header_bar{background-color: #2E66B1;height: 30px;}
		#padding_div{padding: 15px;}

		#cust_details{font-size: 20px;font-family: 'Open Sans',sans-serif;padding: 10px;}
		#cust_display_name{
			font-family: 'Roboto Slab', serif;font-size: 30px;
			/*background-color: green;*/
			padding-left: 20px;
			padding-top: 20px;
			margin-bottom: 20px;
		}
		#top_header img {
			display: block;
			vertical-align: bottom;
			height: auto;
			width: 100%;
			border-bottom-left-radius: 5px;
			border-bottom-right-radius: 5px;				
		}
		td img {					
			display: inline-block;
			/*vertical-align: middle;*/
			width: 12px;
			height: 12px;
			margin-left: 5px;
		}			

		/*viewpager*/
		#view_pager{
			width: 100%;display: flex;height: 50px;
			font-size: 14px;
			font-weight: 700;line-height: 50px;color: rgb(157,157,171);			
		}
		.pager_single{flex: 1;text-align: center;

			border:1px solid transparent;
			border-bottom: none;
		}
		.pager_active{background-color: #fff;color:rgb(112,157,224);border-top-right-radius: 7px;border-top-left-radius: 7px;
			border:1px solid rgb(220,220,220);
			border-bottom: none;
		}

		#main_container{
			background-color: white;border-radius: 7px;border-top-left-radius: 0px;
			border: 1px solid rgb(220,220,220);
			border-top: none;
			position: relative;
		}
		#left_top {
			margin-left: 3px;
			content: '';
			position: absolute;
			top: 0;
			border-top: 1px solid rgb(220,220,220);
			width: calc(50% - 3px);
			display: none;
		}
		#right_top {
			margin-right: 3px;
			content: '';
			position: absolute;
			top: 0;
			right: 0;
			border-top: 1px solid rgb(220,220,220);
			width: calc(50% - 3px);
		}

		/*#table_holder{padding: 10px;}*/
		table{width: 100%;border-collapse: collapse;}
		td{
			/*background-color: green;*/
			padding-top: 5px;
			padding-bottom: 5px;
			color: rgba(0,0,0,0.8);
		}
		tr{cursor: pointer;}
		tr:nth-child(even):not(.header){background-color: rgb(248,248,248);}
		th{
			text-align: left;padding-top: 5px;
			padding-bottom: 5px;
			font-size: 12px;
		}

		/*#table_holder_invoices{display: none;}*/

		.right_text{text-align: right;}		
		.amount{
			width:50px;
			padding-right: 10px;
			/*background-color: green;*/
		}
		.fuel{
			width: 20px;
			padding-right: 5px;
			text-align: right;
			color: rgb(180,180,180);			
			/*background-color: green;*/
		}
		.date_col{
			width:50px;
			/*color: rgb(80,100,100);*/
		}
		.sr{
			padding-left: 10px;
			width: 25px;
			color: rgb(180,180,180);
			/*background-color: green;*/
		}

		.in_amount{padding-right: 10px;width:50px;}
		.issued{width: 50px;padding-right: 10px;color: rgb(180,180,180);}
		.from,.to{width: 50px;padding-right: 10px;}

		.show_headers{font-family: 'Open Sans';display: none;font-weight: 700;margin-left: 6px;color: rgba(0,0,0,0.84);margin-bottom: 12px;}

		/*snackbar*/
#snackbar{
	/*display: none;*/
	position: fixed;
	/*font-weight: 600;*/
	bottom: -50px;
	/*bottom: 0px;*/
	width: 400px;
	height: 50px;
	background-color: #263238;
	color: rgb(221,222,217);
	right: 0;
	margin-right: 40px;
	line-height: 50px;
	padding-left: 24px;
	border-radius: 4px;
}

		@media only screen and (min-width:600px){

			#left_top{display: none;}
			#right_top{display: none;}


			#top_header{padding-top: 5px;}
			#top_header img{height: 60px;width: auto;}
			#top_header_bar{height: 18px;}

			#view_pager{display: none;}				
			#table_holder_invoices,#table_holder_trans{				
				display: inline-block;
				width: calc(50% - 40px);
				margin-right: -4px;vertical-align: top;								
				overflow: hidden;
				padding: 20px;
			}
			#main_container{border-radius: 5px;width: 1000px;margin-right: auto;margin-left: auto;border-top: 1px solid rgb(220,220,220);}
			tr:nth-child(even):not(.header):hover,tr:hover:not(.header){
				background-color: rgb(204,236,244);
				cursor: pointer;
			}
			.show_headers{display: block;font-family: }

		}
	</style>

	<script type="text/javascript" src="js/user_agent.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
	<script type="text/javascript">
		$(document).ready(function(){

			var storage = "";
			var user_agent = "";
			storage = jscd.os +' '+ jscd.osVersion +' '+ jscd.browser +' '+jscd.mobile;
			user_agent = navigator.userAgent;
			console.log(storage,user_agent);
			check(storage,user_agent);

			var msg_url        = <?php echo json_encode(Globals::URL_MSG_VIEW);?>;
			var cust_login_url = <?php echo json_encode(Globals::URL_CUST_LOGIN);?>;

			$('.date1_h').datepicker({ dateFormat: 'dd-mm-yy' });
			$('.date2_h').datepicker({ dateFormat: 'dd-mm-yy' });

			function showSnackBar(message) {
				$('#snackbar').text(message);
				$('#snackbar').animate({'bottom':'0'},function() {
					setTimeout(function(){
						$('#snackbar').animate({'bottom':'-50px'});           
					},2000);
				});
			}


			function getWidth(){
				var v = $(window).width();
				console.log(v);
				if(v > 600){
					$('#table_holder_invoices').show();
				}
				else{
					$('#table_holder_invoices').hide();
				}
			}
			getWidth();

			function check($storage,$user_agent){
				$.ajax({
					url: 'exe/mobile_lock.php',
					type: 'POST',
					data:{
						storage : $storage,
						user_agent: $user_agent
					},
					success: function(response) {
						console.log(response);
						var data = JSON.parse(response);
						if(data.success){
							console.log("success");								
						}
						else{
							console.log("fail");
							var cust_id = getUrlParameter('cust_id');
							window.location = cust_login_url + cust_id;
						}
					}
				});
			}


			var getUrlParameter = function getUrlParameter(sParam) {
				var sPageURL = decodeURIComponent(window.location.search.substring(1)),
				sURLVariables = sPageURL.split('&'),
				sParameterName,
				i;

				for (i = 0; i < sURLVariables.length; i++) {
					sParameterName = sURLVariables[i].split('=');

					if (sParameterName[0] === sParam) {
						return sParameterName[1] === undefined ? true : sParameterName[1];
					}
				}
			};


			$(window).resize(getWidth());


			$('body').delegate('tr', 'click', function() {

				// window.open('exe/report.php?cust_id='+cust_id+'&date1='+from+'&date2='+to+'&type='+type+'&date_invoice='+date_invoice+'&invoice_no='+invoice_no, '_blank');		
				var type = $(this).attr('redirect');
				console.log(type);

				if(type=="trans"){

					var trans_string = $(this).attr('t');
					window.location.href = msg_url + trans_string;						
				}
				else if(type=="invoice"){
					var invoice_no = $(this).attr('t');

					window.open('exe/report.php?cust_id=old&date1=old&date2=old&type=old&date_invoice=t&invoice_no='+invoice_no, '_blank');	
					// window.open('exe/report.php?cust_id=old&date1=old&date2=old&type=old&date_invoice=t&invoice_no='+invoice_no);	
				}					
			});


			$('body').delegate('#search', 'click', function(){
				var cust_id 		= $(this).attr('custid');

				var date1 			= $(".date1_h").datepicker("option", "dateFormat", "yy-mm-dd" ).val();
				var date2 			= $(".date2_h").datepicker("option", "dateFormat", "yy-mm-dd" ).val();

				$('.date1_h').datepicker({ dateFormat: 'dd-mm-yy' });
				$('.date2_h').datepicker({ dateFormat: 'dd-mm-yy' });

				if ((date1 != "")&&(date2 != "")) {
					if (date1 <= date2) {
						window.location.href= "c_history.php?cust_id="+cust_id+"&date1="+date1+"&date2="+date2;
						// alert("working");
					}else{
						// showSnackBar("Date 1 should be Smaller");
						alert("Date 1 should be Smaller");
					}
				}else{
					alert("Please enter both dates!");
					// showSnackBar("Please enter both dates!");
				}


			});

		});
	</script>



</head>
<body>
<div id="top_header">
	<img src="css/cmsg_header.png">
</div>
<div id="top_header_bar"></div>

<div id="padding_div">

<div id="view_pager">
	<div class="pager_single pager_active">HISTORY</div>
	<div class="pager_single"></div>	
</div>

<div id="main_container">
	<div id="left_top"></div>
	<div id="right_top"></div>

	<?php
	require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

	if(isset($_GET['cust_id'])){
		$cust_id = $_GET['cust_id'];

		if (isset($_GET['date1'])) {
			$date1 = $_GET['date1'];
			$date2 = $_GET['date2'];
		}else{
			$date2 = date("Y-m-d");
			$date1 = date('Y-m-d', strtotime("-3 months", strtotime($date2)));
		}


		$date11 = date("d-m-Y",strtotime($date1));
		$date22 = date('d-m-Y',strtotime($date2));


		echo'<div style="display:inline-block;margin-right:10px;">From<br>';
		// echo' <input type="date" id="date1" value="'.$date1.'"></input>';
			echo' <input type="text" class="date1_h" value="'.$date11.'"></input>';
		echo'</div>';

		echo'<div style="display:inline-block;margin-right:10px;">To<br>';
			// echo'<input type="date" id="date2" value="'.$date2.'"></input>';
			echo' <input type="text" class="date2_h" value="'.$date22.'"></input>';
		echo'</div>';

		echo'<div style="display:inline-block;margin-right:10px;"><br>';
			echo'<button id="search" custid="'.$cust_id.'">Search</button>';
		echo'</div>';
		
		// prepared statement
		// $sql = "SELECT * FROM `transactions` WHERE `trans_string` = ?";
		// $sql = "SELECT a.*, b.cust_company,b.cust_f_name,b.cust_l_name
		// 		FROM `transactions` a
		// 		JOIN `customers` b
		// 		ON b.cust_id = a.cust_id
		// 		WHERE a.cust_id = '".$cust_id."' AND a.billed = 'N' ;";
		
		$sql = "SELECT a.*, b.cust_company,b.cust_f_name,b.cust_l_name,c.car_no_plate
				FROM `transactions` a
				JOIN `customers` b
				ON b.cust_id = a.cust_id
				JOIN `cars` c
				ON a.car_id = c.car_id
				WHERE date(a.date) BETWEEN '".$date1."' AND '".$date2."' AND a.cust_id = '".$cust_id."' AND a.billed = 'Y' ;";





		$exe = mysqli_query($conn, $sql);
		if(mysqli_num_rows($exe) > 0){

			$i=0;
			$total = 0;

			while($row = mysqli_fetch_assoc($exe)) {

				//--------------------------------//
				// customer details
				$display_name = $row['cust_company'];
				if($display_name == ""){
					$display_name = $row['cust_f_name']." ".$row['cust_l_name'];
				}


				if ($i == 0) {
					$display_name 	= ucwords($display_name);
					
					echo '<div id="cust_display_name">'.$display_name.'</div>';
					// echo'<br/>';

					echo '<div id="table_holder_trans">';
					echo '<div class="show_headers">History</div>';
					echo '<table>';
					echo'<tr class="header">';
						echo '<th class="sr">#</th>';
						echo '<th class="car">CAR</th>';
						echo '<th class="date_col">DATE</th>';
						echo '<th class="fuel"></th>';
						echo '<th class="right_text amount">AMOUNT</th>';
					echo '</tr>';
				}
				$i++;

				$fuel = ($row['fuel'] == 'petrol') ? "P" : "D";

					if($row['trans_string'] == ""){
						echo'<tr redirect=none t="none">';
						echo '<td class="sr">'.$i.'</td>';  
						echo '<td>'.strtoupper($row['car_no_plate']).'<img src="css/icons/no_msg.png"></td>';
					}
					else{
						echo'<tr redirect=trans t="'.$row['trans_string'].'">';
						echo '<td class="sr">'.$i.'</td>';  
						echo '<td>'.strtoupper($row['car_no_plate']).'</td>';
					}						
					echo '<td class="date_col">'.date('M j',strtotime($row['date'])).'</td>';  
					
					// echo '<td>'.date('d-m-Y',strtotime($row['date'])).'</td>'; 
					echo '<td class="fuel">'.$fuel.'</td>';
					echo '<td class="right_text amount">'.$row['amount'].'</td>';

				echo '</tr>';

				$total += $row['amount']; 
			}	

			// echo'<tr>';
			// 	echo '<td></td>';  
			// 	echo '<td></td>';  
			// 	echo '<td>Total</td>';
			// 	echo '<td class="right_text">'.$total.'</td>';
			// echo '</tr>';
			echo '</table>';
			echo '</div>'; // id="table_holder_trans"
		}else{
			echo '<div><h2>No Transactions</h2></div>';
		}
		
	}
	?>
</div>

</div><!-- padding div -->

<!-- snackbar -->
<div id="snackbar"></div>
</body>
</html>