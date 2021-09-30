<?php
session_start();
include('db.php');

$new_user_name = $_POST['user_name'];


$user_name = $_SESSION['username'];


$user_check_query = "SELECT * FROM users WHERE username='$user_name' LIMIT 1";
$result = mysqli_query($db, $user_check_query);
$user = mysqli_fetch_assoc($result);



$user_id = $user['id'];


$update_user = 'UPDATE users SET username = "'.$new_user_name.'" WHERE id = "'.$user_id.'"' ;

mysqli_query($db, $update_user);

$_SESSION['username'] = $new_user_name;

?>