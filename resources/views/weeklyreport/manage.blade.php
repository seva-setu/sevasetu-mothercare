<?php 
$cstartdate = date('d/m/Y',strtotime('last sunday'));
$cenddate = date('d/m/Y',strtotime('this saturday'));
$state=isset($state) && $state!="all"?$state:"";
$city=isset($city) && $city!="all"?$city:"";
$taluka=isset($taluka) && $taluka!="all" ?$taluka:"";

$userinfo=Session::get('user_logged');
$startdate=isset($startdate)?date('d/m/Y',strtotime($startdate)):"";
$enddate=isset($enddate)?date('d/m/Y',strtotime($enddate)):"";
$datelable="";
if($startdate!="" && $enddate!=""){
	$datelable=$startdate." to ".$enddate;
}	 
$languagedata= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
 
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
			<div class="col-md-12">
				<h2><?php  echo trans('routes.mycalls'); ?></h2>
			</div>
	   </div>
		<hr />
		<div class="row">
		
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-6">
				<h3>Calls scheduled</h3>
				<table class="table table-striped table-bordered table-hover">
                        <thead class="warning">
							<th>{{ trans('routes.uniqueid') }}</th>
							<th>{{ trans('routes.name') }}</th>
							<th>{{ trans('routes.location') }}</th>
							<th colspan="2">{{ trans('routes.interventionpoint') }}</th>
						</thead>
						<?php if(!empty($due_list) && count($due_list) > 0) { ?>
						<tbody>
							<?php foreach ($due_list as $value){ ?>
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
								<a href="" class="btn btn-danger">Cancel</a>
							</td>
							<?php //@if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1)    <td></td> @endif
							?>
							</tr>  
							<?php } ?>
							<tr>
								<td colspan="6"><center>{!! $due_list->render() !!}</center></td>
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
			
			<div class="col-lg-6 col-md-6">
				<h3>Calls completed</h3>
				<table class="table table-striped table-bordered table-hover">
                        <thead class="warning">
							<th>{{ trans('routes.uniqueid') }}</th>
							<th>{{ trans('routes.name') }}</th>
							<th>{{ trans('routes.location') }}</th>
							<th colspan="2">{{ trans('routes.interventionpoint') }}</th>
						</thead>
						<?php if(!empty($due_list) && count($due_list) > 0) { ?>
						<tbody>
							<?php foreach ($due_list as $value){ ?>
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
								<a href="" class="btn btn-danger">Cancel</a>
							</td>
							<?php //@if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1)    <td></td> @endif
							?>
							</tr>  
							<?php } ?>
							<tr>
								<td colspan="6"><center>{!! $due_list->render() !!}</center></td>
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
	</div>
</div>

</body>





