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
@include('template/admin_sidebar')
@include('template/script_multilanguage')

<div id="page-wrapper" >
	<div id="page-inner">
	   <div class="row">
			<div class="col-lg-6 col-md-6">
				<h2><?php  echo trans('routes.callsthisweek'); ?></h2>
			</div>
			<div class="col-lg-6 col-md-6"> 
			
			</div>
	   </div>
		<div class = "row">
			<div class="col-lg-12 col-md-12">
			<h4>
			<?php
				if(isset($due_list_scheduled) && count($due_list_scheduled) > 0){
					$difference_from_today_in_weeks = 2; //formula
					if($difference_from_today_in_weeks <= 1){ ?>
						<span class="badge">{{ trans('routes.close') }}</span>
			<?php 	}
					echo trans('routes.nextcall');
					echo "<b>".date("d M y", strtotime($due_list_scheduled[0]->action_date))."</b>. ";
					
					echo "Call <b>".$due_list_scheduled[0]->name."</b> from <b>". $due_list_scheduled[0]->village_name."</b> on <b>".$due_list_scheduled[0]->phone_number."</b>";
			?>
					<a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($due_list_scheduled[0]->due_id);?>" class="btn btn-info">{{ trans('routes.details') }}</a>
			<?php
					
				} else{ ?>
					{{ trans('routes.nocall') }} 
			<?php } ?>
			</h4>
			</div>
		</div>
		<hr />
		<div class="row">
			@include('template/mycalls_calldetails')
		</div>
	</div>
</div>

</body>





