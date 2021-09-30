<?php 

   session_start();
    if(!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }
 
include('db.php');
$user_name = $_SESSION['username'];
$user_check_query = "SELECT * FROM users WHERE username='$user_name' LIMIT 1";
$result = mysqli_query($db, $user_check_query);
$user = mysqli_fetch_assoc($result);
$user_id=$user['id'];
$user_email = $user['email'];
$user_available_price = $user['available_amount'];
$_SESSION['user_id']=$user_id;

if(empty($user_available_price)){
	$user_available_price= $user['deposit_amount'];
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Account</title>
<?php include 'stylesandscripts.php';?>
  </head>
<body>
	
<div class="col-sm-12 head">
	<i class="fas fa-user-circle"></i>
	<p> Username: <span class="uname" id="uname"><?php echo $user_name; ?></span> <i class="edit-uname fas fa-pen"data-toggle="modal" data-target="#editUname"></i></p>
	<p> Mobile Number: <span class="umobile"><?php echo $user_email; ?></span></p>
	<p class="available_bal">Available Balance: &#8377;<span class="bal_amt"><?php echo $user_available_price; ?></span>
	</p>
  <a href="pay.php"><div class="button button_green">Recharge</div></a>
	
	<div class="notify-btn button button_grey" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-bell"></i></div>
</div>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
 
    <ul class="nav navbar-nav col-xs-12">
      <li class="active"><a href="#"><i class="fas fa-wallet"></i>Orders</a></li>
      <li><a href="#"><i class="fas fa-bullhorn"></i>Promotions</a></li>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fas fa-wallet"></i>Wallet
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="pay.php">Recharge </a></li>
          <li><a href="withdraw.php">Withdrawal</a></li>
          <li><a href="#">Transaction</a></li>
        </ul>
      </li>
      <li><a href="#"><i class="far fa-credit-card"></i>Bank Card</a></li>
      <li><a href="#"><i class="fas fa-wallet"></i>Address</a></li>
       <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fas fa-shield-alt"></i>Account Security
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Reset Password </a></li>
        </ul>
      </li>
      <li><a href="#"><i class="far fa-comment-dots"></i>Complaint and Suggestions</a></li>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="far fa-question-circle"></i>About Us
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Privacy Policy </a></li>
          <li><a href="#">Risk Disclosure Agreement</a></li>
        </ul>
      </li>
	   <?php  if (isset($_SESSION['username'])) : ?>
	   <li>
    	 <a href="index.php?logout='1'" ><i class="fas fa-sign-out-alt"></i>Logout</a> 
		</li>
    <?php endif ?>
    </ul>
  </div>
</nav>




<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Notice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <p class="title"><b>Announcement! The recharge and withdrawal channel is 7/24 hours.</b></p>
      <div class="modal-body">
        <b>Recharge members can complete the recharge according to the following steps!</b>
        <ol>
            <li>You must first submit an order on the platform.</li>
            <li>Fill in the UPI account to be paid.</li>
            <li>The payment amount must be the same! Do not transfer funds directly, otherwise the recharge will fail! ! ! ! If you have any questions, please prepare relevant screenshots and send an email to <a href="mailto:mallgame@gmail.com">mallgame@gmail.com</a></li>
            
        </ol>
</div>

      <div class="modal-footer">
        <button type="button" class="button button_grey" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="editUname" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Username</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
      	<div class="form-group">
  <label for="usr">Username:</label>
  <input type="text" class="form-control" id="usr">
</div>
		</div>

      <div class="modal-footer">
      	<div class="button button_green" id="update_name">Update</div>
        <button type="button" class="button button_grey" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php';?>