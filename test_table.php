<?php require_once('db.php');

$period = $_GET['period'];

$query = "INSERT INTO test_table (period) VALUES('$period')";
 	mysqli_query($db, $query);
	echo $query;


?>