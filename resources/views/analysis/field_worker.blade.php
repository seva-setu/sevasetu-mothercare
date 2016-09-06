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
<table class="table">
</table>
</div>
</div>
</body>
</html>
