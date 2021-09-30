 <?php 
 session_start();
 $account_number = $_POST['account_number'];
 $account_name = $_POST['account_name'];
 $account_ifsc = $_POST['account_ifsc'];
 $payment_type = $_POST['payment_type'];
 $upi_id = $_POST['upi_id'];
 $withdraw_amount = $_POST['withdraw_amount']*100;
 
	require_once('db.php');
	require_once('usertable.php');
	//require_once('payout.php');
	
	$user_name = $_SESSION['username'];
	$result = get_user_info($user_name);
	$user = mysqli_fetch_assoc($result);
	$user_id=$user['id'];
	$user_email = $user['email'];
	$user_mobile = $user['mobilenumber'];
	$user_available_price = $user['available_amount'];
	
	$headers = array();
	$headers[] = 'Content-Type: application/json';
 
	$user_details = array(
		'name'=>$user_name,
		'email'=>$user_email,
		'contact'=>$user_mobile,
		'reference_id'=>$user_id
	);
 
 $contact_id = create_contacts($user_details,$headers);
 
 
if($payment_type =='card'){
	$fund_accounts = array(
	'contact_id'=>$contact_id,
	'account_type'=>'bank_account',
	'bank_account'=>array(
		"name"=>$account_name,
		"ifsc"=>$account_ifsc,
		"account_number"=>$account_number
	  )
	);
	
	$fund_account_id = create_fund_account($fund_accounts,$headers);
}else{
	$fund_accounts = array(
	'contact_id'=>$contact_id,
	'account_type'=>'bank_account',
	'vpa'=>array(
		"address"=>$upi_id
	  )
	);
	$fund_account_id = create_fund_account_upi($fund_accounts,$headers);
}

$transfer = array(
	'account_number' => '2323230088835291',
	"fund_account_id"=> $fund_account_id,
	'amount' => $withdraw_amount,
	"currency"=> "INR",
	"purpose"=>"refund",
	"mode"=>"IMPS",
);
$payout_status = payout_layout($transfer, $headers);

$status = $payout_status->status;

update_payment_details($payout_status,$user_id,$user_name);

echo $status;
	
function create_contacts($user_details,$headers){
	
	$ch = curl_init('https://api.razorpay.com/v1/contacts');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_USERPWD, "rzp_test_NYdKz7h4muLSyv:qiwmLe6TTQ2jizjUOIaiH0jf"); // Input your Razorpay Key Id and Secret Id here
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($user_details));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = json_decode(curl_exec($ch));

	$contact_id = $response->id;
	
	return $contact_id;
}
  

 function create_fund_account($fund_accounts, $headers){	
  
	$ch = curl_init(); curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/fund_accounts');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fund_accounts));
	curl_setopt($ch, CURLOPT_USERPWD, 'rzp_test_NYdKz7h4muLSyv:qiwmLe6TTQ2jizjUOIaiH0jf');  
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = json_decode(curl_exec($ch)); 
	curl_close($ch); 
	$fund_account_id = $result->id;
	return $fund_account_id;
 }


function payout_layout($transfer,$headers){
	
	$ch = curl_init('https://api.razorpay.com/v1/payouts/');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_USERPWD, "rzp_test_NYdKz7h4muLSyv:qiwmLe6TTQ2jizjUOIaiH0jf");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($transfer)); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = json_decode(curl_exec($ch)); 
	return $response;
 
}


 function create_fund_account_upi($fund_accounts, $headers){	
  
	$ch = curl_init(); curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/fund_accounts');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fund_accounts));
	curl_setopt($ch, CURLOPT_USERPWD, 'rzp_test_NYdKz7h4muLSyv:qiwmLe6TTQ2jizjUOIaiH0jf');  
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = json_decode(curl_exec($ch)); 
	curl_close($ch); 

	$fund_account_id = $result->id;
	return $fund_account_id;
 } 


 
 ?>