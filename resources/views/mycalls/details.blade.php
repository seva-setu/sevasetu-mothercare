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
					<h3><?php  echo "<b>".$personal_details->name."</b> (".$personal_details->age." ".trans('routes.yearsold').")"; ?></h3>
				</div>
				<div class="col-lg-5 col-md-5">
					<h3>
					<?php  echo "<b>".trans('routes.phonenumber')."</b>: "; 
							echo $personal_details->phone_number;
					?>
					</h3>
				</div>
				<div class="col-lg-2 col-md-2">
					<h3><b>
					<span class="label label-success">
						<?php 	echo trans('routes.callid').$call_details['due_id']; ?>
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
						echo (date("d M y", strtotime($personal_details->due_date)));
					?>
					</p>
					<p>
					<?php  echo "<b>".trans('routes.husbandname')."</b>: ";
							echo $personal_details->husband_name;
					?>
					</p>
				</div>
				<div class="col-lg-7 col-md-7">
					<p>
					<?php  echo "<b>".trans('routes.village')."</b>: ";
							echo $personal_details->village_name;
					?>
					</p>
					<p>
					<?php  echo "<b>".trans('routes.fieldworkername')."</b>: ";
							echo $personal_details->field_worker_name;
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
						echo date("d M y", strtotime($call_details['action_date']));
							
					?>
					</span>
					</b></h4>
				</div>
				<div class="col-lg-5 col-md-5">
					<h4><b>
					<?php
						if(isset($due_list_pending) and count($due_list_pending)>0){
					?>		
						<span class = "label label-danger">
						{{ count($due_list_pending) }} {{ trans('routes.callsunattended') }}
						</span>
					<?php }
					?>
					</b></h4>
				</div>
			</div>
			<hr/>
			<div class ="row">
				<?php $ret = Session::get('message');
						if(isset($ret)){
				?>
						<div class="col-lg-12 col-md-12">
				<?php
						if(Session::get('message')){
				?>			<h3><span class="label label-success"> {{trans('routes.success')}}</span></h3>
				<?php	}
						else{
				?>			<h4><span class="label label-danger">{{trans('routes.failure')}}</span></h4>
				<?php	}
				?>
						<hr/>
						</div>
			</div>
			<div class="row">
				<?php } ?>
				<div class="col-lg-12 col-md-12">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#t_notes" data-toggle="tab"> <b> {{ trans('routes.notes') }} </b></a>
						</li>
						<li class=""><a href="#t_previousnotes" data-toggle="tab"> <b>{{ trans('routes.previousnotes') }} </b> 
						</a>
						</li>
						<li class=""><a href="#t_previouscalls" data-toggle="tab"> <b>{{ trans('routes.callscompletedscheduled') }} </b> 
						</a>
						</li>
					 </ul>
					 <!-- BEGIN CODE FOR TABBED CONTENT -->
					 <form name = "update_due" method = "POST" action = <?php echo url().'/mycalls/update/'.Hashids::encode($call_details['due_id']); ?> >
					 <div class="tab-content">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					 <!-- BEGIN CODE FOR TAB "Notes from call" -->
                        <div class="tab-pane fade active in" id="t_notes">
							<!-- Begin section for action items -->
							<div class="col-lg-6 col-md-6">
								<p>
								<!-- Begin drop down -->
								<b>{{ trans('routes.recordcallstatus') }}</b>
								<div class="dropdown">
								  <select name = "callstats" id = "callstat" class="form-control">
									<option <?php if ($current_notes->status == 'Not called') echo ' selected="selected"'; ?> >{{ trans('routes.nc') }} </option>
									<option <?php if ($current_notes->status == 'Received') echo ' selected="selected"'; ?>> {{ trans('routes.re') }} </option>
									<option <?php if ($current_notes->status == 'Not received') echo ' selected="selected"'; ?>> {{ trans('routes.nr') }} </option>
									<option <?php if ($current_notes->status == 'Not reachable') echo ' selected="selected"'; ?>> {{ trans('routes.nr2') }} </option>
									<option <?php if ($current_notes->status == 'Incorrect number') echo ' selected="selected"'; ?>> {{ trans('routes.in') }} </option>
								  </select>
								</div>
								</p>
								<!-- End drop down -->
						<hr/>

						<b>{{ trans('routes.expecteddatestatus') }}</b>	
						<script>
							function fun()
							{
								if(document.getElementById("duedatestat").value == '{{ trans('routes.incorrect') }}' )
								{
									document.getElementById("date").classList.remove("hidden");
									document.getElementById("duedate").setAttribute("required", "");
								}
								if(document.getElementById("duedatestat").value == '{{ trans('routes.correct') }}' )
								{
									document.getElementById("date").classList.add("hidden");
									document.getElementById("duedate").removeAttribute("required");
								}
							}
						</script>
						<div class="dropdown">				
						  <select name = "duedatestat" id = "duedatestat" onchange="fun()" class="form-control">
								<option>{{ trans('routes.correct') }}</option>
								<option>{{ trans('routes.incorrect') }}</option>
							</select>

							<div class="hidden" id="date">
								<br/>
								<label>Please fill correct date of delivery : <input type="date" name="duedate" id="duedate"/></label>
							</div>
						</div>
						<hr/>	
						
								<h5><b>
									{{trans('routes.actionitem')}}
								</b></h5>
								<?php 
									$prev = "";
									foreach($action_items as $item){ 
										if($prev != $item->reference_descrip){
											if($prev != "")
												echo "</ul>";
								?>
												<h4>
												<span class="label label-success">
								<?php
												echo $item->reference_descrip;
												$prev = $item->reference_descrip;
								?>
												</span>
												</h4>
											<ul>
								<?php 	} ?>
										<li> {{ $item->action_descrip }}</li>
								<?php }	
								?>
											</ul>
								
								
							</div>
							<!-- End section for action items -->
							<!-- Begin section for textarea -->
							<div class="col-lg-6 col-md-6">
							   <div class="form-group">
								  <label for="action_item">{{ trans('routes.notes_field')}}
								  <span class="label label-danger">Important</span>
								  </label>
								  <textarea name="action_item" class="form-control" rows="5" id="action_item" placeholder="<?php echo trans('routes.textareadefaulttext');?>" onfocus="this.placeholder=''"   onblur="this.placeholder = '<?php echo trans('routes.textareadefaulttext');?>'"><?php
									  echo $current_notes->action_items;
								  ?></textarea>
								</div>
								
							   <div class="form-group">
								  <label for="general_note">{{ trans('routes.notes_general') }} {{ $personal_details->name }}</label>
								  <textarea name="general_note" class="form-control" rows="5" id="general_note">{{ $current_notes->conversation_notes }}</textarea>
								</div>
								<button onclick="return confirm('Are you sure you want to submit with status  \'' + document.getElementById('callstat').value + '\' ?')" type="submit">{{trans('routes.submitnote')}}</button>
							</div>
							<!-- End section for textarea -->
                        </div>
					</form> 
						
						<div class="tab-pane fade" id="t_previousnotes">
							<div class="col-lg-12 col-md-12">
								<table class="table table-striped table-bordered table-hover">
									<thead class="warning">
										<th>{{ trans('routes.uniqueid') }}</th>
										<th>{{ trans('routes.status') }}</th>
										<th>{{ trans('routes.callmadeon') }}</th>
										<th>{{ trans('routes.generalnotes') }}</th>
										<th>{{ trans('routes.actionitemsnoted') }}</th>
									</thead>
									<?php if(isset($previous_notes) && count($previous_notes) > 0) { ?>
									<tbody>
										<?php foreach ($previous_notes as $value){ ?>
										<tr>
										<td><?php echo $value->call_id;?></td>
										
										<td><?php echo $value->status;?></td>
										
										<td><?php echo date('d M y',strtotime($value->modify_date));?></td>
										
										<td><?php echo $value->general_notes;?></td>
										
										<td><?php echo $value->action_items;?></td>
										
										</tr>  
										<?php } ?>
										<tr>
											<td colspan="6"><center>{!! $previous_notes->appends(['three'=>Request::query('three')])->render() !!}</center></td>
										</tr>
									</tbody>
									<?php } else { ?>
									<tbody>
										  <tr>
											<td colspan="5"><center><em><?php echo trans('routes.norecord'); ?></em></center></td>
										  </tr>
								  </tbody>
									  <?php } ?>
							</table>
							</div>
						</div>
						
					<!-- BEGIN CODE FOR TAB "Action items" -->
						<div class="tab-pane fade" id="t_previouscalls">
                           @include('template/mycalls_calldetails')
                        </div>
					 </div>
					</form> 
					 <!-- END CODE FOR TABBED CONTENT -->
					 
				</div>
			</div>
		<?php } //END IF RECORD EMPTY CHECK ?>
	</div>
</div>
@include('template/admin_jsscript')
</body>
