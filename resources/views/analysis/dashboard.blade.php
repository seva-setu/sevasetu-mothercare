<?php 
	$userinfo = Session::get('user_logged');
?>
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
@include('template/analysis_sidebar')
@if(Session::has('user_logged'))
	@include('template/admin_header')
@endif

<div id="page-wrapper" >
	<div id="page-inner">
		
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					{{ trans('adminDashboard.callchampions_week') }} <?php echo date("d-m-Y", $datestart); ?> to <?php echo date("d-m-Y", $dateend); ?>
			</h3>
			<?php 
			if(isset($call_details) && !empty($call_details)){
			?>
			<table class="table table-striped table-bordered table-hover">
					<thead>
					   <tr>
					   	   <th> {{ trans('adminDashboard.callID') }} </th>
						   <th> {{ trans('adminDashboard.callchampionname') }} </th>
						   <th> {{ trans('adminDashboard.mothername') }} </th>
						   <th> {{ trans('adminDashboard.dateofcall') }} </th>
						   <th> {{ trans('adminDashboard.motherphno') }} </th>
						   <th> {{ trans('adminDashboard.reminderstatus') }} </th>
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
					<td> {{date('d-m-Y', strtotime($val1->dt_intervention_date))}} </td>
					<td> {{$val1->v_phone_number}} </td>
					<td> {{$val1->reminder_status}} </td>
					</tr>
						 <?php 
						
					}
			?>
					<tr>
					<td colspan="6"><center>{!! $call_details->render() !!}</center></td>
				</tr>
					 </tbody>
					 
				
			
			</table>
			<?php 
			}
			else {
					 ?>
					 <h4>{{ trans('adminDashboard.nocalls_week') }}</h4>
					 <?php 
			}
					 ?>
			</div>
			
		</div>
		
		
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<h3>
					{{ trans('adminDashboard.stats_week') }} <?php echo date("d-m-Y", $datestart); ?> to <?php echo date("d-m-Y", $dateend); ?>
			</h3>
			<table class="table table-striped table-bordered table-hover">
			<tr>
			<td> {{ trans('adminDashboard.numberofcalls') }} </td>
			<?php if(isset($totalcalls)) { ?>
			<td> {{$totalcalls[0]->count}} </td>
			<?php 
					 }
					 else {
					 ?>
			<td> {{ trans('adminDashboard.dataunavailable') }} </td>
					 <?php
					 }
					 ?>
			</tr>
			<tr>
			<td> {{ trans('adminDashboard.numberofmothers') }} </td>
			<?php if(isset($mothersassigned)) { ?>
			<td> {{$mothersassigned[0]->count}} </td>
			<?php 
					 }
					 else {
					 ?>
			<td> {{ trans('adminDashboard.dataunavailable') }} </td>
					 <?php
					 }
					 ?>
			</tr>
			<tr>
			<td> {{ trans('adminDashboard.averagepermother') }} </td>
			<?php if(isset($averagepermother)) { ?>
			<td> {{$averagepermother[0]->Average}} </td>
			<?php 
					 }
					 else {
					 ?>
			<td> {{ trans('adminDashboard.dataunavailable') }} </td>
					 <?php
					 }
					 ?>
			</tr>
			<tr>
			<td> {{ trans('adminDashboard.callsattempt1') }}  </td>
			<?php if(isset($callsattemptequal1)) { ?>
			<td> {{$callsattemptequal1[0]->count}} </td>
			<?php 
					 }
					 else {
					 ?>
			<td> {{ trans('adminDashboard.dataunavailable') }} </td>
					 <?php
					 }
					 ?>
			</tr>
			<tr>
			<td> {{ trans('adminDashboard.callsattempt2') }} </td>
			<?php if(isset($callsattemptequal2)) { ?>
			<td> {{$callsattemptequal2[0]->count}} </td>
			<?php 
					 }
					 else {
					 ?>
			<td> {{ trans('adminDashboard.dataunavailable') }} </td>
					 <?php
					 }
					 ?>
			</tr>
			<tr>
			<td> {{ trans('adminDashboard.callsgt2') }} </td>
			<?php if(isset($callsattemptgt2)) { ?>
			<td> {{$callsattemptgt2[0]->count}} </td>
			<?php 
					 }
					 else {
					 ?>
			<td> {{ trans('adminDashboard.dataunavailable') }} </td>
					 <?php
					 }
					 ?>
			</tr>
			<tr>
			<td> {{ trans('adminDashboard.mothersincorrectphno') }} </td>
			<?php if(isset($incorrectphno)) { ?>
			<td> {{$incorrectphno[0]->count}} </td>
			<?php 
					 }
					 else {
					 ?>
			<td> {{ trans('adminDashboard.dataunavailable') }} </td>
					 <?php
					 }
					 ?>
			</tr>
			<tr>
			<td> {{ trans('adminDashboard.mothersnotreachable') }} </td>
			<?php if(isset($notreachable)) { ?>
			<td> {{$notreachable[0]->count}} </td>
			<?php 
					 }
					 else {
					 ?>
			<td> {{ trans('adminDashboard.dataunavailable') }} </td>
					 <?php
					 }
					 ?>
			</tr>
			<tr>
			<td> {{ trans('adminDashboard.mothersincorrectdeliverydate') }} </td>
			<?php if(isset($incorrectdeliverydate)) { ?>
			<td> {{$incorrectdeliverydate[0]->count}} </td>
			<?php 
					 }
					 else {
					 ?>
			<td> {{ trans('adminDashboard.dataunavailable') }} </td>
					 <?php
					 }
					 ?>
			</tr>
			<tr>
			<td> {{ trans('adminDashboard.numberactionitems') }} </td>
			<?php if(isset($actionitems)) { ?>
			<td> {{$actionitems[0]->count}} </td>
			<?php 
					 }
					 else {
					 ?>
			<td> {{ trans('adminDashboard.dataunavailable') }} </td>
					 <?php
					 }
					 ?>
			</tr>
			</table>
			</div>
		</div>
		
		
			
		
	</div>
</div>
</body>
</html>

