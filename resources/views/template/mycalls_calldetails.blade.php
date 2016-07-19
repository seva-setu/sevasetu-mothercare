<div class="row">
	<div class="col-lg-6 col-md-6">
	<h3>
		{{ trans('routes.callspending')}}
		<span class="label label-danger">{{ trans('routes.important')}} </span>
	</h3>
	<table class="table table-striped table-bordered table-hover">
			<thead class="warning">
				<th>{{ trans('routes.uniqueid') }}</th>
				<th>{{ trans('routes.name') }}</th>
				<th>{{ trans('routes.status') }}</th>
				<th colspan="2">{{ trans('routes.interventionpoint') }}</th>
			</thead>
			<?php if(isset($due_list_pending) && count($due_list_pending) > 0) { ?>
			<tbody>
				<?php foreach ($due_list_pending as $value){ ?>
				<tr>
				<td data-title="{{ trans('routes.uniqueid') }}"><a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($value->due_id); ?>" ><?php echo $value->due_id;?></a></td>
				
				<td data-title="{{ trans('routes.name') }}"><a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($value->due_id); ?>" ><?php echo $value->name;?></a></td>
				
				<td data-title="{{ trans('routes.stats') }}"><?php echo $value->status;?>
				</td>
				
				<td data-title="{{ trans('routes.interventionpoint') }}">
					<?php
						echo(date("d M y", strtotime($value->action_date)));
					?>
					<br/>
					<smallfont>
					<?php
						
						echo datediff("ww",date("d M y"), $value->action_date, false);
					?>
					</smallfont>
				</td>
				<td>
					<?php //Should have a JS confirmation check on this button ?>
					<a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($value->due_id); ?>" class="btn btn-warning">{{ trans('routes.details') }}</a>
				</td>
				<?php //@if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1)    <td></td> @endif
				?>
				</tr>  
				<?php } ?>
				<tr>
					<td colspan="6"><center>{!! $due_list_pending->appends(['three'=>Request::query('three')])->render() !!}</center></td>
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
						echo datediff("ww",date("d M y"), $value->action_date, false);
					?>
					</smallfont>
				</td>
				<td>
					<?php //Should have a JS confirmation check on this button ?>
					<a href="<?php echo url().'/admin/mycalls/view/'.Hashids::encode($value->due_id); ?>" class="btn btn-warning">{{ trans('routes.details') }}</a>
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
</div>
<hr/>
<div class="row">
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
						echo datediff("ww",date("d M y"), $value->action_date, false);
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
</div>

<?php
	
function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
    /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
        (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
    */
    
    if (!$using_timestamps) {
        $datefrom = strtotime($datefrom, 0);
        $dateto = strtotime($dateto, 0);
    }
    $difference = $dateto - $datefrom; // Difference in seconds
     
    switch($interval) {
     
    case 'yyyy': // Number of full years
        $years_difference = floor($difference / 31536000);
        if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
            $years_difference--;
        }
        if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
            $years_difference++;
        }
        $datediff = $years_difference;
        break;
    case "q": // Number of full quarters
        $quarters_difference = floor($difference / 8035200);
        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
            $months_difference++;
        }
        $quarters_difference--;
        $datediff = $quarters_difference;
        break;
    case "m": // Number of full months
        $months_difference = floor($difference / 2678400);
        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
            $months_difference++;
        }
        $months_difference--;
        $datediff = $months_difference;
        break;
    case 'y': // Difference between day numbers
        $datediff = date("z", $dateto) - date("z", $datefrom);
        break;
    case "d": // Number of full days
        $datediff = floor($difference / 86400);
        break;
    case "w": // Number of full weekdays
        $days_difference = floor($difference / 86400);
        $weeks_difference = floor($days_difference / 7); // Complete weeks
        $first_day = date("w", $datefrom);
        $days_remainder = floor($days_difference % 7);
        $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
        if ($odd_days > 7) { // Sunday
            $days_remainder--;
        }
        if ($odd_days > 6) { // Saturday
            $days_remainder--;
        }
        $datediff = ($weeks_difference * 5) + $days_remainder;
        break;
    case "ww": // Number of full weeks
        $datediff = floor($difference / 604800);
        break;
    case "h": // Number of full hours
        $datediff = floor($difference / 3600);
        break;
    case "n": // Number of full minutes
        $datediff = floor($difference / 60);
        break;
    default: // Number of full seconds (default)
        $datediff = $difference;
        break;
    }

	if($datediff<0)
		$ret = "(".-1*$datediff.trans('routes.weeksago'). ")";
	elseif($datediff == 1)
		$ret = "(".trans('routes.nextweek'). ")";
	elseif ($datediff > 1)
		$ret = "($datediff ".trans('routes.weekstogo'). ")";
	else
		$ret = "(".trans('routes.thisweek'). ")";
    return $ret;
}
?>
