
<?php
session_start();

 require_once('db.php');
   require_once('usertable.php');
   require_once('periodlisttable.php');
   $user_name = $_SESSION['username'];
   
   $result = get_user_info($user_name);
   $user = mysqli_fetch_assoc($result);
  
   $user_id=$user['id'];
   $user_email = $user['email'];
   $user_mobile = $user['mobilenumber'];
   $user_available_price = $user['available_amount'];


   ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Withdraw</title>
<?php include 'stylesandscripts.php';?>
  </head>
<body class="rechargePage">   
  
<div class="col-12 head">
	<div class="back">
  <a href="index.php"><i class="fas fa-arrow-left" onClick=""></i><span class="">Recharge</span></a>
  </div>

</div>


<div class="col-12">
  <div class="showBalance text-center">Balance: &#8377;  <?php echo $user_available_price; ?> </div>
   <div class="input-group mt-4">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
    </div>
    <input type="text" class="form-control" placeholder="Enter withdraw amount" id="withdraw_amount">
  <input type="hidden" id="user_id" value="<?php echo $user_id; ?>" >
  </div>
  <input type="hidden" id="available_price_account" value="<?php echo $user_available_price; ?>">

  <div class="withdraw-option text-center mt-4">
    <p>Withdraw to</p>
    <div class="form-check-inline">
                     <label class="form-check-label">

                       <input type="radio" class="form-check-input withdraw_option" name="optradio" value="card" checked>

                       <img src="images/neft.png">
                     </label>
                  </div>
                  <!--<div class="form-check-inline">
                     <label class="form-check-label">

                       <input type="radio" class="form-check-input withdraw_option" name="optradio" value="upi">
                       <img src="images/upi.png">
                     </label>
                  </div> -->
				  <button type="button" class="submit_withdraw">submit</button>
	</div>
	<div class="withdraw_details" style="display:none;">
		<div class="card_Details" style="display:none;">
			<h3>Please Enter Your Account details to Withdraw</h3>
			<input type="text" id="account_number" value="" placeholder="Account number">
			<input type="text" id="account_name" value="" placeholder="Account Hodler name">
			<input type="text" id="account_ifsc" value="" placeholder="IFSC Code">
		</div>
		<div class="upi_details" style="display:none;">
			<h3>Please Enter Your Upi id</h3>
			<input type="text" id="upi_detils" value="" placeholder="sample@okhdfcbank">
		</div>
		<button type="button" id="withdraw_submit">Withdraw</button>
		<div class="success_withdraw" id="success_withdraw"></div>
		<div class="failure_withdraw" id="failure_withdraw"></div>
	</div>
</div>

<?php include 'footer.php';?>

<script>
$(document).ready(function(){
	
	$('#withdraw_submit').click(function(){
		var account_number =$('#account_number').val();
		var account_name =$('#account_name').val();
		var account_ifsc =$('#account_ifsc').val();
		var withdraw_amount =$('#withdraw_amount').val();
		var available_price_account =$('#available_price_account').val();
		var payment_type = $('input[name="optradio"]:checked').val();
		var upi_id = $('#upi_detils').val();
		var proceed;
		$('#success_withdraw').hide();
		$('#failure_withdraw').hide();
		if(payment_type == 'card'){
			if((account_number !='')&&(account_name !='')&&(account_ifsc !='')&&(withdraw_amount !='')){
				proceed ='yes';
			}else{
				proceed ='no';
				$('.failure_withdraw').html('Please Enter All the Account details');
				$('.failure_withdraw').show();
			}
			
		}else{
			if(upi_id !=''){
				proceed ='yes';
				console.log('not empty');
			}else{
				proceed ='no';
				$('.failure_withdraw').html('Please Enter All the Account details');
				$('.failure_withdraw').show();
				console.log('empty');
			}
		}
		console.log(withdraw_amount +'---'+available_price_account);
		if(withdraw_amount != ''){
			if(parseFloat(withdraw_amount) <= parseFloat(available_price_account)){
				proceed ='yes';
			}else{
				proceed ='no';
				$('#failure_withdraw').html('Please Enter the correct Available amount');
				$('#failure_withdraw').show();
			}
		}else{
			proceed ='no';
			$('#failure_withdraw').html('Please Enter the correct details');
			$('#failure_withdraw').show();
		}
		console.log(payment_type +'---'+upi_id);
		console.log(proceed);
		if(proceed == 'yes'){
			$.ajax({
					url: "withdrawl_fn.php",
					type: "post",
					data: {account_number: account_number,account_name:account_name,account_ifsc:account_ifsc,withdraw_amount:withdraw_amount,payment_type:payment_type,upi_id:upi_id},
					success: function (response) {	
						if($.trim(response) =='processing')	{
							$('#success_withdraw').html('Withdraw Successful.');
							$('#success_withdraw').show();
							$('#failure_withdraw').hide();
						}else{
							$('#failure_withdraw').html('Withdraw Failed. Please check whether you have entered the correct account details.');
							$('#success_withdraw').hide();
							$('#failure_withdraw').show();
						}			
					
					   
					},
					error: function(jqXHR, textStatus, errorThrown) {
					   console.log(textStatus, errorThrown);
					}
				});	
		}		
		
	});	
	

	$('.submit_withdraw').click(function(){		
		var payment_type = $('input[name="optradio"]:checked').val();
		$('.withdraw_details').show();
		if(payment_type =='card'){
			$('.card_Details').show();
			
			$('.upi_details').hide();
		}else{
			$('.card_Details').hide();
			$('.upi_details').show();
		}


	
});
});

</script>
<?php include 'footer.php';?>



