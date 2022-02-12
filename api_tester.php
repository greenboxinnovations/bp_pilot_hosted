<?php
require __DIR__.'/query/conn.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>API TESTER</title>
<link rel="apple-touch-icon" sizes="57x57" href="css/favi5/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="css/favi5/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="css/favi5/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="css/favi5/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="css/favi5/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="css/favi5/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="css/favi5/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="css/favi5/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="css/favi5/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="css/favi5/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="css/favi5/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="css/favi5/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="css/favi5/favicon-16x16.png">
<link rel="manifest" href="css/favi5/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="css/favi5/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		
		$(document).ready(function(){

			var method,url;

			function add_input(){
				$('#post_fields').append('<div class="field_val"><input type="text" class="field" placeholder="field"><input type="text" class="val" placeholder="value"><button class="add_field">Add Field</button><button class="remove_field">Remove</button></div>');
			}


			$('#url').on('focus',function(){
				$(this).css('background-color','white');
			});


			$('body').delegate('.add_field','click',function(){
				add_input();
			});


			$('body').delegate('.remove_field','click',function(){
				$(this).parent().remove();
			});


			$('#method').on('change',function(){
				method = $('#method option:selected').val();
				if(method == 'post'){
					add_input();
					$('#create_object').show();
				} else {
					$('#post_fields').empty();
					$('#create_object').hide();
				}
			});
			

			$('#submit').on('click',function(){
				
					var url = $('#url').val();

					var myObject = {};

					$('.field_val').each(function(){



						var field = $(this).find('input.field').val();
						var val = $(this).find('input.val').val();

						if( field != '' && val != '' ){
							myObject[field] = val;
							console.log(field+':'+val);
						}else{
							alert('Some input error');
						}
					});

					console.log(myObject);
				
					if(url == ''){
						$('#url').css('background-color','red');
					} else {

						console.log(url);
						var json_data = JSON.stringify(myObject);

						$.ajax({
							url: url,
							type: method,
							contentType: "application/json",	
							data:json_data,						
							success: function(response) {						
						     	console.log(response);
						     	$('#response_container').show();
						     	$('#response').html(response);
						    }
						});	
					}					
					
			});

			$('#create_object').on('click',function(){

				var url = $('#url').val();
				

				var html_data = '';
				var html_object = 'var myObject = {};<br/>';
				var stringify = 'var json_data = JSON.stringify(myObject);';
				var br = '<br/>';
				var ajax = '$.ajax({'+br+'url: '+url+','+br+'type: POST,'+br+'contentType: "application/json",'+br+'data:json_data,'+br+'success: function(response) {'+br+'console.log(response);'+br+'}'+br+'});';

				$('input.field').each(function(){
					var name = $(this).val()
					var html = 'var '+name+' = $(\'#'+name+'\').val();';

					html_object = html_object+'myObject["'+name+'"] = '+name+';'+br;

					html_data = html_data+html+br;
				});
				$('#object_response_container').show();
				console.log(ajax);
				$('#object_response_container').html(html_data+br+html_object+br+stringify+br+br+ajax);
			});

		});
	</script>
	<style type="text/css">
	*{padding: 0;margin: 0;}
	body{font-family: helvetica;background-color: rgb(196,196,196);}
	.container{width: 60%;height: auto;background-color: white;margin: 3em;padding: 3em;border-radius: 5px;}
	.content{margin-top: 2em;margin-left: 2em;}	
	#url{padding: 0.3em;width: 30em;}
	#method{padding: 0.3em;}
	.field_val{padding-top: 1em;}
	.field_val input{margin-right: 3em;padding: 0.3em;}	
	.field_val button{margin-right: 1.5em;padding: 0.3em;}	
	button{padding: 0.3em 2em;margin-right: 4em;}
	#create_object{display: none;}
	</style>
</head>
<body>


<div class="container">

	<div class="content"><h3>API Tester</h3></div>

	<!-- url -->
	<div class="content"><input type="text" id="url" value="api/"></input></div>

	<!-- GET/POST -->
	<div class="content">
		<select id="method">
			<option value="get">GET</option>
			<option value="post">POST</option>
		</select>
	</div>

	<!-- POST fields -->
	<div class="content">
		<div id="post_fields">

		</div>	
	</div>

	<div class="content">

		<!-- SUBMIT -->
		<button id="submit">SUBMIT</button>

		<!-- CREATE OBJECT -->
		<button id="create_object">CREATE OBJECT</button>

	</div>

</div>


<!-- response -->
<div class="container" id="response_container" style="display: none;">
	<div class="content">
		<div id="response"></div>
	</div>
</div>

<!-- object response -->
<div class="container" id="object_response_container" style="display: none;">
	<div class="content">
		<div id="response"></div>
	</div>
</div>

</body>
</html>