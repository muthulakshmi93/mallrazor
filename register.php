<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration system PHP and MySQL</title>
 <?php include 'stylesandscripts.php';?>
</head>
<body class="before-login">
  <div class="logo">
    <img src="images/logo.svg">
  </div>
  
  <div class="register-form">
  <div class="header">
  	<h1>Register</h1>
  </div>
	
  <form method="post" action="register.php">
  
  	<div class="input-group">
  	  <label>Username</label>
  	  <input type="text" name="username" value="">
  	</div>
  	<div class="input-group">
  	  <label>Email</label>
  	  <input type="email" name="email" value="">
  	</div>
	<div class="input-group">
  	  <label>Mobile number</label>
  	  <input type="text" name="mobilenumber">
  	</div>
  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2">
  	</div>
	
  	<div class="input-group">
  	  <button type="submit" class="button" name="reg_user">Register</button>
  	</div>
  	<p>
  		Already a member? <a href="login.php">Sign in</a>
  	</p>
  </form>
</div>
</body>
</html>