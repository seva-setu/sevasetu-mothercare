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
@include('template/analysis_sidebar')
@if(Session::has('user_logged'))
	@include('template/admin_header')
@endif
@include('template/analysis_sidebar')

<div id="page-wrapper" >
	<div id="page-inner">
		
		<div class="row">
			<div class="col-lg-10 col-md-10">
			<br><br>
			<table class="table table-striped table-bordered table-hover">
			<tr>
			<th>{{ trans('analysis.call_champ') }}</th>
			<th>{{ trans('analysis.number_of_mothers_assigned') }}</th>
			<th>{{ trans('analysis.numbers_of_call_attempted') }}</th>
			<th>{{trans('analysis.number_of_notes_generated')}}</th>
			<th>{{trans('analysis.number_of_action_items_generated')}}</th>
			<th>{{trans('analysis.number_of_action_items_resolved')}}</th>
			</tr>
			@foreach($data as $i)
			<tr>
				<td><a href="<?php echo url().'/analysis/call_champion/'.Hashids::encode($i['cc_id']); ?>" >{{$i['v_name']}}</a></td>
				<td>{{$i['mother_count']}}</td>
				<td>{{$i['attempted_calls']}}</td>
				<td>{{$i['notes_recorded']}}</td>
				<td>{{$i['action_items_generated']}}</td>
				<td>{{$i['action_items_resolved']}}</td>

			</tr>
			@endforeach		
			</table>
			</div>
		</div>
	</div>
</div>			
</body>
</html>
