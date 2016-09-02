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
<!-- File Button --> 
<div class="form-group">
  <label class="col-md-4 control-label" for="excel_file">{{ trans('upload_excel.Upload_Excel_File') }}</label>
  <div class="col-md-4">
	<input type="file" name="beneficiaries_data" class="filestyle" data-buttonName="btn-primary" accept=".csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
 </div>
<div class="form-group">
  <div class="col-md-1 temp_submit">
    <button id="upload" class="btn btn-primary">{{ trans('upload_excel.upload_data_button') }}</button>
  </div>
 <a href="{{url()}}/download" class="btn btn-large pull-right">
<div class="row">
<div class="col-md-1">
 <span class="glyphicon glyphicon-download fa-lg"></span>
 </div>
<div class="col-md-1">
 {{ trans('upload_excel.Download_Sample_Excel_File') }}  
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
  {{ trans('upload_excel.confirm_submit') }}
  <button type="submit" class="btn btn-primary">{{ trans('upload_excel.yes') }}</button>
  <button  id="dont_submit" class="btn btn-primary">{{ trans('upload_excel.no') }}</button>
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
        {{ trans('upload_excel.File_Missing') }}
    </div>

</div>
</div>

</div>

</fieldset>
</form>

</div>
@if(Session::has('go_back'))
@if(Session::get('go_back')==1)
<div class="row">
<div class="col-md-3">

 </div>
<div class="col-md-6">
    <div class="alert alert-danger ">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
 {{ trans('upload_excel.Incorrect_Excel_File_Format') }}  
    </div>
</div> 
</div>
@endif
@endif
@if(Session::has('count_excelupload_warning.count'))
@if(Session::get('count_excelupload_warning.count')!=0)
<div class="row">
<div class="col-md-3">
 </div>
<div class="col-md-6">
    <div class="alert alert-warning ">
    <b>{{ trans('upload_excel.Warnings') }}  
 </b>
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
     <table class="table">
    <tr>
     <th>{{ trans('upload_excel.Sno_warning_table') }}</th><th>{{ trans('upload_excel.Warnings') }}&nbsp;({{ trans('upload_excel.Total_Warnings') }}:&nbsp;&nbsp;{{Session::get('count_excelupload_warning.count')}})</th>  
    </tr>
      
      @for ($i = 0; $i < Session::get('count_excelupload_warning.count'); $i++)
      <tr>
        <td>{{Session::get('count_excelupload_warning'.$i)}}</td>
        <td><b>{{Session::get('count_excelupload_warning.message'.$i)}}</b></td>
      </tr>  
      @endfor
      </table>
    </div>
</div>
</div>
@endif
@endif
@if(Session::has('count_excelupload_data_repeated.count')||Session::has('count_excelupload_errors.count'))
<div class="row">
<div class="col-md-3">
 </div>
<div class="col-md-6">
<div class="alert alert-danger">    
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<b>{{ trans('upload_excel.Error_heading') }}</b><br>
     <table class="table">
    <tr >
     <th>{{ trans('upload_excel.Sno_warning_table') }}</th><th>{{ trans('upload_excel.Error') }}&nbsp;({{ trans('upload_excel.Total_Error') }}:&nbsp;&nbsp;{{Session::get('count_excelupload_data_repeated.count')+Session::get('count_excelupload_errors.count')}})</th>  
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
</div>
</div>
@endif

@if(Session::has('data_validated'))
@if(Session::get('data_validated')==1)
<div class="row">
<div class="col-md-3">
</div>
<div class="col-md-4 pull-right">
<form method="POST" action="{{url()}}/data/final_upload">
<input type="hidden" name="_token" value="{{ csrf_token() }}">    
<button class="btn btn-primary">{{ trans('upload_excel.confirm_upload') }}</button>
</form>
</div>
</div>
@endif
@endif


@if(Session::has('message'))
<div class="row">
<div class="col-md-3">
 </div>
<div class="col-md-6">

  	<div class="alert alert-success uploaded_mothers">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
         	{{Session::get('message')}}
    </div>
</div>
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
