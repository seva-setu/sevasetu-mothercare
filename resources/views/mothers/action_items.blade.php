<?php 
	$userinfo = Session::get('user_logged');
?>
<!DOCTYPE html>
<html lang="en">

<head>
@include('template/admin_title')
@include('template/admin_cssscripta')
<link rel="shortcut icon" href="{{ url() }}/assets/img/favicon.png">
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
	<br>
	<br>
<div class="row">
<div class="col-md-1">
</div>
<div class="col-md-8 ">

<table class="table">
<tr>
<th>Call ID</th>
<th>Beneficiary Name</th>

<th>Date generated</th>
<th>Action item</th>
<th>Field worker assigned to</th>
<th>Status</th>
<th></th>
</tr>
@foreach($action_data as $x)
<tr>
<td>{{$x['call_id']}}</td>
<td>{{$x['beneficiary_name']}}</td>
<td>{{$x['date_generated']}}</td>
<td>{{$x['action_item']}}</td>
<td>{{$x['field_worker_name']}}</td>

@if($x['status']==0)
<td>Pending</td>
@endif
@if($x['status']==1)
<td>Resolved</td>
@endif
</tr>

@endforeach
</table>

</div>
</div>

</div>
</div>
@include('template/admin_jsscript')
</body>