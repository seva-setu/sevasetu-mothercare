<?php 
	$userinfo = Session::get('user_logged');
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
	@include('template/admin_title')
	@include('template/admin_cssscripta')
	<script src="{{ url() }}/assets/js_admin/jquery-1.12.4.js"></script>
	<script src="{{ url() }}/assets/js_admin/bootstrap-3.3.7.min.js"></script>	
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
@if (isset($newdata) ) 
<table class="table">
<tr>
<th>Call Champion Name</th>
<th>Beneficiary ID</th>
<th>Beneficiary Name</th>
<th>Original Delivery Date</th>
<th>Reported Delivery Date</th>
<th>Field Worker Name</th>
</tr>

@foreach($newdata as $x)
<tr>
<td>{{$x['call_champion_name']}}</td>
<td>{{$x['b_id']}}</td>
<td>{{$x['b_name']}}</td>
<td>{{$x['due_date']}}</td>
<td>{{$x['rd_date']}}</td>
<td>{{$x['field_worker_name']}}</td>

<td><form method="POST" action="{{url()}}/duedateresolve/{{$x['b_id']}}/{{$x['rd_date']}}/accept">			
<input type="hidden" name="_token" value="{{ csrf_token() }}">		
<button class="btn btn-success" type="submit">Accept</button></form></td>

<td><form method="POST" action="{{url()}}/duedateresolve/{{$x['b_id']}}/reject">		
<input type="hidden" name="_token" value="{{ csrf_token() }}">		
<button class="btn btn-primary" type="submit">Reject</button></form></td>

</tr>
@endforeach

</table>
@endif
</div>
</div>
</body>
</html>
