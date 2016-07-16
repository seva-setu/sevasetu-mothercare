<?php 
$cstartdate = date('d/m/Y',strtotime('last sunday'));
$cenddate = date('d/m/Y',strtotime('this saturday'));
$state=isset($state) && $state!="all"?$state:"";
$city=isset($city) && $city!="all"?$city:"";
$taluka=isset($taluka) && $taluka!="all" ?$taluka:"";

$userinfo=Session::get('user_logged');
$startdate=isset($startdate)?date('d/m/Y',strtotime($startdate)):"";
$enddate=isset($enddate)?date('d/m/Y',strtotime($enddate)):"";
$datelable="";
if($startdate!="" && $enddate!=""){
	$datelable=$startdate." to ".$enddate;
}	 
$languagedata= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();

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
@include('template/admin_header')
@include('template/admin_sidebar')
@include('template/script_multilanguage')

<div id="page-wrapper" >
	<div id="page-inner">
		<div class="row">
			<div class="col-lg-9 col-md-9">
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
						$current_val = $checklist_master[0]->i_reference_week;
						$ref_week_counts = array();
						$count = 0;
						foreach($checklist_master as $value){
							if($current_val != $value->i_reference_week){
								$ref_week_counts []= $count;
								$count = 1;
								$current_val = $value->i_reference_week;
							}
							else
								$count++;
						}
						$ref_week_counts []= $count;
						$count = 0;
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





