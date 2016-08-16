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

<br><br>
<div class="container" >
<form class="form-horizontal" method="POST" action="{{url()}}/data/upload"
 enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">		

<fieldset>

<!-- Form Name -->
<legend>Upload Data</legend>

<!-- File Button --> 
<div class="form-group">
  <label class="col-md-4 control-label" for="excel_file">Upload Excel-File</label>
  <div class="col-md-4">
	<input type="file" name="beneficiaries_data" class="filestyle" data-buttonName="btn-primary" accept=".csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain">
 </div>
<div class="form-group">
  <div class="col-md-1 temp_submit">
    <button id="upload" class="btn btn-primary">Upload Data</button>
  </div>
 <a href="{{url()}}/download" class="btn btn-large pull-right">
<div class="row">
<div class="col-md-1">
 <span class="glyphicon glyphicon-download fa-lg"></span>
 </div>
<div class="col-md-1">
 Download Sample Excel-File  
</div>
</div>
</a>


</div>

<br>


 <div class="form-group">
 <div class="col-md-4">

 </div>
  <div class="col-md-4 ">
<div id="final_submit" style="display:none;">
  Are you sure you want to submit?
  <button type="submit" class="btn btn-primary">Yes</button>
  <button  id="dont_submit" class="btn btn-primary">No</button>
</div>
</div>

</div>

 <div class="form-group">
 <div class="col-md-4">

 </div>
  <div class="col-md-4 ">
<div id="choose_file" style="display:none;">
    <div class="alert alert-danger">
      <a href="#" class="close" aria-label="close">&times;</a>
        File Missing.
    </div>

</div>
</div>

</div>

</fieldset>
</form>

</div>
@if(Session::has('count_excelupload_warning.count'))
@if(Session::get('count_excelupload_warning.count')!=0)
    <div class="alert alert-warning ">
    <b>Warnings</b>
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <table class="table">
    <tr>
     <th>S No. as per excel</th><th>Warnings&nbsp;(Total Warnings:&nbsp;&nbsp;{{Session::get('count_excelupload_warning.count')}})</th>  
    </tr>
      
      @for ($i = 0; $i < Session::get('count_excelupload_warning.count'); $i++)
      <tr>
        <td>{{Session::get('count_excelupload_warning'.$i)}}</td>
        <td><b>{{Session::get('count_excelupload_warning.message'.$i)}}</b></td>
      </tr>  
      @endfor
      </table>
    </div>
@endif
@endif
@if(Session::has('count_excelupload_data_repeated.count')||Session::has('count_excelupload_errors.count'))
<div class="alert alert-danger">    
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

<b>Errors occurred while saving following data.</b><br>
     <table class="table">
    <tr >
     <th>S No. as per excel</th><th>Error&nbsp;(Total errors:&nbsp;&nbsp;{{Session::get('count_excelupload_data_repeated.count')+Session::get('count_excelupload_errors.count')}})</th>  
    </tr>

@if(Session::get('count_excelupload_data_repeated.count')!=0)
    
  @for ($i = 0; $i < Session::get('count_excelupload_data_repeated.count'); $i++)
      <tr>
        <td>{{Session::get('count_excelupload_data_repeated'.$i)}}</td>
        <td><b>{{Session::get('count_excelupload_data_repeated.message'.$i)}}</b></td>
      </tr>  
      @endfor    
@endif
@if(Session::has('count_excelupload_errors.count'))
@if(Session::get('count_excelupload_errors.count')!=0)
      @for ($i = 0; $i < Session::get('count_excelupload_errors.count'); $i++)
      <tr>
        <td>{{Session::get('count_excelupload_errors'.$i)}}</td>
        <td><b>{{Session::get('count_excelupload_errors.message'.$i)}}</b></td>
      </tr>  
      @endfor
@endif
@endif
</table>
</div>
@endif

@if(Session::has('data_validated'))
<form method="POST" action="{{url()}}/data/final_upload">
<input type="hidden" name="_token" value="{{ csrf_token() }}">    
<button class="btn btn-primary">Save Data</button>
</form>
@endif


@if(Session::has('message'))
  	<div class="alert alert-success uploaded_mothers">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
         	{{Session::get('message')}}
    </div>
@endif
<script>
$('.close').on('click',function(){

    $('#choose_file').attr('style','display:none;');
});
  $('#upload').on('click',function(e){
    e.preventDefault();

      if($('input:file').val()=='')
      {
          $('#choose_file').attr('style','');
      }
      else
      {
        $('#choose_file').attr('style','display:none;');
       $('#final_submit').attr('style','');
       $('.temp_submit').css('display','none');
      }
  });
  $('#dont_submit').on('click',function(e)
  {
      e.preventDefault();
      $('#final_submit').attr('style','display:none;');     
      $('.temp_submit').css('display','');  
  });
</script>
</body>
