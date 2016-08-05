<?php 
	$user_details = Session::get('user_logged');
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
@include('template/callchampion_sidebar')
<div id="page-wrapper" >
	<div id="page-inner">
	   <div class="row">
			<div class="col-lg-6 col-md-6">
				<h3><?php  echo trans('routes.callsthisweek'); ?></h3>
			</div>
	   </div>
	   <hr/>
			<h4>
			<?php
				if(isset($due_list_thisweek) && count($due_list_thisweek) > 0){
					foreach($due_list_thisweek as $due){
			?>
						<div class="row">
						<p>
						<div class="col-lg-7 col-md-7">
			<?php
						echo "<b>".date("d M y", strtotime($due->action_date))."</b>. ";
						echo "Call <b>".$due->name."</b> from <b>". $due->village_name."</b> on <b>".$due->phone_number."</b>";
			?>
						</div>
						<div class=\"col-lg-5 col-md-5\">
						<a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($due->due_id);?>" class="btn btn-info">{{ trans('routes.details') }}</a>
						
						</div>
						</p>
						</div>
			<?php
					}
				}
				else{ ?>
					{{ trans('routes.nocalls_thisweek') }} 
					{{ trans('routes.nextcall') }}
			<?php 
					if(isset($due_list_scheduled) && count($due_list_scheduled) > 0){
						echo "<b>".date("d M y", strtotime($due_list_scheduled[0]->action_date))."</b>. ";
					}
				} ?>
			</h4>
		<hr />
		@include('template/mycalls_calldetails')
	</div>
</div>

</body>





