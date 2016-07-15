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
		<?php  $not_found = 0;
			if(!isset($not_found)){ ?>
			<div class="row">
				<div class="col-lg-10 col-md-10">
					<h2>{{ trans('routes.norecord') }}</h2>
					<hr/>
				</div>
			</div>
		<?php } else { ?>
		   <div class="row">
				<div class="col-lg-5 col-md-5">
					<h3><?php  echo "<b>Lohia Manohar Ram Kumar</b>"; ?></h3>
				</div>
				<div class="col-lg-5 col-md-5">
				</div>
				<div class="col-lg-5 col-md-5">
					<h3>
					<?php  echo "<b>".trans('routes.phonenumber')."</b>: "; 
							echo "12312312312";
					?>
					</h3>
				</div>
		   </div>
			<hr />
			<div class="row">
				<div class="col-lg-5 col-md-5">
					<?php echo "<b>".trans('routes.expecteddate')."</b>: ";
						echo "12 June 2017";
					?>
					<br/>
					<?php  echo "<b>".trans('routes.husbandname')."</b>: ";
							echo "Rammanohar Lohia Kumar"
					?>
				</div>
				<div class="col-lg-7 col-md-7">
					<?php  echo "<b>".trans('routes.village')."</b>: ";
							echo "Musohri Tola Bhusola Danapur"
					?>
					<br/>
					<?php  echo "<b>".trans('routes.fieldworkername')."</b>: ";
							echo "Lohia Manohar Ram Kumar"
					?>
				</div>
			</div>
			<hr />
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<h4><b>
					<?php 	echo "#"."534: ";
							echo " ".trans('routes.thiscallscheduled')." ";
							echo "12 June 2016";
					?>
					<?php 
					// Logic for checking whether this call is done or not 
						$call_done = true;
						if($call_done){ 
					?>
						<span class = "badge"> 
							<h5><b>
							{{ trans('routes.callcompleted') }}
							</b></h5>
						</span>
					<?php } ?>			
					</b></h4>					
					<ul class="nav nav-tabs">
						<li class="active"><a href="#notes" data-toggle="tab"> <b> {{ trans('routes.notes') }} </b></a>
						</li>
						<li><a href="#actionitem" data-toggle="tab"> <b> {{ trans('routes.actionitem') }} </b></a>
						</li>
						<li class=""><a href="#profile" data-toggle="tab"> <b> {{ trans('routes.profile') }} </b></a>
						</li>
						<li class=""><a href="#previouscalls" data-toggle="tab"> <b>{{ trans('routes.callscompleted') }} </b> </a>
						</li>
						<li class=""><a href="#nextcall" data-toggle="tab"> <b>{{ trans('routes.callsscheduled') }} </b> </a>
						</li>
					 </ul>
				</div>
			</div>
		<?php } //END IF RECORD EMPTY CHECK ?>
	</div>
</div>

</body>
