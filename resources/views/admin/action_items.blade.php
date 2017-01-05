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
<th>{{ trans('action_items.call_id') }}</th>
<th>{{ trans('action_items.date_generated') }}</th>
<th>{{ trans('action_items.action_item') }}</th>
<th>{{ trans('action_items.call_champ_associated') }}</th>
<th>{{ trans('action_items.field_workers_assigned_to') }}</th>
<th>Mother Name</th>
<th>Village Name</th>
<th>Phone Number</th>

<th></th>
</tr>
@foreach($newdata as $x)
<tr>
<td>{{$x['call_id']}}</td>
<td>{{$x['date_generated']}}</td>
<td>{{$x['action_items']}}</td>
<td>{{$x['call_champion_name']}}</td>
<td>{{$x['field_worker_name']}}</td>
<td>{{$x['beneficiary_name']}}</td>
<td>{{$x['beneficiary_village']}}</td>
<td>{{$x['beneficiary_contact']}}</td>
@if($x['status']==0)
<td><form method="POST" action="{{url()}}/actions/{{$x['report_id']}}">			
<input type="hidden" name="_token" value="{{ csrf_token() }}">		
<button class="btn btn-primary" type="submit">{{ trans('action_items.resolve') }}</button></form></td>
@endif
@if($x['status']==1)
<td><form method="POST" action="{{url()}}/actions/{{$x['report_id']}}/unresolve">		
<input type="hidden" name="_token" value="{{ csrf_token() }}">		
<button class="btn btn-success" type="submit">{{ trans('action_items.resolved') }}</button></form></td>
@endif
</tr>

@endforeach
</table>
</div>
</div>
</body>
</html>