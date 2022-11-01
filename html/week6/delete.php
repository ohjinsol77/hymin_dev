<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('dbConn.php');
	
	
	$number = $_POST['pw'];
	var_dump($number);

	$qryDelete = "delete from board where title = " . $number . ";";
	$rstDelete = mysqli_query($CMaster,$qryDelete);


?>

