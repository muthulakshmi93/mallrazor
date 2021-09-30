<?php 

require_once('color_details.php');
require_once('periodlisttable.php');

$tablename = array('parity_record_list','bcone_record_list','emerd_record_list','sapre_record_list');
$results=array();
$current_token = $_POST['current_token'];
$newminutes = $_POST['newminutes'];
$current_hours_minutes = $_POST['current_hours_minutes'];
if($current_token <10){
	$current_token='00'.$current_token;
}else if($current_token <100){
	$current_token='0'.$current_token;
}

$current_date= date('Ymd');

$current_date_number=$current_date."".$current_token;

$next_token = $current_token+1;
if($next_token <10){
	$next_token='00'.$next_token;
}else if($next_token <100){
	$next_token='0'.$next_token;
}

$next_date_number =$current_date."".$next_token;

// connect to the database

require_once('db.php'); 
foreach($tablename as $key => $table_name){
	
	$current_number = rand(0,9);
	$current_four_number =rand(3000,3999);
	$current_price = $current_four_number."".$current_number;
	if($current_number %2 ==0){
		$current_value='even';
		$current_class='redText';
	}else{
		$current_value='odd';
		$current_class='greenText';
	}
	
	$results[$key]['current_date_number']=$current_date_number;
	$results[$key]['next_date_number']=$next_date_number;
	$results[$key]['current_value']=$current_value;
	$results[$key]['current_number']=$current_number;
	$results[$key]['current_class']=$current_class;
	$results[$key]['current_price']=$current_price;
	$results[$key]['next_token']=$next_token;
	$results[$key]['table_name']=$key;
	
	$color_details['current_date_number']= $current_date_number;
	$color_details['current_price']= $current_price;
	$color_details['current_number']= $current_number;
	
	color_insert($color_details ,$table_name);
	
	date_flow_insert($current_hours_minutes,$newminutes);
}

$user_upadted ='no';
$user_list ='SELECT * FROM user_parity_record WHERE period="'. $current_date_number .'"';
$user_parity_list = mysqli_query($db,$user_list);

while($user = mysqli_fetch_assoc($user_parity_list)) { 

	$user_value = $user['value'];
	$amount_spend = $user['amount'];
	$service_fee =($amount_spend/100)*2;
	$user_id = $user['user_id'];
	$user_upadted ='yes';
	
	if(is_numeric($user_value)){
		if(($user_value) % 2 ==0){
			$user_result_value ='even';
		}else{
			$user_result_value ='odd';
		}
		
	}else{
		if($user_value =='green'){
			$user_result_value ='odd';
		}else if($user_value =='red'){
			$user_result_value ='even';
		}else{
			$user_result_value ='neutral';
		}
	}
	
	if($current_number == 0){
		if(is_numeric($user_value)){
			if($user_value == $current_number){
				$status='success';
				$service_fee_amount = $service_fee;
				$profit_amount = ($amount_spend /2)- $service_fee;
			}else{
				$status='fail';
				$service_fee_amount = $amount_spend;
				$profit_amount = 0;
			}			
		}else{		
			if($user_value =='green'){
				$status ='fail';
				$service_fee_amount = $amount_spend;
				$profit_amount = 0;
			}elseif($user_value =='violet'){			
				$status ='success';
				$service_fee_amount = $service_fee;
				$profit_amount = ($amount_spend /2)- $service_fee;			}		
			else{
				$status ='success';
				$service_fee_amount = $service_fee;
				$profit_amount = ($amount_spend /2)- $service_fee;
			}
		}
	}else if($current_number == 5){
		if(is_numeric($user_value)){
			if($user_value == $current_number){
				$status='success';
				$service_fee_amount = $service_fee;
				$profit_amount = ($amount_spend /2)- $service_fee;
			}else{
				$status='fail';
				$service_fee_amount = $amount_spend;
				$profit_amount = 0;
			}			
		}else{
			if($user_value =='green'){
				$status ='success';
				$service_fee_amount = $service_fee;
				$profit_amount = ($amount_spend /2)- $service_fee;
			}elseif($user_value =='violet'){
				$status ='success';
				$service_fee_amount = $service_fee;
				$profit_amount = ($amount_spend / 2)- $service_fee;
			}else{
				$status ='fail';
				$service_fee_amount = $amount_spend;
				$profit_amount = 0;
			}
		}
	}else{
		if(is_numeric($user_value)){
			if($user_value == $current_number){
				$status='success';
				$service_fee_amount = $service_fee;
				$profit_amount = $amount_spend - $service_fee;
			}else{
				$status='fail';
				$service_fee_amount = $amount_spend;
				$profit_amount = 0;
			}
		}else{
			if($user_result_value == $current_value){
				$status='success';
				$service_fee_amount = $service_fee;
				$profit_amount = $amount_spend - $service_fee;
			}else{
				$status='fail';
				$service_fee_amount = $amount_spend;
				$profit_amount = 0;
			}
		}
	}
	
	$update_query = 'UPDATE user_parity_record SET status = "'.$status.'" ,servicefee ="'. $service_fee_amount .'" ,profit ="'.$profit_amount.'"  WHERE period = "'.$current_date_number.'" AND value="'. $user_value .'" AND user_id ="'. $user_id .'"' ;
	
	mysqli_query($db, $update_query);

}
if($user_upadted == 'yes'){
	$total_user_count = 'SELECT SUM(profit) as total_profit FROM user_parity_record where user_id="'. $user_id .'"';
	$total_count_success = mysqli_query($db,$total_user_count);
	$user_profit_list = mysqli_fetch_assoc($total_count_success);

	$total_profit = $user_profit_list['total_profit'];

	$failed_user_count = 'SELECT SUM(servicefee) as total_loss FROM user_parity_record where user_id="'. $user_id .'"';
	$total_count_loss = mysqli_query($db,$failed_user_count);
	$user_loss_list = mysqli_fetch_assoc($total_count_loss);
	$total_loss = $user_loss_list['total_loss'];



	$user_id = $_POST['user_id'];
	$user_check_query = "SELECT * FROM users WHERE id='$user_id' LIMIT 1";
	$result = mysqli_query($db, $user_check_query);
	$user = mysqli_fetch_assoc($result);
	$user_price = $user['deposit_amount'];

	$user_total_amount = $user_price + $total_profit - $total_loss;


	$update_user = 'UPDATE users SET available_amount = "'.$user_total_amount.'" ,profit_amount ="'. $total_profit .'" ,loss_amount ="'.$total_loss.'"  WHERE id = "'.$user_id.'"' ;

	mysqli_query($db, $update_user);
	
}

echo json_encode($results);

?>