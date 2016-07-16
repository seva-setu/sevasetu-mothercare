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
	   <div class = "row">
			<div class="col-lg-10 col-md-10">
			<h4>
				{{ trans('routes.intromct') }}
			</h4>			
			</div>
		</div>
		<hr />
		<div class="row">
			<div class="col-lg-6 col-md-6">
				<h3>
					{{ trans('routes.assigned') }}
				</h3>
				<div class="panel-group" id="accordion">
					<?php
					$i = 0;
					foreach($data as $value){
					?>
						<div class="panel panel-default">
						   <div class="panel-heading">
							  <h4 class="panel-title">
								 <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" class="collapsed">
								 {{ $value->name }} from {{ $value->village_name }}
								 </a>
							  </h4>
						   </div>
						   <div id="collapse<?php echo $i; ?>" class="panel-collapse collapse" style="height: 0px;">
							  <div class="panel-body">
								
									<b>{{ trans('routes.name') }}</b>: {{ $value->name }}
								<br/>
									<b>{{ trans('routes.village') }}</b>: {{ $value->village_name }}
								<br/>
									<b>{{ trans('routes.name') }}</b>: {{ $value->phone_number }}
								<br/>
									<b>{{ trans('routes.fieldworkername') }}</b>: {{ $value->field_worker_name }} 
								<br/>
									<b>{{ trans('routes.fieldworkernumber') }}</b>: {{ $value->field_worker_number }} 
							  </div>
						   </div>
						</div>
					<?php 
						$i++;
					}
					?>
				</div>
				<h4>
					<a href="<?php echo url().'/admin/schedule/'.Hashids::encode($userinfo['role_id']);?>" class="btn btn-primary"> 
					<b>{{trans('routes.addmother')}}</b>
					</a>
				</h4>
			</div>
			<!-- end main content in the page -->
			
		</div>
	</div>
</div>
@include('template/admin_jsscript')
</body>





