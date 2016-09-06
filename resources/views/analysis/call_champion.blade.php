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
			<table class="table table-striped table-bordered table-hover">
			<tr>
			<th>Call Champion</th>
			<th>Number of Mothers assigned</th>
			<th>Number of calls attempted</th>
			<th>Number of notes generated</th>
			<th>Number of action items generated</th>
			<th>Number of action items resolved</th>
			</tr>
			@foreach($data as $i)
			<tr>
				<td>{{$i['v_name']}}</td>
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
