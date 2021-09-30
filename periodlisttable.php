<?php 

function get_period_list($tablename, $limit){
	
	include( 'db.php' );
	$my_query = "SELECT * FROM ". $tablename ." ORDER BY period DESC LIMIT ".$limit;
	$result = mysqli_query($db, $my_query);
	return $result;
	
}

function date_flow_insert($current_hours_minutes,$newminutes){
	include( 'db.php' );
	
	$update_query = 'UPDATE date_flow SET minutes = "'.$newminutes.'" , date ="'. $current_hours_minutes .'" WHERE id ="1"' ;
  	mysqli_query($db, $update_query);
	
}

function get_date_flow(){
	include( 'db.php' );
	$my_query = "SELECT * FROM date_flow LIMIT 1";
	$result = mysqli_query($db, $my_query);
	return $result;
	
}
?>