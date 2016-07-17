	<div class="col-lg-6 col-md-6">
	<h3>{{ trans('routes.callsscheduled')}}</h3>
	<table class="table table-striped table-bordered table-hover">
			<thead class="warning">
				<th>{{ trans('routes.uniqueid') }}</th>
				<th>{{ trans('routes.name') }}</th>
				<th>{{ trans('routes.location') }}</th>
				<th colspan="2">{{ trans('routes.interventionpoint') }}</th>
			</thead>
			<?php if(isset($due_list_scheduled) && count($due_list_scheduled) > 0) { ?>
			<tbody>
				<?php foreach ($due_list_scheduled as $value){ ?>
				<tr>
				<td data-title="{{ trans('routes.uniqueid') }}"><a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($value->due_id); ?>" ><?php echo $value->due_id;?></a></td>
				
				<td data-title="{{ trans('routes.name') }}"><a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($value->due_id); ?>" ><?php echo $value->name;?></a></td>
				
				<td data-title="{{ trans('routes.location') }}"><?php echo $value->village_name;?>
				</td>
				
				<td data-title="{{ trans('routes.interventionpoint') }}">
					<?php
						echo(date("d M y", strtotime($value->action_date)));
					?>
					<br/>
					<smallfont>
					<?php
						//Write the difference of the dates here instead of ..
						echo "(.. ".trans('routes.weeks'). ")";
					?>
					</smallfont>
				</td>
				<td>
					<?php //Should have a JS confirmation check on this button ?>
					<a href="<?php echo url().'/admin/schedule/'.Hashids::encode($user_details['role_id']).'/'.Hashids::encode($value->due_id);?>" class="btn btn-danger">{{ trans('routes.cancel') }}</a>
				</td>
				<?php //@if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1)    <td></td> @endif
				?>
				</tr>  
				<?php } ?>
				<tr>
					<td colspan="6"><center>{!! $due_list_scheduled->appends(['two'=>Request::query('two')])->render() !!}</center></td>
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

	<div class="col-lg-6 col-md-6">
	<h3>{{ trans('routes.callscompleted') }}  </h3>
	<table class="table table-striped table-bordered table-hover">
			<thead class="warning">
				<th>{{ trans('routes.uniqueid') }}</th>
				<th>{{ trans('routes.name') }}</th>
				<th>{{ trans('routes.location') }}</th>
				<th colspan="2">{{ trans('routes.interventionpoint') }}</th>
			</thead>
			<?php if(isset($due_list_completed) && count($due_list_completed) > 0) { ?>
			<tbody>
				<?php foreach ($due_list_completed as $value){ ?>
				<tr>
				<td data-title="{{ trans('routes.uniqueid') }}"><a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($value->due_id); ?>" ><?php echo $value->due_id;?></a></td>
				
				<td data-title="{{ trans('routes.name') }}"><a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($value->due_id); ?>" ><?php echo $value->name;?></a></td>
				
				<td data-title="{{ trans('routes.location') }}"><?php echo $value->village_name;?>
				</td>
				
				<td data-title="{{ trans('routes.interventionpoint') }}">
					<?php
						echo(date("d M y", strtotime($value->action_date)));
					?>
					<br/>
					<smallfont>
					<?php
						//Write the difference of the dates here.
						echo "(.. weeks to go)";
					?>
					</smallfont>
				</td>
				<td>
					<?php //Should have a confirmation check on this button ?>
					<a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($value->due_id); ?>" class="btn btn-success"><?php echo trans('routes.edit'); ?></a>
				</td>
				<?php //@if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1)    <td></td> @endif
				?>
				</tr>  
				<?php } ?>
				<tr>
					<td colspan="6"><center>{!! $due_list_completed->appends(['one'=>Request::query('one')])->render() !!}</center></td>
				</tr>
			</tbody>
			<?php } else { ?>
			<tbody>
				  <tr>
					<td colspan="10"><center><em><?php echo trans('routes.norecord'); ?></em></center></td>
				  </tr>
		  </tbody>
			  <?php } ?>
	</table>
	</div>