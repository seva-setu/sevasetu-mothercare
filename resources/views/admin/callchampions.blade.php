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
				<h2>Call Champions</h2>
					<br/>
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#home">All</a></li>
						<li><a data-toggle="tab" href="#menu1">Unapproved</a></li>
						<li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
						<li><a data-toggle="tab" href="#menu3">Menu 3</a></li>
					</ul>

				<div class="tab-content">
					<div id="home" class="tab-pane fade in active">
						<h3>Total call champions till date: 6</h3>
					</div>
					<div id="menu1" class="tab-pane fade">
						<h3>Unapproved</h3>
						<table class="table table-striped table-bordered table-hover">
							<thead class="warning">
								<th>CC Id</th>
								<th>Name</th>
								<th>Email</th>
								<th>Contact</th>
								<th>Assign mentor</th>
							</thead>
							
							<tbody>
								<?php foreach ($unapproved as $value){ ?>
									<tr>
										<td><?php echo $value->cc_id;?></td>									
										<td><?php echo $value->v_name;?></td>	
										<td><?php echo $value->v_email;?></td>
										<td><?php echo $value->i_phone_number;?></td>	
										<td><button class="btn btn-primary btn-xs">Assign mentor</button></td>									
									</tr>  
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div id="menu2" class="tab-pane fade">
						<h3>Shadowing</h3>
						<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
					</div>
					<div id="menu3" class="tab-pane fade">
						<h3>Menu 3</h3>
						<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>