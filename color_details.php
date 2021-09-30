<?php 


function color_insert($color_details,$table_name){
include('db.php'); 
	$query = "INSERT INTO ". $table_name ." (period, price, number) 
				  VALUES('".$color_details['current_date_number']."', '".$color_details['current_price']."', '".$color_details['current_number']."')";
	mysqli_query($db, $query);


}