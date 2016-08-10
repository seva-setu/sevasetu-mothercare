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
	@if(Session::get('user_logged')['v_role'] == 2)
		@include('template/callchampion_sidebar')
	@endif
	@if(Session::get('user_logged')['v_role'] == 1)
		@include('template/admin_sidebar')
	@endif
@endif

<div id="page-wrapper" >
	<div id="page-inner">
		<div class="row">
			<div class="col-lg-10 col-md-10">
				<h3>
					{{ trans('routes.masterchecklist') }}
				</h3>
					{{ trans('routes.masterchecklist_descrip') }}
				<table class="table table-striped table-bordered table-hover">
					<thead>
					   <tr>
						  <th>{{ trans('routes.num') }}</th>
						  <th>{{ trans('routes.weekofpregnancy') }}</th>
						  <th>{{ trans('routes.stage') }}</th>
						  <th>{{ trans('routes.action') }}</th>
					   </tr>
					</thead>
					<tbody>
						<?php
						/////// Counts to manage row-count /////////
						$current_val = -1;
						foreach($checklist_master as $value){
							if($current_val != $value->i_reference_week){
								$current_val = $value->i_reference_week;
							}
							else{
								$value->i_reference_week = '';
								$value->v_reference_descrip = '';
							}
						}
						/////// Counts to manage row-count /////////
						foreach($checklist_master as $value){
						?>
							<tr>
							<td>{{ $value->checklist_id }}</td>
							<td>{{ $value->i_reference_week }}</td>
							<td>{{ $value->v_reference_descrip }}</td>
							<td>{{ $value->v_action_descrip }}</td>
							</tr>
						<?php 
						}
						?>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@include('template/admin_jsscript')
</body>





