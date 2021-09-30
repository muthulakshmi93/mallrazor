<?php 
   session_start(); 
   
   if (!isset($_SESSION['username'])) {
   $_SESSION['msg'] = "You must log in first";
   header('location: login.php');
   }
   if (isset($_GET['logout'])) {
   session_destroy();
   unset($_SESSION['username']);
   header("location: login.php");
   }
   require_once('db.php');
   require_once('usertable.php');
   require_once('periodlisttable.php');
   $user_name = $_SESSION['username'];
   
   $result = get_user_info($user_name);
   $user = mysqli_fetch_assoc($result);
   $user_id=$user['id'];
   $user_available_price = $user['available_amount'];
   $_SESSION['user_id']=$user_id;
   
   if(empty($user_available_price)){
   $user_available_price= $user['deposit_amount'];
   }
   
   $my_parity_list = get_user_details('user_parity_record','parity_record_list',$user_id);
   $my_sapre_list  = get_user_details('user_sapre_record','sapre_record_list',$user_id);
   $my_bcone_list  = get_user_details('user_bcone_record','bcone_record_list',$user_id);
   $my_emerd_list  = get_user_details('user_emerd_record','emerd_record_list',$user_id);
   
   $date_result = get_date_flow();
   $date_flow = mysqli_fetch_assoc($date_result);
   $date_minutes = $date_flow['minutes'];
   $current_date_new = $date_flow['date'];
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <title>MallGame</title>
      <?php include 'stylesandscripts.php';?>
   </head>
   <body>
      <div class="col-sm-12 head">
         <p class="available_bal">Available Balance: &#8377;<span class="bal_amt"><?php echo $user_available_price; ?></span>
            <i class="fas fa-sync" onClick="window.location.reload()"></i>
         </p>
         <a class="button button_green" href="pay.php">Recharge</a>
         <div class="rules button button_grey" data-toggle="modal" data-target="#exampleModalCenter">Read Rules</div>
      </div>
      <div class="products_tab">
         <ul class="nav nav-tabs">
            <li class="nav-item ">
               <a href="#Parity" class="nav-link active" data-toggle="tab">Parity</a>
            </li>
            <li class="nav-item ">
               <a href="#Sapre" class="nav-link" data-toggle="tab">Sapre</a>
            </li>
            <li class="nav-item ">
               <a href="#Bcone" class="nav-link" data-toggle="tab">Bcone</a>
            </li>
            <li class="nav-item ">
               <a href="#Emerd" class="nav-link" data-toggle="tab">Emerd</a>
            </li>
         </ul>
         <input type="hidden" value="<?php echo $user_available_price;?>" id="current_balance_user">
         <?php 
            $parity_last 			= get_period_list('parity_record_list', 1);	
            $parity_result 			= mysqli_fetch_assoc($parity_last);	
            $current_period_value 	= $parity_result['period']+1;
            $next_val 				= substr($current_period_value, -3);
            
            
            ?>
         <div class="tab-content">
            <input type="hidden" class="offer_in_remaining_seconds" value =" <?php echo date('M d Y'); ?> <?php echo $current_date_new; ?>">
            <input type="hidden" class="current_date" value =" <?php echo date('M d Y'); ?> ">
            <input type="hidden" class="current_minutes" value ="<?php echo $date_minutes; ?>">
            <div class="tab-pane fade show active" id="Parity">
               <div class="tabHeadContent">
                  <div class="period">
                     Period
                     <p class="next_Tokens" data-number="<?php echo $next_val; ?>"><?php echo $current_period_value; ?></p>
                  </div>
                  <div class="count">
                     Count
                     <p id="time" class="timing_changes"></p>
                  </div>
                  <div class="choose_option">
                     <div class="choose_color">
                        <div class="button button_green join_green col-xs-4 float-left join_button" data-value="green" data-toggle="modal" data-target="#joinModal">Join Green</div>
                        <div class="button button_violet join_violet col-xs-4 join_button" data-value="violet">Join Violet</div>
                        <div class="button button_red join_red col-xs-4 float-right join_button" data-value="red">Join Red</div>
                     </div>
                     <div class="choose_number">
                        <div class="button button_redmix col-xs-2 join_button" data-value="0">0</div>
                        <div class="button button_green col-xs-2 join_button" data-value="1">1</div>
                        <div class="button button_red col-xs-2 join_button" data-value="2">2</div>
                        <div class="button button_green col-xs-2 join_button"data-value="3">3</div>
                        <div class="button button_red col-xs-2 join_button" data-value="4">4</div>
                        <div class="button button_greenmix col-xs-2 join_button" data-value="5">5</div>
                        <div class="button button_red col-xs-2 join_button" data-value="6">6</div>
                        <div class="button button_green col-xs-2 join_button" data-value="7">7</div>
                        <div class="button button_red col-xs-2 join_button" data-value="8">8</div>
                        <div class="button button_green col-xs-2 join_button" data-value="9">9</div>
                     </div>
                     <input type="hidden" id="option_value" value="1" class="option_value">
                  </div>
               </div>
               <div class="tableSec">
                  <p class="tableHeading">Parity Record</p>
                  <div class="progress" style="height:2px;">
                     <div class="progress-bar" style="width:100%;"></div>
                  </div>
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Period</th>
                           <th>Price</th>
                           <th>Number</th>
                           <th>Result</th>
                        </tr>
                     </thead>
                     <tbody id="parity_record">
                        <?php  				
                           $result = get_period_list('parity_record_list', 15);
                           while($user = mysqli_fetch_assoc($result)) {  
                           	if(($user['number'] ==0)||($user['number'] ==5)){
                           		$violet_div= '<span class="resultCircle violet"></span>';
                           	}else{
                           		$violet_div='';
                           	}
                           	if($user['number'] % 2 ==0)	
                           	{
                           		$numberclass= 'redText';
                           		$round_class='red';
                           	}else{
                           		$numberclass='greenText';
                           		$round_class='green';
                           	}
                             ?>
                        <tr>
                           <td><?php echo $user['period']; ?></td>
                           <td><?php echo $user['price']; ?></td>
                           <td class="<?php echo $numberclass; ?>"><?php echo $user['number']; ?></td>
                           <td><span class="resultCircle <?php echo $round_class; ?>"></span> <?php echo $violet_div; ?></td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
                  <nav aria-label="Page navigation example" id="pagination">
                     <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                           <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                           <a class="page-link" href="#">Next</a>
                        </li>
                     </ul>
                  </nav>
               </div>
               <div class="parityRecord">
                  <p class="recordHeading">
                     My Parity Record
                  </p>
                  <div class="progress" style="height:2px;">
                     <div class="progress-bar" style="width:100%;"></div>
                  </div>
                  <!--Accordion wrapper-->
                  <div class="accordion md-accordion parity_accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                     <!-- Accordion card --><?php
                        if((mysqli_num_rows($my_parity_list)) > 0){
                        	while($user = mysqli_fetch_assoc($my_parity_list)) { 
                        	/*echo '<pre>';
                        	print_r($user);
                        	echo '</pre>'; */
                        	
                        			$user_status = $user['status'];
                        			$period = $user['period'];
                        			$profit = $user['profit'];
                        			$servicefee = $user['servicefee'];
                        			$amount = $user['amount'];
                        			
                        			$service_fee =($amount/100)*2;
                        			$profit_amount = $amount - $service_fee;
                        			if(empty($period)){
                        				$period = $current_period_value;
                        				
                        			}
                        			
                        			if(empty($user_status)){
                        				$user_status = 'Wait';
                        			}
                        			
                        			if($user_status == 'success'){
                        				$user_class='greenText';
                        				$user_total = $profit + $amount;
                        			}else if($user_status == 'fail'){
                        				$user_class='redText';
                        				$user_total = $profit_amount;
                        			}else{
                        				$user_class='yellowText';
                        				$user_total = '';
                        			}
                        			if($user['number'] % 2 ==0)	
                        			{
                        				$numberclass= 'redText';
                        				$round_class='red';
                        				$result_value_section ='red';
                        			}else{
                        				$numberclass='greenText';
                        				$round_class='green';
                        				$result_value_section ='green';
                        			}
                        			
                        			if(($user['number'] ==0)||($user['number'] ==5)){
                        				$violet_value= 'yes';
                        			}else{
                        				$violet_value='no';
                        			}
                        			
                        			$random_id = uniqid();
                        			
                        			if(is_numeric($user['value'])){
                        				if(($user['value']) % 2 ==0){
                        					$user_number_class ='redText';
                        					$user_result_value ='red';
                        				}else{
                        					$user_number_class='greenText';
                        					$user_result_value ='green';
                        				}
                        				
                        			}else{
                        				if($user['value'] =='green'){
                        					$user_number_class='greenText';
                        					$user_result_value ='green';
                        				}else if($user['value'] == 'violet'){
                        					$user_number_class='violetText';
                        					$user_result_value ='violet';
                        				}else{
                        					$user_number_class ='redText';
                        					$user_result_value ='red';
                        				}
                        			}					
                        			if(is_numeric($user['value'])){
                        				if($user['value'] == $user['number']){
                        					$user_result = 'success';
                        					$result_value=$user['number'];
                        				}else{
                        					$user_result = 'fail';
                        					$result_value=$user['number'];
                        				}
                        			}else{
                        				if($result_value_section == $user_result_value){
                        					$user_result = 'success';
                        				}else{
                        					$user_result = 'fail';
                        				}
                        			}
                        			
                        			if(($violet_value =='yes')&&($user_result_value =='violet')){
                        				$user_result ='success';
                        			}
                        			
                        			
                        			
                        		  ?>
                     <div class="card">
                        <!-- Card header -->
                        <div class="card-header" role="tab" id="headingOne1">
                           <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne_<?php echo $random_id; ?>" aria-expanded="true"
                              aria-controls="collapseOne_<?php echo $random_id; ?>">
                              <p class="mb-0">
                                 <?php echo $period; ?> - <span class="<?php echo $user_class; ?>"> <?php echo ucwords($user_status); ?> 
                                 <?php	if($user_status != 'wait')  echo '-'.$user_total; ?></span>
                                 <i class="fas fa-angle-down rotate-icon"></i>
                              </p>
                           </a>
                        </div>
                        <!-- Card body -->
                        <div id="collapseOne_<?php echo $random_id; ?>" class="collapse" role="tabpanel" aria-labelledby="headingOne1"
                           data-parent="#accordionEx">
                           <table>
                              <tr>
                                 <td>Period</td>
                                 <td><?php echo $user['period']; ?></td>
                              </tr>
                              <tr>
                                 <td>Contract Money</td>
                                 <td><?php echo $user['amount']; ?></td>
                              </tr>
                              <tr>
                                 <td>Contract Count</td>
                                 <td>1</td>
                              </tr>
                              <tr>
                                 <td>Delivery</td>
                                 <td><?php echo $profit; ?></td>
                              </tr>
                              <tr>
                                 <td>Fee</td>
                                 <td><?php echo $servicefee; ?></td>
                              </tr>
                              <tr>
                                 <td>Result</td>
                                 <td class="<?php echo $numberclass; ?>"><?php echo $result_value_section; ?></td>
                              </tr>
                              <tr>
                                 <td>Select</td>
                                 <td class="<?php echo $user_number_class; ?>"><?php echo $user['value']; ?></td>
                              </tr>
                              <tr>
                                 <td>Status</td>
                                 <td><?php echo $user_status; ?></td>
                              </tr>
                              <tr>
                                 <td>Create Time</td>
                                 <td><?php echo $user['date']; ?></td>
                              </tr>
                           </table>
                        </div>
                     </div>
                     <!-- Accordion card -->
                     <?php } 
                        }else{
                         echo '<p class="no_record text-center">No Record Found.</p>';
                        }
                        ?>
                  </div>
                  <!-- Accordion wrapper -->
               </div>
            </div>
            <div class="tab-pane fade" id="Sapre">
               <div class="tabHeadContent">
                  <div class="period">
                     Period
                     <p class="next_Tokens" data-number="<?php echo $next_val; ?>"><?php echo $current_period_value; ?></p>
                  </div>
                  <div class="count">
                     Count
                     <p id="time" class="timing_changes">03:00</p>
                  </div>
                  <div class="choose_color">
                     <div class="button button_green join_green col-xs-4 float-left join_button">Join Green</div>
                     <div class="button button_violet join_violet col-xs-4 join_button ">Join Violet</div>
                     <div class="button button_red join_red col-xs-4 float-right join_button">Join Red</div>
                  </div>
                  <div class="choose_number">
                     <div class="button button_redmix col-xs-2 join_button">0</div>
                     <div class="button button_green col-xs-2 join_button">1</div>
                     <div class="button button_red col-xs-2 join_button">2</div>
                     <div class="button button_green col-xs-2 join_button">3</div>
                     <div class="button button_red col-xs-2 join_button">4</div>
                     <div class="button button_greenmix col-xs-2 join_button">5</div>
                     <div class="button button_red col-xs-2 join_button">6</div>
                     <div class="button button_green col-xs-2 join_button">7</div>
                     <div class="button button_red col-xs-2 join_button">8</div>
                     <div class="button button_green col-xs-2 join_button">9</div>
                  </div>
                  <input type="hidden" id="option_value" value="2" class="option_value">
               </div>
               <div class="tableSec">
                  <p class="tableHeading">Sapre Record</p>
                  <div class="progress" style="height:2px;">
                     <div class="progress-bar" style="width:100%;"></div>
                  </div>
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Period</th>
                           <th>Price</th>
                           <th>Number</th>
                           <th>Result</th>
                        </tr>
                     </thead>
                     <tbody id="sapre_record">
                        <?php  				
                           $result = get_period_list('sapre_record_list', 15);
                           while($user = mysqli_fetch_assoc($result)) {  
                           	if(($user['number'] ==0)||($user['number'] ==5)){
                           		$violet_div= '<span class="resultCircle violet"></span>';
                           	}else{
                           		$violet_div='';
                           	}
                           	if($user['number'] % 2 ==0)	
                           	{
                           		$numberclass= 'redText';
                           		$round_class='red';
                           	}else{
                           		$numberclass='greenText';
                           		$round_class='green';
                           	}
                             ?>
                        <tr>
                           <td><?php echo $user['period']; ?></td>
                           <td><?php echo $user['price']; ?></td>
                           <td class="<?php echo $numberclass; ?>"><?php echo $user['number']; ?></td>
                           <td><span class="resultCircle <?php echo $round_class; ?>"></span> <?php echo $violet_div; ?></td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
               <div class="parityRecord">
                  <p class="recordHeading">
                     My Sapre Record
                  </p>
                  <div class="progress" style="height:2px;">
                     <div class="progress-bar" style="width:100%;"></div>
                  </div>
                  <!--Accordion wrapper-->
                  <div class="accordion md-accordion sapre_accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                     <?php
                        if((mysqli_num_rows($my_sapre_list))>0){
                        	while($user = mysqli_fetch_assoc($my_sapre_list)) { 
                        	
                        			$user_status = $user['status'];
                        			$period = $user['period'];
                        			$profit = $user['profit'];
                        			$servicefee = $user['servicefee'];
                        			$amount = $user['amount'];
                        			
                        			$service_fee =($amount/100)*2;
                        			$profit_amount = $amount - $service_fee;
                        			if(empty($period)){
                        				$period = $current_period_value;
                        				
                        			}
                        			
                        			if(empty($user_status)){
                        				$user_status = 'Wait';
                        			}
                        			
                        			if($user_status == 'success'){
                        				$user_class='greenText';
                        				$user_total = $profit + $amount;
                        			}else if($user_status == 'fail'){
                        				$user_class='redText';
                        				$user_total = $profit_amount;
                        			}else{
                        				$user_class='yellowText';
                        				$user_total = '';
                        			}
                        			if($user['number'] % 2 ==0)	
                        			{
                        				$numberclass= 'redText';
                        				$round_class='red';
                        				$result_value_section ='red';
                        			}else{
                        				$numberclass='greenText';
                        				$round_class='green';
                        				$result_value_section ='green';
                        			}
                        			
                        			if(($user['number'] ==0)||($user['number'] ==5)){
                        				$violet_value= 'yes';
                        			}else{
                        				$violet_value='no';
                        			}
                        			
                        			$random_id = uniqid();
                        			
                        			if(is_numeric($user['value'])){
                        				if(($user['value']) % 2 ==0){
                        					$user_number_class ='redText';
                        					$user_result_value ='red';
                        				}else{
                        					$user_number_class='greenText';
                        					$user_result_value ='green';
                        				}
                        				
                        			}else{
                        				if($user['value'] =='green'){
                        					$user_number_class='greenText';
                        					$user_result_value ='green';
                        				}else if($user['value'] == 'violet'){
                        					$user_number_class='violetText';
                        					$user_result_value ='violet';
                        				}else{
                        					$user_number_class ='redText';
                        					$user_result_value ='red';
                        				}
                        			}					
                        			if(is_numeric($user['value'])){
                        				if($user['value'] == $user['number']){
                        					$user_result = 'success';
                        					$result_value=$user['number'];
                        				}else{
                        					$user_result = 'fail';
                        					$result_value=$user['number'];
                        				}
                        			}else{
                        				if($result_value_section == $user_result_value){
                        					$user_result = 'success';
                        				}else{
                        					$user_result = 'fail';
                        				}
                        			}
                        			
                        			if(($violet_value =='yes')&&($user_result_value =='violet')){
                        				$user_result ='success';
                        			}				
                        ?>	  
                     <div class="card">
                        <!-- Card header -->
                        <div class="card-header" role="tab" id="headingOne1">
                           <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne_<?php echo $random_id; ?>" aria-expanded="true"
                              aria-controls="collapseOne_<?php echo $random_id; ?>">
                              <p class="mb-0">
                                 <?php echo $period; ?> - <span class="<?php echo $user_class; ?>"> <?php echo ucwords($user_status); ?> 
                                 <?php	if($user_status != 'wait')  echo '-'.$user_total; ?></span>
                                 <i class="fas fa-angle-down rotate-icon"></i>
                              </p>
                           </a>
                        </div>
                        <!-- Card body -->
                        <div id="collapseOne_<?php echo $random_id; ?>" class="collapse" role="tabpanel" aria-labelledby="headingOne1"
                           data-parent="#accordionEx">
                           <table>
                              <tr>
                                 <td>Period</td>
                                 <td><?php echo $user['period']; ?></td>
                              </tr>
                              <tr>
                                 <td>Contract Money</td>
                                 <td><?php echo $user['amount']; ?></td>
                              </tr>
                              <tr>
                                 <td>Contract Count</td>
                                 <td>1</td>
                              </tr>
                              <tr>
                                 <td>Delivery</td>
                                 <td><?php echo $profit; ?></td>
                              </tr>
                              <tr>
                                 <td>Fee</td>
                                 <td><?php echo $servicefee; ?></td>
                              </tr>
                              <tr>
                                 <td>Result</td>
                                 <td class="<?php echo $numberclass; ?>"><?php echo $result_value_section; ?></td>
                              </tr>
                              <tr>
                                 <td>Select</td>
                                 <td class="<?php echo $user_number_class; ?>"><?php echo $user['value']; ?></td>
                              </tr>
                              <tr>
                                 <td>Status</td>
                                 <td><?php echo $user_status; ?></td>
                              </tr>
                              <tr>
                                 <td>Create Time</td>
                                 <td><?php echo $user['date']; ?></td>
                              </tr>
                           </table>
                        </div>
                     </div>
                     <!-- Accordion card -->
                     <?php }
                        }else{
                        echo '<p class="no_record text-center">No Record Found.</p>';
                        } ?>
                  </div>
               </div>
            </div>
            <div class="tab-pane fade" id="Bcone">
               <div class="tabHeadContent">
                  <div class="period">
                     Period
                     <p class="next_Tokens" data-number="<?php echo $next_val; ?>"><?php echo $current_period_value; ?></p>
                  </div>
                  <div class="count">
                     Count
                     <p id="time" class="timing_changes">03:00</p>
                  </div>
                  <div class="choose_color">
                     <div class="button button_green join_green col-xs-4 float-left join_button">Join Green</div>
                     <div class="button button_violet join_violet col-xs-4 join_button">Join Violet</div>
                     <div class="button button_red join_red col-xs-4 float-right join_button">Join Red</div>
                  </div>
                  <div class="choose_number">
                     <div class="button button_redmix col-xs-2 join_button">0</div>
                     <div class="button button_green col-xs-2 join_button">1</div>
                     <div class="button button_red col-xs-2 join_button">2</div>
                     <div class="button button_green col-xs-2 join_button">3</div>
                     <div class="button button_red col-xs-2 join_button">4</div>
                     <div class="button button_greenmix col-xs-2 join_button">5</div>
                     <div class="button button_red col-xs-2 join_button">6</div>
                     <div class="button button_green col-xs-2 join_button">7</div>
                     <div class="button button_red col-xs-2 join_button">8</div>
                     <div class="button button_green col-xs-2 join_button">9</div>
                  </div>
                  <input type="hidden" id="option_value" value="3" class="option_value">
               </div>
               <div class="tableSec">
                  <p class="tableHeading">Bcone Record</p>
                  <div class="progress" style="height:2px;">
                     <div class="progress-bar" style="width:100%;"></div>
                  </div>
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Period</th>
                           <th>Price</th>
                           <th>Number</th>
                           <th>Result</th>
                        </tr>
                     </thead>
                     <tbody id="bcone_record">
                        <?php  				
                           $result = get_period_list('bcone_record_list', 15);
                           while($user = mysqli_fetch_assoc($result)) {  
                           	if(($user['number'] ==0)||($user['number'] ==5)){
                           		$violet_div= '<span class="resultCircle violet"></span>';
                           	}else{
                           		$violet_div='';
                           	}
                           	if($user['number'] % 2 ==0)	
                           	{
                           		$numberclass= 'redText';
                           		$round_class='red';
                           	}else{
                           		$numberclass='greenText';
                           		$round_class='green';
                           	}
                             ?>
                        <tr>
                           <td><?php echo $user['period']; ?></td>
                           <td><?php echo $user['price']; ?></td>
                           <td class="<?php echo $numberclass; ?>"><?php echo $user['number']; ?></td>
                           <td><span class="resultCircle <?php echo $round_class; ?>"></span> <?php echo $violet_div; ?></td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
               <div class="parityRecord">
                  <p class="recordHeading">
                     My Bcone Record
                  </p>
                  <div class="progress" style="height:2px;">
                     <div class="progress-bar" style="width:100%;"></div>
                  </div>
                  <!--Accordion wrapper-->
                  <div class="accordion md-accordion bcone_accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                     <?php
                        if((mysqli_num_rows($my_bcone_list))>0){
                        	while($user = mysqli_fetch_assoc($my_bcone_list)) { 
                        	
                        			$user_status = $user['status'];
                        			$period = $user['period'];
                        			$profit = $user['profit'];
                        			$servicefee = $user['servicefee'];
                        			$amount = $user['amount'];
                        			
                        			$service_fee =($amount/100)*2;
                        			$profit_amount = $amount - $service_fee;
                        			if(empty($period)){
                        				$period = $current_period_value;
                        				
                        			}
                        			
                        			if(empty($user_status)){
                        				$user_status = 'Wait';
                        			}
                        			
                        			if($user_status == 'success'){
                        				$user_class='greenText';
                        				$user_total = $profit + $amount;
                        			}else if($user_status == 'fail'){
                        				$user_class='redText';
                        				$user_total = $profit_amount;
                        			}else{
                        				$user_class='yellowText';
                        				$user_total = '';
                        			}
                        			if($user['number'] % 2 ==0)	
                        			{
                        				$numberclass= 'redText';
                        				$round_class='red';
                        				$result_value_section ='red';
                        			}else{
                        				$numberclass='greenText';
                        				$round_class='green';
                        				$result_value_section ='green';
                        			}
                        			
                        			if(($user['number'] ==0)||($user['number'] ==5)){
                        				$violet_value= 'yes';
                        			}else{
                        				$violet_value='no';
                        			}
                        			
                        			$random_id = uniqid();
                        			
                        			if(is_numeric($user['value'])){
                        				if(($user['value']) % 2 ==0){
                        					$user_number_class ='redText';
                        					$user_result_value ='red';
                        				}else{
                        					$user_number_class='greenText';
                        					$user_result_value ='green';
                        				}
                        				
                        			}else{
                        				if($user['value'] =='green'){
                        					$user_number_class='greenText';
                        					$user_result_value ='green';
                        				}else if($user['value'] == 'violet'){
                        					$user_number_class='violetText';
                        					$user_result_value ='violet';
                        				}else{
                        					$user_number_class ='redText';
                        					$user_result_value ='red';
                        				}
                        			}					
                        			if(is_numeric($user['value'])){
                        				if($user['value'] == $user['number']){
                        					$user_result = 'success';
                        					$result_value=$user['number'];
                        				}else{
                        					$user_result = 'fail';
                        					$result_value=$user['number'];
                        				}
                        			}else{
                        				if($result_value_section == $user_result_value){
                        					$user_result = 'success';
                        				}else{
                        					$user_result = 'fail';
                        				}
                        			}
                        			
                        			if(($violet_value =='yes')&&($user_result_value =='violet')){
                        				$user_result ='success';
                        			}				
                        ?>	  
                     <div class="card">
                        <!-- Card header -->
                        <div class="card-header" role="tab" id="headingOne1">
                           <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne_<?php echo $random_id; ?>" aria-expanded="true"
                              aria-controls="collapseOne_<?php echo $random_id; ?>">
                              <p class="mb-0">
                                 <?php echo $period; ?> - <span class="<?php echo $user_class; ?>"> <?php echo ucwords($user_status); ?> 
                                 <?php	if($user_status != 'wait')  echo '-'.$user_total; ?></span>
                                 <i class="fas fa-angle-down rotate-icon"></i>
                              </p>
                           </a>
                        </div>
                        <!-- Card body -->
                        <div id="collapseOne_<?php echo $random_id; ?>" class="collapse" role="tabpanel" aria-labelledby="headingOne1"
                           data-parent="#accordionEx">
                           <table>
                              <tr>
                                 <td>Period</td>
                                 <td><?php echo $user['period']; ?></td>
                              </tr>
                              <tr>
                                 <td>Contract Money</td>
                                 <td><?php echo $user['amount']; ?></td>
                              </tr>
                              <tr>
                                 <td>Contract Count</td>
                                 <td>1</td>
                              </tr>
                              <tr>
                                 <td>Delivery</td>
                                 <td><?php echo $profit; ?></td>
                              </tr>
                              <tr>
                                 <td>Fee</td>
                                 <td><?php echo $servicefee; ?></td>
                              </tr>
                              <tr>
                                 <td>Result</td>
                                 <td class="<?php echo $numberclass; ?>"><?php echo $result_value_section; ?></td>
                              </tr>
                              <tr>
                                 <td>Select</td>
                                 <td class="<?php echo $user_number_class; ?>"><?php echo $user['value']; ?></td>
                              </tr>
                              <tr>
                                 <td>Status</td>
                                 <td><?php echo $user_status; ?></td>
                              </tr>
                              <tr>
                                 <td>Create Time</td>
                                 <td><?php echo $user['date']; ?></td>
                              </tr>
                           </table>
                        </div>
                     </div>
                     <!-- Accordion card -->
                     <?php }
                        }else{
                        echo '<p class="no_record text-center">No Record Found.</p>';
                        } ?>
                  </div>
                  <!-- Accordion wrapper -->
               </div>
            </div>
            <div class="tab-pane fade" id="Emerd">
               <div class="tabHeadContent">
                  <div class="period">
                     Period
                     <p class="next_Tokens" data-number="<?php echo $next_val; ?>"><?php echo $current_period_value; ?></p>
                  </div>
                  <div class="count">
                     Count
                     <p id="time" class="timing_changes">03:00</p>
                  </div>
                  <div class="choose_color">
                     <div class="button button_green join_green col-xs-4 float-left join_button">Join Green</div>
                     <div class="button button_violet join_violet col-xs-4 join_button">Join Violet</div>
                     <div class="button button_red join_red col-xs-4 float-right join_button">Join Red</div>
                  </div>
                  <div class="choose_number">
                     <div class="button button_redmix col-xs-2 join_button">0</div>
                     <div class="button button_green col-xs-2 join_button">1</div>
                     <div class="button button_red col-xs-2 join_button">2</div>
                     <div class="button button_green col-xs-2 join_button">3</div>
                     <div class="button button_red col-xs-2 join_button ">4</div>
                     <div class="button button_greenmix col-xs-2 join_button">5</div>
                     <div class="button button_red col-xs-2 join_button">6</div>
                     <div class="button button_green col-xs-2 join_button">7</div>
                     <div class="button button_red col-xs-2 join_button">8</div>
                     <div class="button button_green col-xs-2 join_button">9</div>
                  </div>
                  <input type="hidden" id="option_value" value="4" class="option_value">
               </div>
               <div class="tableSec">
                  <p class="tableHeading">Emerd Record</p>
                  <div class="progress" style="height:2px;">
                     <div class="progress-bar" style="width:100%;"></div>
                  </div>
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Period</th>
                           <th>Price</th>
                           <th>Number</th>
                           <th>Result</th>
                        </tr>
                     </thead>
                     <tbody id="emerd_record">
                        <?php  				
                           $result = get_period_list('emerd_record_list', 15);
                           while($user = mysqli_fetch_assoc($result)) {  
                           	if(($user['number'] ==0)||($user['number'] ==5)){
                           		$violet_div= '<span class="resultCircle violet"></span>';
                           	}else{
                           		$violet_div='';
                           	}
                           	if($user['number'] % 2 ==0)	
                           	{
                           		$numberclass= 'redText';
                           		$round_class='red';
                           	}else{
                           		$numberclass='greenText';
                           		$round_class='green';
                           	}
                             ?>
                        <tr>
                           <td><?php echo $user['period']; ?></td>
                           <td><?php echo $user['price']; ?></td>
                           <td class="<?php echo $numberclass; ?>"><?php echo $user['number']; ?></td>
                           <td><span class="resultCircle <?php echo $round_class; ?>"></span> <?php echo $violet_div; ?></td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
               <div class="parityRecord">
                  <p class="recordHeading">
                     My Emerd Record
                  </p>
                  <div class="progress" style="height:2px;">
                     <div class="progress-bar" style="width:100%;"></div>
                  </div>
                  <!--Accordion wrapper-->
                  <div class="accordion md-accordion emerd_accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                     <?php
                        if((mysqli_num_rows($my_emerd_list))>0){
                        	while($user = mysqli_fetch_assoc($my_emerd_list)) { 
                        	
                        			$user_status = $user['status'];
                        			$period = $user['period'];
                        			$profit = $user['profit'];
                        			$servicefee = $user['servicefee'];
                        			$amount = $user['amount'];
                        			
                        			$service_fee =($amount/100)*2;
                        			$profit_amount = $amount - $service_fee;
                        			if(empty($period)){
                        				$period = $current_period_value;
                        				
                        			}
                        			
                        			if(empty($user_status)){
                        				$user_status = 'Wait';
                        			}
                        			
                        			if($user_status == 'success'){
                        				$user_class='greenText';
                        				$user_total = $profit + $amount;
                        			}else if($user_status == 'fail'){
                        				$user_class='redText';
                        				$user_total = $profit_amount;
                        			}else{
                        				$user_class='yellowText';
                        				$user_total = '';
                        			}
                        			if($user['number'] % 2 ==0)	
                        			{
                        				$numberclass= 'redText';
                        				$round_class='red';
                        				$result_value_section ='red';
                        			}else{
                        				$numberclass='greenText';
                        				$round_class='green';
                        				$result_value_section ='green';
                        			}
                        			
                        			if(($user['number'] ==0)||($user['number'] ==5)){
                        				$violet_value= 'yes';
                        			}else{
                        				$violet_value='no';
                        			}
                        			
                        			$random_id = uniqid();
                        			
                        			if(is_numeric($user['value'])){
                        				if(($user['value']) % 2 ==0){
                        					$user_number_class ='redText';
                        					$user_result_value ='red';
                        				}else{
                        					$user_number_class='greenText';
                        					$user_result_value ='green';
                        				}
                        				
                        			}else{
                        				if($user['value'] =='green'){
                        					$user_number_class='greenText';
                        					$user_result_value ='green';
                        				}else if($user['value'] == 'violet'){
                        					$user_number_class='violetText';
                        					$user_result_value ='violet';
                        				}else{
                        					$user_number_class ='redText';
                        					$user_result_value ='red';
                        				}
                        			}					
                        			if(is_numeric($user['value'])){
                        				if($user['value'] == $user['number']){
                        					$user_result = 'success';
                        					$result_value=$user['number'];
                        				}else{
                        					$user_result = 'fail';
                        					$result_value=$user['number'];
                        				}
                        			}else{
                        				if($result_value_section == $user_result_value){
                        					$user_result = 'success';
                        				}else{
                        					$user_result = 'fail';
                        				}
                        			}
                        			
                        			if(($violet_value =='yes')&&($user_result_value =='violet')){
                        				$user_result ='success';
                        			}				
                        ?>	  
                     <div class="card">
                        <!-- Card header -->
                        <div class="card-header" role="tab" id="headingOne1">
                           <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne_<?php echo $random_id; ?>" aria-expanded="true"
                              aria-controls="collapseOne_<?php echo $random_id; ?>">
                              <p class="mb-0">
                                 <?php echo $period; ?> - <span class="<?php echo $user_class; ?>"> <?php echo ucwords($user_status); ?> 
                                 <?php	if($user_status != 'wait')  echo '-'.$user_total; ?></span>
                                 <i class="fas fa-angle-down rotate-icon"></i>
                              </p>
                           </a>
                        </div>
                        <!-- Card body -->
                        <div id="collapseOne_<?php echo $random_id; ?>" class="collapse" role="tabpanel" aria-labelledby="headingOne1"
                           data-parent="#accordionEx">
                           <table>
                              <tr>
                                 <td>Period</td>
                                 <td><?php echo $user['period']; ?></td>
                              </tr>
                              <tr>
                                 <td>Contract Money</td>
                                 <td><?php echo $user['amount']; ?></td>
                              </tr>
                              <tr>
                                 <td>Contract Count</td>
                                 <td>1</td>
                              </tr>
                              <tr>
                                 <td>Delivery</td>
                                 <td><?php echo $profit; ?></td>
                              </tr>
                              <tr>
                                 <td>Fee</td>
                                 <td><?php echo $servicefee; ?></td>
                              </tr>
                              <tr>
                                 <td>Result</td>
                                 <td class="<?php echo $numberclass; ?>"><?php echo $result_value_section; ?></td>
                              </tr>
                              <tr>
                                 <td>Select</td>
                                 <td class="<?php echo $user_number_class; ?>"><?php echo $user['value']; ?></td>
                              </tr>
                              <tr>
                                 <td>Status</td>
                                 <td><?php echo $user_status; ?></td>
                              </tr>
                              <tr>
                                 <td>Create Time</td>
                                 <td><?php echo $user['date']; ?></td>
                              </tr>
                           </table>
                        </div>
                     </div>
                     <!-- Accordion card -->
                     <?php }
                        }else{
                        echo '<p class="no_record text-center">No Record Found.</p>';
                        } ?>
                  </div>
                  <!-- Accordion wrapper -->
               </div>
            </div>
         </div>
      </div>
      <div class="bottom-tab row">
         <a class="col-3" href="#"><i class="fas fa-cart-arrow-down"></i>Shop</a>
         <a class="col-3"  href="#"><i class="fas fa-search"></i>Search</a>
         <a class="col-3 active"  href="index.php"><i class="fas fa-trophy"></i>Win</a>
         <a class="col-3"  href="myaccount.php"><i class="fas fa-user-circle"></i>Account</a>
      </div>
      <input type="hidden" value="<?php echo $user_id; ?>" id="user_id">
      <script type="text/javascript">
         $('.join_button').click(function(){
         	var this_number = $(this).attr('data-value');
         	var current_token= $('.next_Tokens').attr('data-number');
         	var option_value = $('.option_value').val(); 
         	var current_balance_user = $('#current_balance_user').val();
         	var amount = 10;
         	if(current_balance_user > amount){
         		$.ajax({
         					url: "color.php",
         					type: "post",
         					data: {this_number: this_number,current_token:current_token,option_value:option_value},
         					success: function (response) {
         						if(option_value == 1){
         							$('.parity_accordion').prepend(response);
         						}
         					   
         					},
         					error: function(jqXHR, textStatus, errorThrown) {
         					}
         		});			
         	}else{
         		
         	}
         			
         			
         
         });
         
         
         
         
         var x = setInterval(function() {
         	var current_seconds = $('.offer_in_remaining_seconds').val();
         	var countDownDate = new Date(current_seconds).getTime();
         	var now = new Date().getTime();
         	var distance = countDownDate - now;
         	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
         	var seconds = Math.floor((distance % (1000 * 60)) / 1000);
         	if(seconds <10){
         	  seconds='0'+seconds;
         	}
         	if(minutes <10){
         	  minutes='0'+minutes;
         	}
            var current_token= $('.next_Tokens').attr('data-number');
         	var date = new Date;
         	var newminutes = parseInt($('.current_minutes').val());
         	var newhour = date.getHours();
         	
         	
         	newminutes = newminutes+3;
         	
         	
         	if(newminutes == 60){
         		newminutes=00;
         		newhour=newhour+1;
         	}
         	
         	if(newminutes < 0 ){
         		newminutes ='0'+newminutes;
         	}
          
         	$('.timing_changes').html(minutes+':'+seconds);
         	  
         	 var cur_date =  $('.current_date').val();
         	  
         	  if( seconds == 00){
				  
				  $.getJSON("http://localhost/mallnew/test_table.php?period=20210901005",
				   function(data) {
					 console.log(data);         
				   });
         		 /* var current_new_date = cur_date + ' '+newhour+':'+newminutes+':00';
         		  var current_hours_minutes = newhour+':'+newminutes+':00';
         		  var newminutes = newminutes;
         		  $('.offer_in_remaining_seconds').val(current_new_date);
         		  $('.current_minutes').val(newminutes);
         		  
         		  
         			var user_id =$('#user_id').val();
         			var current_Tokens= parseInt(current_token); 			
         			
         		  $.ajax({
         				url: "functions.php",
         				type: "post",
         				data: {current_token: current_Tokens,user_id:user_id,current_hours_minutes:current_hours_minutes,newminutes:newminutes},
         				success: function (response) {
         					
         				var results =JSON.parse(response);
         				var array = $.map(results, function(value, index) {
         					var current_date_number = value.current_date_number;
         					var next_date_number = value.next_date_number;
         					var current_value = value.current_value;
         					var current_number = value.current_number;
         					var current_class = value.current_class;
         					var current_price = value.current_price;
         					var next_token = value.next_token;
         					var table_name = value.table_name;
         					var round_div;
         					if(current_value =='odd'){
         						round_div ='<span class="resultCircle green"></span>';
         					}else{
         						round_div ='<span class="resultCircle red"></span>';
         					}
         					
         					var previous_token_div = '<tr><td>'+current_date_number+'</td><td>'+current_price+'</td><td class="'+current_class+'">'+current_number+'</td><td>'+round_div+'</td></tr>';
         					if(table_name == 0){
         						$('#parity_record').prepend(previous_token_div);
         					}
         					if(table_name == 1){
         						$('#sapre_record').prepend(previous_token_div);
         					}
         					if(table_name == 2){
         						$('#bcone_record').prepend(previous_token_div);
         					}
         					if(table_name == 3){
         						$('#emerd_record').prepend(previous_token_div);
         					}
         					
         					$('.next_Tokens').attr('data-number',next_token);
         					$('.next_Tokens').html(next_date_number);
         				});
         				
         				   
         				},
         				error: function(jqXHR, textStatus, errorThrown) {
         				   console.log(textStatus, errorThrown);
         				}
         			});	*/
         			
         	  }
         	
          
         }, 1000);
		 
		/* $(document).ready(function(){
			 $.getJSON("http://localhost/mallnew/test_table.php?period=20210901002",
			   function(data) {
				 console.log(data);         
			   });
			 
		 }); */
      </script>
      <!-- Join Color Modal -->
      <div class="modal fade" id="joinModal" tabindex="-1" role="dialog" aria-labelledby="joinModal" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Join Green</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="title">
                  <span>Contract Money:</span>
                  <div class="form-check-inline">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="optradio" checked>10
                     </label>
                  </div>
                  <div class="form-check-inline">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="optradio">100
                     </label>
                  </div>
                  <div class="form-check-inline">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="optradio">1000
                     </label>
                  </div>
                  <div class="form-check-inline">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="optradio">10000
                     </label>
                  </div>
               </div>
               <div class="modal-body">
                  <span>Number: </span>
                  <div class="def-number-input number-input safari_only">
                     <button onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="minus"></button>
                     <input class="quantity" min="0" name="quantity" value="1" type="number">
                     <button onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus"></button>
                  </div>
                  <span class="totalText">Total Contract Money is : <b>10</b></span>
               </div>
               <div class="modal-footer">
                  <div class="custom-control custom-checkbox">
                     <input type="checkbox" class="custom-control-input" id="customCheck" checked>
                     <label class="custom-control-label" for="customCheck">I agree <b>PreSale Rule</b></label>
                  </div>
                  <button type="button" class="button button_green" data-dismiss="modal">Submit</button>
                  <button type="button" class="button button_grey" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Rule of Guess</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <p class="title">3 minutes 1 issue, 2 minutes and 30 seconds to order, 30 seconds to show the lottery result. It opens all day. The total number of trade is 480 issues</p>
               <div class="modal-body">
                  <p>If you spend 100 to trade, after deducting 2 service fee, your contract amount is 98:</p>
                  <ol>
                     <li><b class="greenText"> JOIN GREEN:</b> If the result shows 1,3,7,9, you will get (98*2) 196.</li>
                     <li><b class="redText">JOIN RED:</b> If the result shows 2,4,6,8, you will get (98*2) 196; If the result shows 0, you will get (98*1.5) 147.</li>
                     <li><b class="violetText">JOIN VIOLET:</b> If the result shows 0 or 5, you will get (98*4.5) 441.</li>
                     <li><b>SELECT NUMBER:</b> If the result is the same as the number you selected, you will get(98*9)882.</li>
                  </ol>
               </div>
               <div class="modal-footer">
                  <button type="button" class="button button_grey" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
   </body>
</html>