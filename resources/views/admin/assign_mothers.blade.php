<?php 
	$userinfo = Session::get('user_logged');
?>
<!DOCTYPE html>
<html lang="en">

	<head>
		@include('template/admin_title')
		@include('template/admin_cssscripta')
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
	</head>
	<style>
		smallfont{
			font-size:10px;
		}
	</style>
	<body>
		@include('template/admin_header')
		@include('template/admin_sidebar')

		<div id="page-wrapper" >
			<div id="page-inner">
				<div class="row container-fluid">
					<h3> Assign Mothers </h3>

					<div>
						<h4> Unassigned Mothers </h4>
						<form method="POST" action="{{ url() }}/assign/mothers">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead class="warning">
									<th>select</th>
									<th>CC Id</th>
									<th>Name</th>
									<th>Email</th>
									<th>Contact</th>
								</thead>
								
								<tbody>
									<?php foreach ($unassigned as $value){ ?>
										<tr>
											<td><input type="checkbox" id="sjd" name="check_list[]" value = "<?php echo $value->b_id;?>"/></td>	
											<td><?php echo $value->b_id;?></td>							
											<td><?php echo $value->v_name;?></td>	
											<td><?php echo $value->v_husband_name;?></td>
											<td><?php echo $value->v_phone_number;?></td>											
										</tr>  
									<?php } ?>
								</tbody>
							</table>
						</div>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<button type="submit" class="btn btn-primary btn-lg">Assign Selected Mothers</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>