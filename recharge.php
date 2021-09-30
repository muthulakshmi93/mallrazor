<?php 
 session_start(); 
 $user_id = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>MallGame</title>
<?php include 'stylesandscripts.php';?>
  </head>
<body class="rechargePage">   
  
<div class="col-12 head">
	<div class="back">
  <i class="fas fa-arrow-left" onClick=""></i><span class="">Recharge</span>
  </div>

  <i class="fa fa-history"></i>

</div>


<div class="rechargeForm col-12">
  
   <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="fas fa-wallet"></i></span>
    </div>
    <input type="text" class="form-control" placeholder="Enter or Select recharge amount" id="price">
	<input type="hidden" id="user_id" value="<?php echo $user_id; ?>" >
  </div>

    <div class="choose_amount">
      <div class="button button_grey col-xs-2 join_button" data-value="100">100</div>
      <div class="button button_grey col-xs-2 join_button" data-value="300">300</div>
      <div class="button button_grey col-xs-2 join_button" data-value="500">500</div>
      <div class="button button_grey col-xs-2 join_button"data-value="1000">1000</div>
      <div class="button button_grey col-xs-2 join_button" data-value="2000">2000</div>
      <div class="button button_grey col-xs-2 join_button" data-value="5000">5000</div>
      <div class="button button_grey col-xs-2 join_button" data-value="10000">10000</div>
      <div class="button button_grey col-xs-2 join_button" data-value="50000s">50000</div>
    </div> 
    <div class="payment_sec">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-wallet"></i></span>
        </div>
		 <button id="payButton" class="btnAction">Deposit</button>
        <!--<input type="text" class="form-control" placeholder="Enter or Select recharge amount">-->
  </div>
    </div>

</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
 
 $(document).ready(function() {
 
 $("#payButton").click(function(e) {
 
 var getAmount = $("#price").val();
 var product_id =  $("#userid").val();
 var useremail =  'dummyuseremail@gamil.com';
 
 var totalAmount = getAmount * 100;
 
 var options = {
 "key": "rzp_test_NYdKz7h4muLSyv", // your Razorpay Key Id
 "amount": totalAmount,
 "name": "Product Name",
 "description": "Dummy Product",
 "image": "https://www.codefixup.com/wp-content/uploads/2016/03/logo.png",
 "handler": function (response){
 $.ajax({
 url: 'ajax-payment.php',
 type: 'post',
 dataType: 'json',
 data: {
 razorpay_payment_id: response.razorpay_payment_id , totalAmount : totalAmount ,product_id : user_id ,useremail : useremail,
   }, 
 success: function (data) 
 {
 //alert(data.msg);
 console.log(data);
 //window.location.href = 'success.php/?productCode='+ data.productCode +'&payId='+ data.paymentID +'&userEmail='+ data.userEmail +'';
 
 }
      });
 },
 "theme": {
 "color": "#528FF0"
 }
 };
 
 var rzp1 = new Razorpay(options);
 rzp1.open();
 e.preventDefault();
});
 
});
</script>



<?php include 'footer.php';?>