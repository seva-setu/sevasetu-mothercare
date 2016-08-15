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
					Call champions who have calls in the week from <?php echo date("j F Y", $datestart); ?> to <?php echo date("j F Y", $dateend); ?>
			</h3>
			<?php 
			if(!empty($call_details)){
			?>
			<table class="table table-striped table-bordered table-hover">
					<thead>
					   <tr>
					   	   <th> Call ID </th>
						   <th> Call Champion Name </th>
						   <th> Mother's Name </th>
						   <th> Date of Call </th>
						   <th> Mother's number </th>
					   </tr>
					 </thead>
					 <tbody>
					<?php 
					foreach($call_details as $val1)
					{
						
					?>
					<tr>
					<td> {{$val1->due_id }} </td>
					<td> {{$val1->c_name}} </td>
					<td> {{$val1->b_name}} </td>
					<td> {{$val1->dt_intervention_date}} </td>
					<td> {{$val1->v_phone_number}} </td>
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
					 <?php 
			}
					 ?>
			</div>
			
		</div>
		
		
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					Statistics for the week from <?php echo date("j F Y", $datestart); ?> to <?php echo date("j F Y", $dateend); ?>
			</h3>
			<table class="table table-striped table-bordered table-hover">
			<tr>
			<td> Number of calls made </td>
			<td> {{$totalcalls[0]->count}} </td>
			</tr>
			<tr>
			<td> Number of mothers assigned </td>
			<td> {{$mothersassigned[0]->count}} </td>
			</tr>
			<tr>
			<td> Average calls made per mother </td>
			<td> {{$averagepermother[0]->Average}} </td>
			</tr>
			<tr>
			<td> Number of scheduled calls connected in the first call attempt  </td>
			<td> {{$callsattemptequal1[0]->count}} </td>
			</tr>
			<tr>
			<td> Number of scheduled calls for which two attempts were made </td>
			<td> {{$callsattemptequal2[0]->count}} </td>
			</tr>
			<tr>
			<td> Number of scheduled calls for which more than 2 attempts were made </td>
			<td> {{$callsattemptgt2[0]->count}} </td>
			</tr>
			<tr>
			<td> Number of mothers whose phone numbers were marked incorrect </td>
			<td> {{$incorrectphno[0]->count}} </td>
			</tr>
			<tr>
			<td> Number of mothers who were not reachable or were out of network </td>
			<td> {{$notreachable[0]->count}} </td>
			</tr>
			<tr>
			<td> Number of action items generated </td>
			<td> {{$actionitems[0]->count}} </td>
			</tr>
			</table>
			</div>
		</div>
		
		
		
		
		
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					Call champions who have not made scheduled calls in the week from <?php echo date("j F Y", $datestart); ?> to <?php echo date("j F Y", $dateend); ?>
			</h3>
			<?php 
			if(!empty($cc_not_called)){
			?>
		    <table class="table table-striped table-bordered table-hover">
					<thead>
					   <tr>
					   	   <th> Due ID </th>
						   <th> Name </th>
						   <th> Call Champion Phone Number </th>
						   <th> Reminder Status </th>
						   
					   </tr>
					 </thead>
					 <tbody>
					<?php 
					foreach($cc_not_called as $val)
					{
						
					?>
					<tr>
					<td> {{$val->due_id }} </td>
					<td> {{$val->name}} </td>
					<td> {{$val->phno}} </td>					
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

