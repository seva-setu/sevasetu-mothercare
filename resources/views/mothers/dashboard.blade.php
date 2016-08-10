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
@include('template/admin_header')
@include('template/callchampion_sidebar')

<div id="page-wrapper" >
	<div id="page-inner">
		<div class="row">
			<div class="col-lg-8 col-md-8">
				<?php 
					if(isset($data) && is_array($data) && count($data) > 0){
				?>
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
								 <b>{{ $value->name }}</b> from {{ $value->village_name }}
								 </a>
							  </h4>
						   </div>
						   <div id="collapse<?php echo $i; ?>" class="panel-collapse collapse" style="height: 0px;">
							  <div class="panel-body">
								
									<b>{{ trans('routes.name') }}</b>: {{ $value->name }}
								<br/>
									<b>{{ trans('routes.age') }}</b>: {{ $value->age }}
								<br/>
									<b>{{ trans('routes.village') }}</b>: {{ $value->village_name }}
								<br/>
									<b>{{ trans('routes.phonenumber') }}</b>: {{ $value->phone_number }}
								<br/>
									<b>{{ trans('routes.expecteddate') }}</b>: {{ $value->due_date }}
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
			</div>
		</div>
		<div class = "row">
			<div class="col-lg-4 col-md-4">
					<?php } 
						//End of if($data)
						else
							echo "<h4>".trans('routes.getstarted')."</h4>";
					?>
				<h4>
					<a href="<?php echo url().'/schedule/'.Hashids::encode($userinfo['role_id']);?>" class="btn btn-primary"> 
					<b>{{trans('routes.addmother')}}</b>
					</a>
				</h4>
			</div>
			<!-- end main content in the page -->
		</div>
		<hr/>
		<div class="row">
			<div class="col-lg-6 col-md-6">
			<!-- Begin RHS content on the page -->
				<h4>{{ trans('routes.whatismct') }}</h4>
				 <div class="panel panel-primary">
					<div class="panel-body">
						<div class="embed-responsive embed-responsive-16by9">
						<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/VeohOCDw2bc"></iframe>
						</div>
					</div>
				 </div>
			</div>
			<div class="col-lg-6 col-md-6">
			<!-- Begin RHS content on the page -->
				<h4>{{ trans('routes.sevasetuinmedia') }}</h4>
				 <div class="panel panel-primary">
					<div class="panel-body">
						<div class="embed-responsive embed-responsive-16by9">
						<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/uXLACvILofE"></iframe>
						</div>
					</div>
				 </div>
			</div>
			<!-- End RHS content on the page -->	
		</div>
	</div>
</div>
@include('template/admin_jsscript')
</body>





