<?php
if(!isset($_SESSION))
{
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
	
if (isset($_POST['car_id'])) {
	$car_id = $_POST['car_id'];
	$pump_id = $_POST['pump_id'];
	$table_name = 'cars';

	$sql5 = "UPDATE `cars` SET `status` = 'inactive'  WHERE `car_id` = '".$car_id."' ;";
	$exe5 = mysqli_query($conn, $sql5);

	date_default_timezone_set("Asia/Kolkata");
	$date = date("Y-m-d H:i:s");		
	$unix = strtotime($date); 

	// $upload_dir =  realpath(__DIR__ . '/../mysql_uploads');
	// $filename = $upload_dir ."/".$table_name.'.sql';
	// $db_name = "pump_master";
	
	// // exec("/usr/bin/mysqldump -u\"pump_master_user\" --password=\"pump_master_user123!@#\" \"".$db_name."\" \"".$table_name."\" > ".$filename);
	// exec(Globals::MYSQLDUMP_PATH." -u\"".Globals::DB_USER_NAME."\" --password=\"".Globals::DB_PASSWORD."\" \"".Globals::DB_NAME."\" \"".$table_name."\" > ".$filename

	// $sql3 = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = 'cars';";
	// $exe3 = mysqli_query($conn ,$sql3);

	Globals::updateSyncTable("cars", "car_id", $unix, $pump_id);


	echo'success';
}
	
?>