<!DOCTYPE html>
<html lang="en">

<head>
@include('template/admin_title')
@include('template/admin_cssscripta')
</head>
<style>
smallfont{
	font-size:10px;
}
</style>
<body>
@if(Session::has('user_logged'))
	@include('template/admin_header')
	@include('template/admin_sidebar')
@endif

<div id="page-wrapper" >
	<div id="page-inner">
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					Total unsuccessful calls : <?php echo $total_calls;?>
			</h3>
		
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					Call champions who did not make scheduled calls
			</h3>
			<?php 
			if(!empty($cc_not_called)){
			?>
			<table class="table table-striped table-bordered table-hover">
					<thead>
					   <tr>
					   	   <th> ID </th>
						   <th> Name </th>
					   </tr>
					 </thead>
					 <tbody>
					<?php 
					foreach($cc_not_called as $val)
					{
						foreach($val as $val1){
					?>
					<tr>
					<td> {{$val1->ID }} </td>
					<td> {{$val1->name}} </td>
					</tr>
					 </tbody>
					 
					 <?php 
						}
					}
			?>
			
			</table>
			<?php 
			}
			else {
					 ?>
					 <h4>No pending calls in the past week</h4>
					 <?php 
			}
					 ?>
			</div>
			
		</div>
		
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					Calls scheduled last week
			</h3>
			<?php 
			if(!empty($call_details)){
			?>
		    <table class="table table-striped table-bordered table-hover">
					<thead>
					   <tr>
					   	   <th> Due ID </th>
						   <th> Beneficiary ID </th>
						   <th> Call Champion ID </th>
						   <th> Action ID </th>
						   <th> Intervention Date</th>
						   <th> Reminder status </th>
					   </tr>
					 </thead>
					 <tbody>
					<?php 
					foreach($call_details as $val)
					{
						
					?>
					<tr>
					<td> {{$val->due_id }} </td>
					<td> {{$val->fk_b_id}} </td>
					<td> {{$val->fk_cc_id}} </td>
					<td> {{$val->fk_action_id}} </td>
					<td> {{$val->dt_intervention_date}} </td>
					<td> {{$val->reminder_status}} </td>
					</tr>
					 </tbody>
					 
					 <?php 
					}
					?>
					
			</table>
			<?php 
			}
			else {
					
					 ?>
					 <h4>No scheduled calls in the past week</h4>
					 <?php } 
					 ?>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					Received calls in the past week
			</h3>
			<?php 
			if(!empty($received_calls)){
			?>
			<table class="table table-striped table-bordered table-hover">
					<thead>
					   <tr>
					   	   <th> Report ID </th>
						   <th> Due ID </th>
						   <th> Beneficiary ID </th>
						   <th> Call Champion ID </th>
						   <th> Modify Date </th>
						   <th> Conversation </th>
						   <th> Action Items </th>
						   
					   </tr>
					 </thead>
					 <tbody>
					<?php 
					
					foreach($received_calls as $val)
					{
						foreach($val as $val1){
					?>
					<tr>
					<td> {{$val1->report_id }} </td>
					<td> {{$val1->fk_due_id}} </td>
					<td> {{$val1->fk_b_id}} </td>
					<td> {{$val1->fk_cc_id}} </td>
					<td> {{$val1->dt_modify_date}} </td>
					<td> {{$val1->t_conversation}} </td>
					<td> {{$val1->t_action_items}} </td>
					</tr>
					 </tbody>
					 
					 <?php 
						}
					}
					?>
					
			</table>
			<?php 
					}
					else {
					 ?>
					 <h4>No calls received in the past week</h4>
					 <?php 
					}
					 ?>
			</div>
			
		</div>
		
			
		
	</div>
</div>
</body>
</html>

