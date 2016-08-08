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
			
			</div>
			
		</div>
		
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					Calls scheduled last week
			</h3>
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
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					Received calls in the past week
			</h3>
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
			</div>
			
		</div>
		
				<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					Acton items generated in the past week
			</h3>
			<table class="table table-striped table-bordered table-hover">
					<thead>
					   <tr>
					   	   <th> Action ID </th>
						   <th> Checklist ID </th>
						   <th> Reference Week </th>
						   <th> Reference Description </th>
						   <th> Action Description </th>
						   						   
					   </tr>
					 </thead>
					 <tbody>
					<?php 
					foreach($action_items as $val)
					{
						foreach($val as $val1){
					?>
					<tr>
					<td> {{$val1->i_action_id }} </td>
					<td> {{$val1->checklist_id}} </td>
					<td> {{$val1->i_reference_week}} </td>
					<td> {{$val1->v_reference_descrip}} </td>
					<td> {{$val1->v_action_descrip}} </td>
					</tr>
					</tbody>
					 
					 <?php 
						}
					}
					 ?>
					 
			</table>
			</div>
			
		</div>
		
	</div>
</div>
</body>
</html>

