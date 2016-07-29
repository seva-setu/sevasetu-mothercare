
<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ $title or '' }}</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	@include('template/admin_cssscripta')
	<link rel="stylesheet" href="{{ url() }}/external/css_admin/dashboard.css" />
</head>

<body>
@include('template/admin_header')
@include('template/admin_sidebar')
 <div id="page-wrapper" >
	<div id="page-inner">
	   <div class="row">
			<div class="col-md-12">
				<h2><?php  echo trans('routes.dashboard'); ?></h2>
			</div>
	   </div>
		<hr />
		<div class="row">
			<div class="col-lg-4 col-md-4">
				<div class="panel panel-primary">
				<div class="panel-heading">
				   <?php echo trans('routes.assigned_beneficiary');?>
				</div>
				<div class="panel-body">
					<table class="table table-responsive">
					<?php 
						foreach($assigned_beneficiaries as $mother){
							echo "<tr>";
							
							echo "<td>";
							echo $mother->name;
							echo "</td>";
							
							echo "<td>";
							echo (" from");
							echo "</td>";
							
							echo "<td>";
							echo $mother->village_name;
							echo "</td>";
							
							echo "</tr>";
						}
					?>
					</table>
				   
				</div>
				<div class="panel-footer">
				</div>
			 </div>
			</div>
			<div class="col-lg-4 col-md-4">
            <table>
			<tr><td>
				<div class="panel panel-primary">
				<div class="panel-heading">
				   <?php echo trans('routes.next_scheduled');?>
				</div>
				<div class="panel-body">
					<?php 
						echo $next_scheduled_call;
					?>
				   
				</div>
				<div class="panel-footer">
				</div>
			 </div>
			
			</td></tr>
			<tr><td>
			
				<div class="panel panel-primary">
				<div class="panel-heading">
				   <?php echo trans('routes.number_calls')?>
				</div>
				<div class="panel-body">
					<?php 
						echo $number_of_calls; 
					?>
				</div>
				<div class="panel-footer">
				</div>
			 </div>
			 
			</td></tr>
			</div>
		</div>
		<hr />
		</div>
	</div>
</body>
</html>