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
		<h3> <span>##</span> Mother's Details</h3>
		</br>
			<div class="col-lg-10 col-md-10">
			<?php 
			if(isset($data) && !empty($data)){
			?>
			<table class="table table-striped table-bordered table-hover">
					<thead>
					   <tr>
					   	   <th> {{ trans('adminDashboard.mothername') }} </th>
						   <th> {{ trans('routes.phonenumber') }} </th>
						   <th> {{ trans('routes.husbandname') }} </th>
						   <th> {{ trans('routes.village') }} </th>
						   <th> {{ trans('routes.fieldworker') }} </th>
						   <th> {{ trans('adminDashboard.lastcall') }} </th>
						   <th> {{ trans('adminDashboard.completedcalls') }} </th>
						   <th> {{ trans('adminDashboard.pendingcalls') }} </th>
					   </tr>
					 </thead>
					 <tbody>
					<?php 
					foreach($data as $val)
					{
						
					?>
					<tr>
					<td> {{$val->name }} </td>
					<td> {{$val->phone_number }} </td>					
					<td> {{$val->husband_name }} </td>
					<td> {{$val->village_name }} </td>
					<td> {{$val->field_worker_name }} </td>
					<td> {{ $x[$val->b_id]['last_call'] }} </td>
					<td> {{ $x[$val->b_id]['completed_calls'] }} </td>
					<td> {{ $x[$val->b_id]['pending_calls'] }} </td>
					</tr>
						 <?php 
						
					}
			?>
					 </tbody>
					 
				
			
			</table>
			 <?php 
						
					}
			?>
			</div>
		</div>
		
		
			
		
	</div>
</div>
</body>
</html>

