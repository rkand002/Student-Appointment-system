<?php
	include ('connect.php');
	//print_r($_SERVER);

	$server_filename_array = explode("/", $_SERVER['SCRIPT_NAME']);
	print_r($server_filename_array);
	if(in_array("Prod_StudentAppointmentSystem", $server_filename_array))
		echo "yes";
	else
		echo "dev";
?>