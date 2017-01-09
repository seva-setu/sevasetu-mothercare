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

<div id="page-wrapper" >
<div id="page-inner">
<br><br>
<div class="table-responsive">
<table class="table table-bordered">
<tr>
	<th>
	{{trans('analysis.field_worker')}}
	</th>
	<th>
		{{trans('analysis.number_of_mothers_assigned')}}
	</th>
	<th>
	 	{{trans('analysis.number_of_incorrect_numbers')}}	
	</th>
	<th>
	 	{{trans('analysis.number_of_unconnected_numbers')}}	
	</th>
	<th>
	 	{{trans('analysis.number_of_successfull_calls')}}
	</th>

</tr>

@for($i=0;$i<(count($data['f']));$i++)
<tr>
	<td>
		{{$data['f'][$i]}}
	</td>
	<td>
		{{$data['m_count'][$i]}}
	</td>
	<td>
	 	{{$data['incorrect_no'][$i]}}	
	</td>
	<td>
	 	{{$data['unconnected_calls'][$i]}}	
	</td>
	<td>
		{{$data['connected_calls'][$i]}}
	</td>

</tr>

@endfor
</table>
</div>
</div>
</div>
</body>
</html>
