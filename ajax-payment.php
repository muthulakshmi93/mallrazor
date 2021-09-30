<?php 
 require_once('usertable.php');

$data = [ 
 'payment_id' => $_POST['razorpay_payment_id'],
 'amount' => $_POST['totalAmount'],
 'product_id' => $_POST['product_id'], 
 ];
 
//check payment is authrized or not via API call
 
 $razorPayId = $_POST['razorpay_payment_id'];
 
        $ch = curl_init('https://api.razorpay.com/v1/payments/'.$razorPayId.'');
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
 curl_setopt($ch, CURLOPT_USERPWD, "rzp_test_NYdKz7h4muLSyv:qiwmLe6TTQ2jizjUOIaiH0jf"); // Input your Razorpay Key Id and Secret Id here
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $response = json_decode(curl_exec($ch));
 
 $response->status; // authorized
 
 if($response->method == 'upi'){
	$upi_details['upi_id'] = $response->vpa;
	$upi_details['upi_transaction_id'] = $response->acquirer_data->upi_transaction_id;
 }
 else{
	 $upi_details = array();
 }
// you can write your database insert code here
 
// check that payment is authorized by razorpay or not
if($response->status == 'authorized')
{
	$respval = array('msg' => 'Payment successfully credited', 'status' => true, 'productCode' => $_POST['product_id'], 'paymentID' => $_POST['razorpay_payment_id'], 
	'userEmail' => $_POST['useremail']);  
	$success_payment = array('status' => true, 'userid' => $_POST['product_id'], 'paymentID' => $_POST['razorpay_payment_id'], 'amount' => $_POST['totalAmount'],'payment_method'=>$response->method,'upi_details'=>$upi_details);  
 
	update_user_table_payment($_POST['product_id'],$_POST['name'],$_POST['totalAmount']);
	add_payment_captured($success_payment);
	echo json_encode($respval);
}else{
	$respval = array('msg' => 'Payment Failed', 'status' => false, 'productCode' => $_POST['product_id'], 'paymentID' => $_POST['razorpay_payment_id'], 
'userEmail' => $_POST['useremail']); 
	$success_payment = array('status' => false, 'userid' => $_POST['product_id'], 'paymentID' => $_POST['razorpay_payment_id'], 'amount' => $_POST['totalAmount'],'payment_method'=>$response->method);  
	add_payment_captured($success_payment);
	echo json_encode($respval);
}
 