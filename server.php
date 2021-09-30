<?php
session_start();

// connect to the database
include( 'db.php' );


 if (isset($_REQUEST['reg_user'])) {
        // removes backslashes
        $username = stripslashes($_REQUEST['username']);
        //escapes special characters in a string
        $username = mysqli_real_escape_string($db, $username);
        $email    = stripslashes($_REQUEST['email']);
        $email    = mysqli_real_escape_string($db, $email);
        $password =  md5(stripslashes($_REQUEST['password_1']));
        $mobilenumber = stripslashes($_REQUEST['mobilenumber']);
        $password = mysqli_real_escape_string($db, $password);
        $create_datetime = date("Y-m-d H:i:s");
        	$query = "INSERT INTO users (username, email, password,mobilenumber,deposit_amount, profit_amount, loss_amount , available_amount) 
  			  VALUES('$username', '$email', '$password','$mobilenumber','0','0','0','0')";
        $result   = mysqli_query($db, $query);
        if ($result) {
            echo "<div class='form'>
                  <h3>You are registered successfully.</h3><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a></p>
                  </div>";
        } else {
            echo "<div class='form'>
                  <h3>Required fields are missing.</h3><br/>
                  <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
                  </div>";
        }
    }