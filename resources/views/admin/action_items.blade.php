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
@include('template/admin_header')
@include('template/admin_sidebar')
@include('template/admin_jsscript')
<br>
<div class="row">
<div class="col-md-3">
</div>
<div class="col-md-8 ">

<table class="table">
<tr>
<th>Call ID</th>
<th>Date generated</th>
<th>Action item</th>
<th>Call Champion Associated</th>
<th>Field worker assigned to</th>
<th></th>
</tr>
@foreach($newdata as $x)
<tr>
<td>{{$x['call_id']}}</td>
<td>{{$x['date_generated']}}</td>
<td>{{$x['action_items']}}</td>
<td>{{$x['call_champion_name']}}</td>
<td>{{$x['field_worker_name']}}</td>

@if($x['status']==0)
<td><form method="POST" action="{{url()}}/actions/{{$x['report_id']}}">			
<input type="hidden" name="_token" value="{{ csrf_token() }}">		
<button class="btn btn-primary" type="submit">Resolve</button></form></td>
@endif
@if($x['status']==1)
<td><form method="POST" action="{{url()}}/actions/{{$x['report_id']}}/unresolve">		
<input type="hidden" name="_token" value="{{ csrf_token() }}">		
<button class="btn btn-success" type="submit">Resolved</button></form></td>
@endif
</tr>

@endforeach
</table>

</div>
</div>
</body>
</html>