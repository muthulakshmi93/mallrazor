<?php 

function get_user_details( $maintable, $leftjointable , $userid ){
	
	include( 'db.php' );
	$my_query = " SELECT up.period, up.amount, up.value, up.date, up.status, up.profit, up.servicefee , pr.price ,pr.number  FROM ". $maintable ." as up LEFT JOIN ". $leftjointable ." as pr on up.period=pr.period WHERE up.user_id=".$userid." ORDER BY up.period DESC LIMIT 15 ";
	$user_list = mysqli_query($db, $my_query);
	return $user_list;
	
}


function save_user_details($table_details,$values ){
	
	include( 'db.php' );
	
	$current_date_number = $values['period'];
	$user_id = $values['user_id'];
	$current_entry = $values['value'];
	
	
	$query = "INSERT INTO user_parity_record (period, user_id,amount,value,date) VALUES('$current_date_number',$user_id,'10', '$current_entry','".date('Y-m-d G:H:s')."')";
  	mysqli_query($db, $query);
	
}

function get_user_info($user_name){
	include( 'db.php' );
	$user_check_query = "SELECT * FROM users WHERE username='$user_name' LIMIT 1";
	$result = mysqli_query($db, $user_check_query);
	
	return $result;
}

function update_user_table_payment($user_id,$user_name,$amount){
	include( 'db.php' );
	
	$amount = $amount/100;
	$user_details = get_user_info($user_name);
	$user = mysqli_fetch_assoc($user_details);
	$user_id=$user['id'];
	$new_deposit_price = $user['deposit_amount'] + $amount;
	$new_available_price = $user['available_amount'] + $amount;
	
	$update_query = 'UPDATE users SET available_amount = "'.$new_available_price.'" , deposit_amount ="'. $new_deposit_price .'" WHERE id ="'. $user_id .'"' ;
	
	
	mysqli_query($db, $update_query);

}

function add_payment_captured($payment_details){
	include( 'db.php' );
	$transc_id = $payment_details['paymentID'];
	$status = $payment_details['status'];
	$userid = $payment_details['userid'];
	$payment_method = $payment_details['payment_method'];
	$amount = $payment_details['amount']/100;
	
	if($payment_method == 'card'){
	 $ch = curl_init('https://api.razorpay.com/v1/payments/'.$transc_id.'/card');
	 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	 curl_setopt($ch, CURLOPT_USERPWD, "rzp_test_NYdKz7h4muLSyv:qiwmLe6TTQ2jizjUOIaiH0jf"); // Input your Razorpay Key Id and Secret Id here
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 $response = json_decode(curl_exec($ch));	 
		 $card_last_digit = $response->last4;
		 $card_network = $response->network;
		 $card_type = $response->type;	 
		 $upi_id ='-';
		 $upi_transcation_id ='-';
	}else{
		$card_last_digit = '-';
		$card_type = '-';
		$card_network = '-';
		$card_type = '-';
		$upi_id ='-';
		$upi_transcation_id ='-';
	}
	
	if($payment_method == 'upi'){
		$upi_id = $payment_details['upi_details']['upi_id'];
		$upi_transcation_id = $payment_details['upi_details']['upi_transaction_id'];
	}
	
	$query = "INSERT INTO  rp_details (userid,status,amount,transaction_id,payment_method,cardlast,card_type,card_network,upi_id,upi_transaction_id) VALUES('$userid',$status,'".$amount."','".$transc_id."','".$payment_method."','".$card_last_digit."','".$card_type."','".$card_network."','".$upi_id."','".$upi_transcation_id."')";
	
	 
  	mysqli_query($db, $query);
}

function get_payment_details($user_id){
	
	include( 'db.php' );
	$user_check_query = "SELECT * FROM rp_details WHERE userid='$user_id'";
	$result = mysqli_query($db, $user_check_query);
	
	return $result;
	
}

function update_payment_details($response,$user_id,$user_name){
	include( 'db.php' );
	$payout_id = $response->id;
	$amount = $response->amount /100;
	$payment_mode = $response->mode;
	$fund_account_id = $response->fund_account_id;
	$status = $response->status;
	
	
	$query = "INSERT INTO  rp_payout_details (user_id,amount,payment_mode,fund_account_id,payout_id,status) VALUES('$user_id',$amount,'".$payment_mode."','".$fund_account_id."','".$payout_id."','".$status."')";
	
		 
  	mysqli_query($db, $query);
	
	$user_details = get_user_info($user_name);
	$user = mysqli_fetch_assoc($user_details);
	
	$new_available_price = intval($user['available_amount'] - $amount);
	
	$update_query = 'UPDATE users SET available_amount = "'.$new_available_price.'" WHERE id ="'. $user_id .'"' ;	
	
	mysqli_query($db, $update_query);
	
	
	
}
?>