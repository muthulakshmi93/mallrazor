<?php
session_start();
include('db.php'); 
include('usertable.php'); 
$user_id = $_SESSION['user_id'];
$current_entry = $_POST['this_number'];
$current_token = $_POST['current_token'];
$option_value = $_POST['option_value'];
$current_date= date('Ymd');
$random_code = rand(1000,9999);

$current_date_number=$current_date."".$current_token;


$insert_array['period']=$current_date_number;
$insert_array['user_id']=$user_id;
$insert_array['amount']=10;
$insert_array['value']=$current_entry;
$insert_array['date']=date('Y-m-d G:H:s');

if($option_value == 1){
	save_user_details('user_parity_record',$insert_array);
} 
if($option_value == 2){
	save_user_details('user_sapre_record',$insert_array);
} 
if($option_value == 3){
	save_user_details('user_bcone_record',$insert_array);
} 
if($option_value == 4){
	save_user_details('user_emerd_record',$insert_array);
} 

$content ='<div class="card">

      <!-- Card header -->
      <div class="card-header" role="tab" id="headingOne1">
        <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne'.$random_code.'" aria-expanded="true"
           aria-controls="collapseOne'.$random_code.'">
          <p class="mb-0">'.$current_date_number.' - <span class="greenText"> Wait</span>

            <i class="fas fa-angle-down rotate-icon"></i>
          </p>
        </a>
      </div>

      <!-- Card body -->
      <div id="collapseOne'.$random_code.'" class="collapse" role="tabpanel" aria-labelledby="headingOne1"
           data-parent="#accordionEx">
        <div class="card-body">     
			<h4>Period Detail</h4>
			<table>
				<tr>
					<td>Period</td>
					<td>'. $current_date_number .'</td>
				</tr>
				<tr>
					<td>Contract Money</td>
					<td>10</td>
				</tr>
				<tr>
					<td>Contract Count</td>
					<td>1</td>
				</tr>
				<tr>
					<td>Delivery</td>
					<td>9.8</td>
				</tr>
				<tr>
					<td>Fee</td>
					<td>0.20</td>
				</tr>
				<tr>
					<td>Select</td>
					<td>'.$current_entry.'</td>
				</tr>
				<tr>
					<td>Status</td>
					<td>Wait</td>
				</tr>
				<tr>
					<td>Create Time</td>
					<td>'.date('Y-m-d G:H').'</td>
				</tr>
			</table>
        </div>
      </div>

    </div>';


echo $content;

 ?>