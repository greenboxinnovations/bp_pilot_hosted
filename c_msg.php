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

	<script type="text/javascript" src="js/user_agent.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
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
							console.log("asd");
							$('#view_all').show();
							$('#login').hide();
						}
						else{
							console.log("fail");
							$('#view_all').hide();
							$('#login').show();
						}
					}
				});
			}

			$('body').delegate('#login', 'click', function(e) {
				e.stopPropagation(); 

				var mobile_no = $(this).attr('mobile');

				$.ajax({
					url: 'exe/login_customer_otp_request.php',
					type: 'POST',
					data:{
						mobile_no : mobile_no,
						request_otp: true
					},
					success: function(response) {
						var json = $.parseJSON(response);
						console.log(response);
						if (json.success) {
							window.location.href = cust_login_url + json.cust_id;
						}else{
							alert(json.msg);
						}	
					}
				});
			 
			});
		});
	</script>

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

		#padding_div{			
			padding: 20px;
			}

		#cust_details{
			font-size: 20px;
			padding: 20px;
			background-color: white;
			border-radius: 5px;
			border: 1px solid rgb(220,220,220);
			margin-bottom: 30px;
		}
		#cust_display_name{
			font-family: 'Roboto Slab', serif;font-size: 30px;
			/*background-color: green;*/
			display: inline-block;
			vertical-align: top;
			width: 50%;
		}
		#button_div{
			text-align: right;
			display: inline-block;
			vertical-align: top;
			width: 50%;
			margin-top: 5px;
		}

		a{text-decoration: none;color: rgba(255,255,255,0.85);}

		#button_div button{
			/*background-color: green;padding: 0px 10px 0px 10px;*/
			  position: relative;

		/*display: block;*/
		/*margin: 30px auto;*/
		padding: 10px;

		overflow: hidden;

		border-width: 0;
		outline: none;
		border-radius: 2px;
		box-shadow: 0 1px 4px rgba(0, 0, 0, .6);
		font-weight: 600;
		background-color: #2E66B1;
		color: #ecf0f1;

		transition: background-color .3s;
				}
				.clear_both{clear: both;}
				img {
					display: block;
					vertical-align: bottom;
				  height: auto;
				  width: 100%;
				  border-bottom-left-radius: 5px;
				  border-bottom-right-radius: 5px;
				  /*margin-left: auto;		  */
				  /*margin-right: auto;*/
				  /*margin: 20px;*/
				}

				.img_border{
					border: 1px solid rgb(180,180,180);margin-bottom: 20px;
					border-radius: 5px;
				}
				.img_desc{
					/*background-color: rgb(200,200,200);*/
					background-color: #2E66B1;
					color: rgba(255,255,255,0.9);
					padding-left: 20px;
					padding-top: 5px;
					padding-bottom: 5px;
					font-weight: 600;
					border-top-left-radius: 5px;
					border-top-right-radius: 5px;
					/*margin-top: 25px;			*/
				}

				.title{text-align: left;}
				.val{
					 
				}
				.inline{
					display: inline-table;width: 50%;
				}

				video{width: 100%;}


				@media only screen and (min-width:600px){
					#top_header{padding-top: 5px;}
					#top_header img{height: 60px;width: auto;}
					#top_header_bar{height: 18px;margin-bottom: 30px;}
					/*.img_border{display: inline-block;width: 400px;}*/
					.fluid_containers{
						display: inline-block;
						width: calc(50% - 60px);margin-right: -4px;vertical-align: top;
						padding-left: 30px;
						padding-right: 30px;
					}
					/*.fluid_containers img{height: 320px;width: auto;}*/
				}
	</style>
</head>
<body>

<div id="top_header">
	<img src="css/cmsg_header.png">
</div>
<div id="top_header_bar"></div>

<div id="padding_div">

<?php
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

