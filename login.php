<!DOCTYPE html>
<html>
<head>
	<title>FuelCam Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

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
	<meta name="theme-color" content="#ffffff">

	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<script src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			// Functions
			function setCookie(cname, cvalue, exdays) {
				var d = new Date();
				d.setTime(d.getTime() + (exdays*24*60*60*1000));
				var expires = "expires="+d.toUTCString();
				document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
			}

			function getCookie(cname) {
				var name = cname + "=";
				var ca = document.cookie.split(';');
				for(var i = 0; i < ca.length; i++) {
					var c = ca[i];
					while (c.charAt(0) == ' ') {
						c = c.substring(1);
					}
					if (c.indexOf(name) == 0) {
						return c.substring(name.length, c.length);
					}
				}
				return "";
			}

			function checkCookie() {
				var username = getCookie("username");
				if (username != "") {
					$('#username_input').val(username);
					$("#password_input").focus();
				} else {
					$('#username_input').focus();
				}
			}

			function showErrorMessage(message) {
				$('#error_response').fadeIn();
				$('#error_response').text(message);				
				setTimeout(function(){
					$('#error_response').fadeOut();
				},3000);
			}

			function logSizes() {
				var w = $(window).width();
				var h = $(window).height();	
				console.log("width: "+w+" height: "+h);
				// 1366, 662
				// 1920, 974
				// $(window).resize(function() {}); 
			}

			function loadLogin() {
				setTimeout(function() {
					$('#login_well').fadeIn(1300);					
					checkCookie();
				}, 1000);
			}			

			function init() {
				// fadeIn div
				// onSuccess check cookie
				loadLogin();				
			}

			function fakeLogin(name, password) {

				var url = 'exe/login_process.php';
				var method = 'post';

				var myObject = {};
				myObject["name"] = name;
				myObject["password"] = password;
				var json_data = JSON.stringify(myObject);
				

				$.ajax({
					url: url,
					type: method,
					contentType: "application/json",	
					data:json_data,						
					success: function(response) {
						console.log(response);
						var json = $.parseJSON(response);
						if (json.message == 'reset') {
							showErrorMessage("Please change your Password");
							
							$('#password_input').val("");
							$("#login_btn").text("Save new Pass");
							
						}
						if (json.message == 'Authentication Success') {
							setCookie("username", name, 14);
							$('#password_input').val("");			// remove password text (incase backpressed)
							window.location.href = 'index.php';
						}
						if (json.message == 'Authentication Demo') {
							setCookie("username", name, 14);
							$('#password_input').val("");			// remove password text (incase backpressed)
							window.location.href = 'demo.php';
						}
						if (json.message == 'Authentication Error') {
							setCookie("username","jiggy",-14);
							showErrorMessage("Something went wrong!");
						}
				    }	
				}); 




				// // successful login
				// if((name == "jiggy") && (password == "jiggy")){
				// 	setCookie("username","jiggy",14);
				// 	$('#password_input').val("");			// remove password text (incase backpressed)
				// 	window.location = "home.php";
				// }
				// // unsuccessful login
				// else{
				// 	setCookie("username","jiggy",-14);
				// 	showErrorMessage("Something went wrong!");
				// }
			}

			function newPass(name, password) {

				var url = 'exe/new_password.php';
				var method = 'post';

				var myObject = {};
				myObject["name"] = name;
				myObject["password"] = password;
				var json_data = JSON.stringify(myObject);
				

				$.ajax({
					url: url,
					type: method,
					contentType: "application/json",	
					data:json_data,						
					success: function(response) {
						console.log(response);
						var json = $.parseJSON(response);

						if (json.message == 'Authentication Success') {
							setCookie("username", name, 14);
							$('#password_input').val("");
							showErrorMessage("Password Changed!");			// remove password text (incase backpressed)
							window.location.href = 'index.php';
						}
						if (json.message == 'Authentication Error') {
							setCookie("username","jiggy",-14);
							showErrorMessage("Something went wrong!");
						}
				    }	
				});




				// // successful login
				// if((name == "jiggy") && (password == "jiggy")){
				// 	setCookie("username","jiggy",14);
				// 	$('#password_input').val("");			// remove password text (incase backpressed)
				// 	window.location = "home.php";
				// }
				// // unsuccessful login
				// else{
				// 	setCookie("username","jiggy",-14);
				// 	showErrorMessage("Something went wrong!");
				// }
			}
			// Globals			
			init();

			// Click functions
			// login button and enter should be same
			$('#password_input').on('keypress',function(e) {
				var pass_val = $(this).val();
				var user_val = $('#username_input').val();

				var text = $('#login_btn').text();

				if((e.keyCode == 13) && (pass_val != "")) {
					// login function here
					// login function here	
					if (text == "Save new Pass") {
						newPass(user_val, pass_val);
					}else{
						fakeLogin(user_val, pass_val);
					}
				}
			});

			$('#login_btn').on('click',function() {
				var pass_val = $('#password_input').val();
				var user_val = $('#username_input').val();

				var text = $('#login_btn').text();

				if(pass_val != "") {
					// login function here	
					if (text == "Save new Pass") {
						newPass(user_val, pass_val);
					}else{
						fakeLogin(user_val, pass_val);
					}
					
				}
			});

		});
	</script>
	<style type="text/css">
		*{padding: 0;margin: 0;}
		body{
			font-family: 'Raleway', sans-serif;
			background-color: #3541CA;
			position: relative;

			background-repeat:no-repeat;
			background-size: cover;		
			/*background-color: grey;*/
		}


		#login_well{
			width: 290px;height: 380px;
			/*background-color: rgb(200,200,200);*/
			display: none;
			overflow: hidden;
			/*background-color: rgba(255,255,255,0.3);*/
			background-color: rgb(219,219,219);
			margin: 0 auto;margin-top: 150px;border-radius: 5px;
			-webkit-box-shadow: 0px 5px 15px 1px rgba(0,0,0,0.5);
			-moz-box-shadow: 0px 5px 15px 1px rgba(0,0,0,0.5);
			box-shadow: 0px 5px 15px 1px rgba(0,0,0,0.5);
		}
		#header{
			font-weight: 700;
			font-size: 16px;
			/*color: rgba(0,0,0,0.9);*/
			color: rgb(120,120,120);
			height: 62px;
			line-height: 62px;
			padding-left: 25px;
			/*border-bottom: 1px solid rgb(140,140,140);*/
			border-bottom: 1px solid rgb(200,200,200);
		}		

		/*SPARTAN TEXT COLOR
		97FF00
		*/

		.mat_btn{
			display: inline-block;
			position: relative;
			/*background-color: #4285f4;*/
			font-weight: 500;
			background-color: rgb(100,100,100);
			color: #fff;
			width: 120px;
			height: 32px;
			line-height: 32px;
			margin-left: 147px;
			margin-top: 10px;			
			border-radius: 2px;
			font-size: 0.9em;
			text-align: center;
			transition: box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
			transition-delay: 0.2s;
			box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);
		}
		.mat_btn:hover{cursor: pointer;}
		.mat_btn:active {
			background-color: rgb(90,90,90);
			box-shadow: 0 8px 17px 0 rgba(0, 0, 0, 0.2);
			transition-delay: 0s;
		}


		.input_placeholder input{
			font-size:17px;
			margin:25px auto;
			color: rgb(80,80,80);
			padding:10px 0px 10px 15px;
			display:block;
			width:230px;
			border-radius: 2px;
			
			border:1px solid rgb(210,210,210);
			-webkit-box-shadow: 0px 2px 2px -2px rgba(0,0,0,0.5);
			-moz-box-shadow: 0px 2px 2px -2px rgba(0,0,0,0.5);
			box-shadow: 0px 2px 2px -2px rgba(0,0,0,0.5);
			/*border:none;*/
			/*border-bottom:1px solid #757575;*/
		}
		.input_placeholder input:focus{
			outline:none;
			-webkit-box-shadow: 0px 2px 10px -2px rgba(0,0,0,0.5);
			-moz-box-shadow: 0px 2px 10px -2px rgba(0,0,0,0.5);
			box-shadow: 0px 2px 10px -2px rgba(0,0,0,0.5);
		}

		#error_response{width: 230px;margin: 0 auto; color: red;font-weight: 700; margin-top: 50px;display: none;}

		@media only screen and (max-width: 768px) {
			body{
				position: relative;

				background-repeat:no-repeat;
				background-size: 100% 100vh;
				/*background-color: green;*/
			}
		}

		@media only screen and (min-width: 1368px) {
			body{
				position: relative;

				background-repeat:no-repeat;
				background-size: 100% 100vh;
				/*background-color: green;*/
			}
		}
	</style>
</head>
<body>
	<div id="login_well">

		<div id="header">Login</div>

		<div class="input_placeholder" style="margin-top: 35px;">
			<input type="text" placeholder="Username" id="username_input"><span class="bar"></span>
		</div>

		<div class="input_placeholder">
			<input type="password" placeholder="Password" id="password_input" autocomplete="off"><span class="bar"></span>
		</div>

		<div class="input_placeholder">
			<div class="mat_btn" id="login_btn">LOGIN</button>
		</div>

		<div class="input_placeholder">
			<div id="error_response"></div>
		</div>
	</div>
</body>
</html>