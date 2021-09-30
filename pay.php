<?php
 session_start();   
  
   if (!isset($_SESSION['username'])) {
	   $_SESSION['msg'] = "You must log in first";
	   header('location: login.php');
   }
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Recharge</title>
<?php include 'stylesandscripts.php';?>
  </head>
<body class="rechargePage">   
  
<div class="col-12 head">
  <div class="row">
	<div class="back col-3">
  <i class="fas fa-arrow-left" onclick="goBack()"></i><span class=""></span>
  </div>
  <div class="col-6 text-center">Recharge</div>
<div class="col-3">
  <i class="fa fa-history"></i>
</div>
  </div>

</div>
<?php 

require_once('db.php');
require_once('usertable.php');
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['username'];
$result = get_user_info($user_name);
$user = mysqli_fetch_assoc($result);

 
$user_email = $user['email'];

$payment_histroy = get_payment_details($user_id);


  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    // Append the host(domain name, ip) to the URL.   
    $url.= $_SERVER['HTTP_HOST'];   
    
    // Append the requested resource location to the URL   
    $url.= $_SERVER['REQUEST_URI'];    
      
    
?>
<?php 
if(isset($_GET['payId'])){
	$transcid = $_GET['payId'];
}

$secretkey = "0a971c0e2be6c4a0e2333d27fe7f31300b56d749";
if(isset($_POST['orderId'])){
$orderId = $_POST["orderId"];
$userid = $_POST["userid"];
$orderAmount = $_POST["orderAmount"];
$referenceId = $_POST["referenceId"];
$txStatus = $_POST["txStatus"];
$paymentMode = $_POST["paymentMode"];
$txMsg = $_POST["txMsg"];
$txTime = $_POST["txTime"];
$signature = $_POST["signature"];
$data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
$hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;
$computedSignature = base64_encode($hash_hmac);


if ($signature == $computedSignature) {
	
	
	$success_payment = array('status' => true, 'userid' => $userid, 'paymentID' => $_POST['referenceId'], 'amount' => $orderAmount,'payment_method'=>$paymentMode);  
 
	update_user_table_payment($orderId,$user_name,$orderAmount);
	add_payment_captured($success_payment);

 ?>
<div class="alert alert-success text-center">
  <strong>Payment Successful!</strong>
</div>
<?php
 }else{
	
	$success_payment = array('status' => false, 'userid' => $userid, 'paymentID' => $_POST['referenceId'], 'amount' => $orderAmount,'payment_method'=>$paymentMode);  
	add_payment_captured($success_payment);
}
} ?>
 <form method="POST">
 <input type="hidden" name="productId" id="productId" value="<?php echo $user_id; ?>">
 <input type="hidden" name="useremail" id="useremail" value="<?php echo $user_email; ?>">
 <input type="hidden" name="usernmae" id="usernmae" value="<?php echo $user_name; ?>">
      <!-- Product #1 -->
</form>


        
       
<div class="rechargeForm col-12">
  <form id="redirectForm" method="post" action="cashfree/request.php">
	   <div class="input-group mb-3">
		<div class="input-group-prepend">
		  <span class="input-group-text"><i class="fas fa-wallet"></i></span>
		</div>
		
			<input type="text" class="form-control" placeholder="Enter or Select recharge amount" id="price" name="orderAmount">
			<input class="form-control" name="appId" type="hidden" value="92046fbf29fc9e438fbce403664029" />
			<input class="form-control" name="orderId" type="hidden" value="<?php echo strtotime(date('y-m-d h:i:s')); ?>"/>
			<input class="form-control" name="userid" type="hidden" value="<?php echo $user_id; ?>"/>
			<input class="form-control" name="orderCurrency" type="hidden" value="INR"/>
			<input class="form-control" name="orderNote" type="hidden" value="test"/>
			<input class="form-control" name="customerName" type="hidden" value="test"/>
			<input class="form-control" name="customerEmail" type="hidden" value="<?php echo $user_email; ?>" />
			<input class="form-control" name="customerPhone" type="hidden" value="999999999"/>
			<input class="form-control" name="returnUrl" type="hidden" value="http://localhost/mall/cashfree/response.php" />
			<input class="form-control" name="notifyUrl" type="hidden" value="" />
		
		
			<input type="hidden" id="user_id" value="<?php echo $user_id; ?>" >
		  </div>

			<div class="choose_amount text-center">
			  <div class="button button_grey col-xs-2 join_button" data-value="100">100</div>
			  <div class="button button_grey col-xs-2 join_button" data-value="300">300</div>
			  <div class="button button_grey col-xs-2 join_button" data-value="500">500</div>
			  <div class="button button_grey col-xs-2 join_button"data-value="1000">1000</div>
			  <div class="button button_grey col-xs-2 join_button" data-value="2000">2000</div>
			  <div class="button button_grey col-xs-2 join_button" data-value="5000">5000</div>
			  <div class="button button_grey col-xs-2 join_button" data-value="10000">10000</div>
			  <div class="button button_grey col-xs-2 join_button" data-value="50000">50000</div>
			</div> 
			<div class="payment_sec text-center"> 
				  
				   
				   <button type="submit" class="btn btn-primary btn-block button button_green btnAction" value="Pay" id="payButton">Deposit</button>
				
			</div>
	</form>
	<div class="error-money alert alert-danger" style="display:none;">Please choose or enter the deposit money</div>
<div class="pay_history"><h2>Deposit History</h2>


  <table class="table">
                <thead>
                  <tr>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment method</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody id="parity_record">
				<?php  				
				
				while($user = mysqli_fetch_assoc($payment_histroy)) {  
					if(($user['status'] ==true)){
						$status ='Success';
						$status_icon ='fa-check-circle greenText';
					}else{
						$status ='Failed';
						$status_icon ='fa-times-circle redText';
					}
				  ?>
                  <tr>
                    <td><?php echo $user['amount']; ?></td>
                    <td> <i class="fa <?php echo $status_icon; ?>" style="margin-right:5px;"></i><?php echo $status; ?></td>
                    <td><?php echo $user['payment_method']; ?></td>
                    <td><?php echo $user['date']; ?></td>
                   
				 
                  </tr>
				<?php } ?>
                 
                </tbody>
          </table>


</div>
</div>       
   
    </div>
 
  </body>

<script>
 $('.join_button').click(function(){
		 var amount = $(this).attr('data-value');
		 $('#price').val(amount);
		  $('.error-money').hide();
		 
	 });
	 
	 setTimeout(function(){ $('.alert-success').hide(); }, 6000);
	 
</script>
<?php include 'footer.php';?>