if(isset($_GET['t'])){
	$trans_string = $_GET['t'];
	$btn = true;
	
	if (isset($_GET['btn'])) {
		$btn = false;
	}

	// prepared statement
	// $sql = "SELECT * FROM `transactions` WHERE `trans_string` = ?";
	$sql = "SELECT a.*,b.car_no_plate, c.cust_company,c.cust_disp_name,c.cust_ph_no
			FROM `transactions` a
			JOIN `cars` b
			ON a.car_id = b.car_id
			JOIN `customers` c
			ON c.cust_id = a.cust_id
			where a.trans_string = '".$trans_string."'";
	
	$exe = mysqli_query($conn, $sql);
	if(mysqli_num_rows($exe) > 0){
		while($row = mysqli_fetch_assoc($exe)) {

			//--------------------------------//
			// customer details
			$display_name = $row['cust_disp_name'];			


			echo '<div class="fluid_containers">';
			echo '<div id="cust_details">';
				$display_name 	= ucwords($display_name);
				
				echo '<div id="cust_display_name">'.$display_name.'</div>';

				echo '<div id="button_div">';

				$url  = Globals::URL_CUST_LIST.$row['cust_id'];
				$url1 = Globals::URL_CUST_LOGIN.$row['cust_id'];

				// if ($btn) {
				// 	echo '<button custid="'.$row['cust_id'].'" id="view_all" ><a href="'.$url.'">VIEW ALL</a></button>';
				// }

				//check if customer has logged in ans the cookie is set and is valid if yes proceed to list page else login
				// if ($login_check == 1) {
				// 	echo '<button custid="'.$row['cust_id'].'" id="view_all" ><a href="'.$url.'">VIEW ALL</a></button>';
				// }else{
				// 	echo '<button custid="'.$row['cust_id'].'" id="login" mobile="'.$row['cust_ph_no'].'"  ><a href="'.$url1.'">LOGIN</a></button>';
				// }


				echo '<button custid="'.$row['cust_id'].'" style="display:none;" id="view_all" ><a href="'.$url.'">VIEW ALL</a></button>';
			
				echo '<button custid="'.$row['cust_id'].'" style="display:none;" id="login" mobile="'.$row['cust_ph_no'].'" ><a >LOGIN</a></button>';
				


				echo '</div>';				
				echo '<div class="clear_both"></div>';

				echo '<div class="title inline">'.strtoupper($row['car_no_plate']) .'</div>'; 
				// if ($btn) {
				// 	echo '<div class="val inline" id="view_all" custid="'.$row['cust_id'].'"><button>View All</button></div>';
				// }				
				echo '<br>';
				echo '<br>';

				echo'<div class="container" >';
					echo '<div class="title inline">T-NO</div>';  echo '<div class="val inline">'.($row['transaction_no']).'</div>';
				echo '</div>';

				if ($row['receipt_no'] != 0) {
						echo'<div class="container" >';
							echo '<div class="title inline">Receipt Number</div>';  echo '<div class="val inline">'.($row['receipt_no']).'</div>';
						echo '</div>';
				}			

				echo'<div class="container" >';
					echo '<div class="title inline">Fuel</div>';  echo '<div class="val inline">'.ucwords($row['fuel']).'</div>';
				echo '</div>';

				echo'<div class="container" >';
					echo '<div class="title inline">Amount</div>';  echo '<div class="val inline">'.$row['amount'].'</div>';
				echo '</div>';

				echo'<div class="container" >';
					echo '<div class="title inline">Litres</div>';  echo '<div class="val inline">'.$row['liters'].'</div>';
				echo '</div>';

				echo'<div class="container" >';
					echo '<div class="title inline">Fuel Rate</div>';  echo '<div class="val inline" >'.$row['rate'].'</div>';
				echo '</div>';

				$duration = $row['trans_time'];

				if ($duration == "") {
					$duration = '00:00';
				}

				echo'<div class="container" >';
					echo '<div class="title inline">Duration</div>';  echo '<div class="val inline" >'.$duration.'</div>';
				echo '</div>';


			echo '</div>';
			echo '</div>';
			// echo '<div class="clear_both"></div>';
		
			$date_dir 	= date('Y-m-d', strtotime($row['date']));
			//--------------------------------//
			// transaction videos
			$video_dir = 'videos';
			$vid_path = $video_dir."/".$date_dir."/".$trans_string.".mp4";


			if(file_exists($vid_path)) {
				echo '<div class="fluid_containers">';
				echo '<div class="img_border">';
				echo '<div class="img_desc">VIDEO</div>';
				echo '<video controls>
				<source src="'.$vid_path.'" type="video/mp4">
				Your browser does not support the video tag.
				</video>';
				echo '</div>';
				echo '</div>';
				// echo '<br>';
			}			

			//--------------------------------//
			// transaction photos
			$upload_dir = 'uploads';

			$check 			= ['_start.jpeg','_start_top.jpeg','_stop.jpeg','_stop_top.jpeg'];
			$description 	= ['Zero Photo','Zero Overhead Photo','Completion Photo','Completed Overhead Photo'];

			foreach ($check as $i => $extention) {

				$file_path = $upload_dir."/".$date_dir."/".$trans_string.$extention;

				if(file_exists($file_path)) {
					echo '<div class="fluid_containers">';
					echo '<div class="img_border">';
					echo '<div class="img_desc">'.strtoupper($description[$i]).'</div>';
					echo '<img src="'.$file_path.'">';
					echo '</div>';
					echo '</div>';
					// echo '<br>';
				}else{
					// echo "photo error";
					// echo '<br>';
				}
			}

			echo'<br/>';
			echo $date_time 	= $row['date'];
			echo '<br/>';
		}	
	}
}
?>
</div><!-- padding div -->
<script type="text/javascript">
	$(document).ready(function(){
		$('.fluid_containers img').each(function(){
			console.log($(this).attr('src')+" "+$(this).height());
		});
	});
</script>
</body>
</html>