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
					<h3><?php  echo "<b>Rani Jhansi Phulan Devi</b>"; ?></h3>
				</div>
				<div class="col-lg-5 col-md-5">
					<h3>
					<?php  echo "<b>".trans('routes.phonenumber')."</b>: "; 
							echo "12312312312";
					?>
					</h3>
				</div>
				<div class="col-lg-2 col-md-2">
					<h3><b>
					<span class="label label-success">
						<?php 	echo trans('routes.callid')."534"; ?>
					</span>
					</b></h3>
				</div>
		   </div>
			<hr />
			<div class="row">
			<h5>
				<div class="col-lg-5 col-md-5">
					<p>
					<?php echo "<b>".trans('routes.expecteddate')."</b>: ";
						echo "12 June 2017";
					?>
					</p>
					<p>
					<?php  echo "<b>".trans('routes.husbandname')."</b>: ";
							echo "Rammanohar Lohia Kumar"
					?>
					</p>
				</div>
				<div class="col-lg-7 col-md-7">
					<p>
					<?php  echo "<b>".trans('routes.village')."</b>: ";
							echo "Musohri Tola Bhusola Danapur"
					?>
					</p>
					<p>
					<?php  echo "<b>".trans('routes.fieldworkername')."</b>: ";
							echo "Lohia Manohar Ram Kumar"
					?>
					</p>
				</div>
			</h5>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-5">
					<h4><b>
					<span class="label label-primary">
					
					<?php 
							echo " ".trans('routes.thiscallscheduled')." ";
							echo "12 June 2016";
					?>
					</span>
					</b></h4>
				</div>
				<div class="col-lg-5 col-md-5">
					<h4><b>
					<?php 
					// Logic for checking whether this call is done or not 
						$call_done = false;
						if($call_done){ 
					?>
						
						<span class = "label label-danger"> 
							{{ trans('routes.callcompleted') }}
						</span>
						
					<?php } ?>
					</b></h4>
				</div>
			</div>
			<hr/>
			<div class ="row">
				<div class="col-lg-12 col-md-12">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#t_notes" data-toggle="tab"> <b> {{ trans('routes.notes') }} </b></a>
						</li>
						<li class=""><a href="#t_profile" data-toggle="tab"> <b> {{ trans('routes.profile') }} </b></a>
						</li>
						<li class=""><a href="#t_previouscalls" data-toggle="tab"> <b>{{ trans('routes.callscompletedscheduled') }} </b> </a>
						</li>
					 </ul>
					 <!-- BEGIN CODE FOR TABBED CONTENT -->
					 <div class="tab-content">
					 
					 <!-- BEGIN CODE FOR TAB "Notes from call" -->
                        <div class="tab-pane fade active in" id="t_notes">
							<!-- Begin section for action items -->
							<div class="col-lg-6 col-md-6">
								<h4><b>
								<span class = "label label-warning">
									{{trans('routes.actionitem')}}
								</span>
								</b></h4>
								<br/>
								<ul>
									<li> Talk to me</li>
									<li> Talk to you</li>
								</ul>
							
							</div>
							<!-- End section for action items -->
							<!-- Begin section for textarea -->
							<div class="col-lg-6 col-md-6">
							   <div class="form-group">
								  <label for="general_note">{{ trans('routes.notes_general')}}</label>
								  <textarea class="form-control" rows="5" id="general_note"></textarea>
								</div>
								<div class="form-group">
								  <label for="action_note">{{ trans('routes.notes_field')}}
								  <span class="label label-danger">Important</span>
								  </label>
								  <textarea class="form-control" rows="5" id="action_note"></textarea>
								</div>
								<a href="#" class="btn btn-primary">{{trans('routes.submitnote')}}</a>
							</div>
							<!-- End section for textarea -->
                        </div>
							
					<!-- BEGIN CODE FOR TAB "Action items" -->
						<div class="tab-pane fade" id="t_actionitem">
                           <p>
                              ACTION ITEM dolor sit amet, consectetur adipisicing elit eserunt mollit anim id est laborum.
                           </p>
                        </div>
					 </div>
					 
					 <!-- END CODE FOR TABBED CONTENT -->
					 
				</div>
			</div>
		<?php } //END IF RECORD EMPTY CHECK ?>
	</div>
</div>
@include('template/admin_jsscript')
</body>
