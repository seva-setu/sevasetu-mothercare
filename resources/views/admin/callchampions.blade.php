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
					<!-- Call champion navigation tab bar-->
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#home">All</a></li>
						<li><a data-toggle="tab" href="#menu1">Unapproved</a></li>
						<li><a data-toggle="tab" href="#menu2">Shadowing</a></li>
						<li><a data-toggle="tab" href="#menu3">Unassigned Callchampions</a></li>
					</ul>

				<div class="tab-content">
					<!--Tab for all callchampion who are approved and assigned to some beneficiaries-->
					<div id="home" class="tab-pane fade in active">
						<h3>Total call champions till date: 9</h3>
						<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead class="warning">
								<th>CC Id</th>
								<th>Name</th>
								<th>Email</th>
								<th>Contact</th>
								<th>Assign mothers</th>
							</thead>
							
							<tbody>
								<?php foreach ($all as $value){ ?>
									<tr>
										<td><?php echo $value->cc_id;?></td>							
										<td><?php echo $value->v_name;?></td>	
										<td><?php echo $value->v_email;?></td>
										<td><?php echo $value->i_phone_number;?></td>	
										<td><button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assign_mothers" data-id = '{{ $value->cc_id }}' >Assign mothers</button></td>
									</tr>  
								<?php } ?>
							</tbody>
						</table>
						</div>

						<!--Modal for assigning mothers and selection of no of mothers to be assigned to that callchampion-->
						<div class="modal fade" id="assign_mothers" role="dialog">
						    <div class="modal-dialog">						    
						      <!-- Modal content-->
						      	<div class="modal-content">
						        	<div class="modal-header">
						          		<button type="button" class="close" data-dismiss="modal">&times;</button>
						          		<h4 class="modal-title">Select number of mothers</h4>
						        	</div>
						        	<div class="modal-body">
						          		<form class="form-horizontal" role="form" method="post" action="{{ url() }}/assign/mothers">
										    <div class="form-group">
										      <label class="control-label col-sm-3" for="mothers_count">No of mothers:</label>
										      <div class="col-sm-9">
										        <input type="number" class="form-control" min="1" max="20" id="mothers_count" name="mothers_count" required>
										        <input type="hidden"  id="cc_id" name="cc_id">
										      </div>
										    </div>
										    <div class="form-group">
										      <div class="col-sm-offset-2 col-sm-10">
										      	<input type="hidden" name="_token" value="{{ csrf_token() }}">
										        <button type="submit" class="btn btn-primary">Assign Mothers</button>
										      </div>
										    </div>
										</form>
						        	</div>
						        	<div class="modal-footer">
						          		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						        	</div>
						      	</div>						      
						    </div>
						</div> 
					</div>

					<!--Tab for Unapproved callchampions who just got unboard and unapproved-->
					<div id="menu1" class="tab-pane fade">
						<h3>Unapproved</h3>
						<div class="table-responsive">
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
										<td><button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assign_mentor" data-id = '{{ $value->cc_id }}' data-name='{{ $value->v_name }}' >Assign mentor</button></td>
									</tr>  
								<?php } ?>
							</tbody>
						</table>
						</div>

						<!--Modal for assigning mentors and selection of mentors to be assigned to that unapproved callchampion-->
						<div class="modal fade" id="assign_mentor" role="dialog">
						    <div class="modal-dialog">						    
						      <!-- Modal content-->
						      	<div class="modal-content">
						        	<div class="modal-header">
						          		<button type="button" class="close" data-dismiss="modal">&times;</button>
						          		<h4 class="modal-title">Assign a mentor</h4>
						        	</div>
						        	<div class="modal-body">
						          		<form class="form-horizontal" role="form">
										    <div class="form-group">
										      <label class="control-label col-sm-3" for="mentee_name">Mentee:</label>
										      <div class="col-sm-9">
										        <input type="text" class="form-control" id="mentee_name" readonly>
										        <input type="hidden"  id="mentee">
										      </div>
										    </div>
										    <div class="form-group">
										      <label class="control-label col-sm-3" for="mentor">Select Mentor:</label>
										      <div class="col-sm-9">
										        <select class="form-control" id="mentor" name="mentor" placeholder="Mentor">
										        <?php foreach ($mentors as $value){ ?>
										        	<option value='{{ $value->cc_id }}'>{{ $value->v_name }}</option>
										        <?php } ?>
										        </select>
										      </div>
										    </div>
										    <div class="form-group">
										      <div class="col-sm-offset-2 col-sm-10">
										        <button type="submit" onclick="assign_mentor(event)" class="btn btn-primary">Assign Mentor</button>
										      </div>
										    </div>
										</form>
						        	</div>
						        	<div class="modal-footer">
						          		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						        	</div>
						      	</div>						      
						    </div>
						</div> 
					</div>

					<!--Tab for ongoing shadowing mentors and mentees -->
					<div id="menu2" class="tab-pane fade">
						<h3>Shadowing</h3>
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead class="warning">
									<th>CC Id</th>
									<th>Mentee</th>
									<th>Contact</th>
									<th>CC Id</th>
									<th>Mentor</th>
									<th>Contact</th>
									<th>Mark as done</th>
								</thead>
								
								<tbody>
									<?php for ($i = 0 ; $i < sizeof($mentees); $i++) {?>
										<tr>
											<td><?php echo $mentees[$i]->cc_id;?></td>							
											<td><?php echo $mentees[$i]->v_name;?></td>	
											<td><?php echo $mentees[$i]->i_phone_number;?></td>
											<td><?php echo $mentor[$i][0]->cc_id;?></td>
											<td><?php echo $mentor[$i][0]->v_name;?></td>
											<td><?php echo $mentor[$i][0]->i_phone_number;?></td>	
											<td><button class="btn btn-primary btn-xs" onclick="shadowing_done(event, <?php echo $mentees[$i]->cc_id;?>)" >Mark as Done</button></td>
										</tr>  
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>

					<!--Tab for approved callchampions who are yet to assign to any beneficiary-->
					<div id="menu3" class="tab-pane fade">
						<h3>Menu 3</h3>
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead class="warning">
									<th>CC Id</th>
									<th>Name</th>
									<th>Email</th>
									<th>Contact</th>
									<th>Assign mothers</th>
								</thead>
								
								<tbody>
									<?php foreach ($unassigned as $value){ ?>
										<tr>
											<td><?php echo $value->cc_id;?></td>							
											<td><?php echo $value->v_name;?></td>	
											<td><?php echo $value->v_email;?></td>
											<td><?php echo $value->i_phone_number;?></td>	
											<td><form method="post" action="{{ url() }}/assign/mothers">
												<input type="hidden" name="cc_id" value="{{ $value->cc_id }}"/>
												<input type="hidden" name="mothers_count" value="-1"/>
										      	<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
										      	<button class="btn btn-primary btn-xs">Assign mothers</button>
										      	</form>
										    </td>
										</tr>  
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript">
	 	// function sets the value of mentee id when assign mentor modal opens for a particular call champion
  		$('#assign_mentor').on('show.bs.modal', function(e){
			   var menteeId = $(e.relatedTarget).data('id');
			   var mentee = $(e.relatedTarget).data('name');
			   document.getElementById('mentee_name').value = mentee;
			   document.getElementById('mentee').value = menteeId;
			});

  		// function sets the value of callchampion id when assign mothers modal opens for a particular call champion
  		$('#assign_mothers').on('show.bs.modal', function(e){
			   var cc_id = $(e.relatedTarget).data('id');
			   document.getElementById('cc_id').value = cc_id;
			});

  		// function sends a request to controller for assigning mentor for a particular unapproved callchampion
  		function assign_mentor(event)
  		{
  			 event.preventDefault();
  			 var mentee_id = document.getElementById('mentee').value;
  			 var mentor_id = document.getElementById('mentor').value;
  			$.ajax({
			         url : '{{ url() }}/mentor/assign',
			         type: "POST",
			         dataType: 'json',
			         data:{'_token': '{{ csrf_token() }}',
			         		'mentor_id': mentor_id,
			         		'mentee_id': mentee_id
			         	  },
			     }).done(function (data) {
			     	$('#assign_mentor').modal('hide');
			         alert("successfuly assigned mentor.");
			         event.preventDefault();
			         window.location.href = "";

			     }).fail(function (data) {
			     	alert("Error in assigning mentor. check logs for further info.");
			     });
  		}

  		// function calls the method in controller for changing the status of a cllchampion from shadowing to approved on mark as done action
  		function shadowing_done(event,cc_id)
  		{
  			event.preventDefault();
  			$.ajax({
			         url : '{{ url() }}/callchampion/status/update',
			         type: "POST",
			         dataType: 'json',
			         data:{'_token': '{{ csrf_token() }}',
			         		'cc_id': cc_id
			         	  },
			     }).done(function (data) {
			         alert("successfuly marked shadowing as done.");
			         event.preventDefault();
			         window.location.href = "";

			     }).fail(function (data) {
			     	alert("Error in assigning mentor. check logs for further info.");
			     });
  		}

  	</script> 

</body>
</html>