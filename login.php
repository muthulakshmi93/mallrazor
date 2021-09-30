<?php require_once('db.php') ?>
<?php
session_start();

if (isset($_POST['login_user'])) {
  $username = stripslashes($_REQUEST['username']);    // removes backslashes
        $username = mysqli_real_escape_string($db, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($db, $password);
        // Check user is exist in the database
        $query    = "SELECT * FROM `users` WHERE username='$username'
                     AND password='" . md5($password) . "'";
        $result = mysqli_query($db, $query) or die(mysql_error());
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $_SESSION['username'] = $username;
            // Redirect to user dashboard page
            header("Location: myaccount.php");
        } else {
            echo "<div class='form'>
                  <h3>Incorrect Username/password.</h3><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a> again.</p>
                  </div>";
        }
}

?>
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
  <div class="login-form">
  <div class="header">
  	<h1>Login</h1>
  </div>
	 
  <form method="post" action="login.php">
  	<!-- <?php// include('errors.php'); ?> -->
  	<div class="input-group">
  		<label>Username</label>
  		<input type="text" name="username" >
  	</div>
  	<div class="input-group">
  		<label>Password</label>
  		<input type="password" name="password">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="button" name="login_user">Login</button>
  	</div>
  	<p>
  		Don't have an account? <a href="register.php">Sign up</a>
  	</p>
  </form>
</div>
</body>
</html>

