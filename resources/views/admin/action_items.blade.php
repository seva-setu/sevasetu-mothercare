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
<a href="{{url()}}/download_action_items" class="btn btn-primary">Download Info</a>
<table class="table">
<tr>
<th>{{ trans('action_items.call_id') }}</th>
<th>{{ trans('action_items.call_status') }}</th>
<th>{{ trans('action_items.date_generated') }}</th>
<th>{{ trans('action_items.notes') }}</th>
<th>{{ trans('action_items.action_item') }}</th>
<th>{{ trans('action_items.field_workers_assigned_to') }}</th>
<th>{{ trans('action_items.b_id') }}</th>
<th>{{ trans('action_items.mother_name') }}</th>
<th>{{ trans('action_items.village_name') }}</th>
<th>{{ trans('action_items.phone_number') }}</th>
<th>{{ trans('action_items.cc_id') }}</th>
<th>{{ trans('action_items.user_id') }}</th>
<th>{{ trans('action_items.call_champ_associated') }}</th>
<th></th>
</tr>
@foreach($newdata as $x)
<tr>
<td>{{$x['call_id']}}</td>
<td>{{$x['e_call_status']}}</td>
<td>{{$x['date_generated']}}</td>
<td>{{$x['notes']}}</td>
<td>{{$x['action_items']}}</td>

<td>{{$x['field_worker_name']}}</td>
<td>{{$x['beneficiary_id']}}</td>
<td>{{$x['beneficiary_name']}}</td>
<td>{{$x['beneficiary_village']}}</td>
<td>{{$x['beneficiary_contact']}}</td>
<td>{{$x['cc_id']}}</td>
<td>{{$x['cc_user_id']}}</td>
<td>{{$x['call_champion_name']}}</td>
@if($x['action_items']!='')
@if($x['status']==0)
<td><form method="POST" action="{{url()}}/actions/{{$x['report_id']}}">			
<input type="hidden" name="_token" value="{{ csrf_token() }}">		
<button class="btn btn-primary" type="submit">{{ trans('action_items.resolve') }}</button>

</form></td>
@endif
@if($x['status']==1)
<td><form method="POST" action="{{url()}}/actions/{{$x['report_id']}}/unresolve">		
<input type="hidden" name="_token" value="{{ csrf_token() }}">		
<button class="btn btn-success" type="submit">{{ trans('action_items.resolved') }}</button></form></td>
@endif
@endif
</tr>

@endforeach
</table>
</div>
</div>
</body>
</html>