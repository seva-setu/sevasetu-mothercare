<?php 
$citydis="";
$statedis="";
$talukadis="";
if($city!="")
	$citydis="display:block;";
if($state!="")
	$statedis="display:block;";
if($taluka!="")
	$talukadis="display:block;";
?>
@include('template/admin_title')
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
@include('template/script_multilanguage')
<div id="content">
  <div id="content-header">
    <h1><?php  echo trans('routes.location'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="{{ url() }}/admin/dashboard" title="<?php  echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php  echo trans('routes.home'); ?></a><a href="{{ url() }}admin/addlocation"><?php  echo trans('routes.location'); ?></a><a class="current"><?php  echo trans('routes.add'); ?></a></div>
  <div class="container-fluid">
  <div id="errorInsertion"></div>
    <div class="row-fluid">

      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5><?php  echo trans('routes.addloc'); ?></h5>
          </div>
          <?php  echo Session::get('message'); ?>
          <form class="form-horizontal" role="form" method="POST" id="frmBeneficiary" name="frmBeneficiary" action="{{ url() }}admin/editlocation">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="hdnAddId" id="hdnAddId" value="">
			<div class="row-fluid">
            <div class="widget-content nopadding">
              <div class="row-fluid">
                  <div class="span6 clearfix">
                    <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.zipcode'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	<input type="text" maxlength="8"  class="required" name="txtZipcode" id="txtZipcode" value="">
                        <input type="hidden" class="required" name="hdnZipcode" id="hdnZipcode" value="">
                       </div>
                    </div>
                    <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.taluka'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" maxlength="20" class="required" name="txtTaluka" id="txtTaluka" value="">
                       </div>
                    </div>
                    <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.city'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" maxlength="20" class="required" name="txtDistrict" id="txtDistrict" value="">
                       </div>
                    </div>
                     </div>
                    <div class="span6 clearfix">
                    <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.state'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" maxlength="20" class="required" name="txtState" id="txtState" value="">
                       </div>
                    </div>
                    <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.country'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" maxlength="20" class="required" name="txtCountry" id="txtCountry" value="">
                       </div>
                    </div>
                     <div class="control-group">
                  	<div class="control-label" style="padding-top:11px;">
                    	<input type="submit" class="btn btn-primary" name="save" value="<?php  echo trans('routes.submit'); ?>" >
                    </div>
                    <div class="controls">
                    <a href="javascript:void(0)" onclick="addressreset();" class="btn btn-danger"><?php  echo trans('routes.cancel'); ?></a>
                  	</div>
                	</div>
                     </div>
	             </div>
                </div>
            </div>
                <div class="span12" style="float:left;margin-bottom: 10px;margin-top: 10px;">
		<div ><?php echo trans('routes.searchadd');?></div>
    	<div class="span2" style="float:left;margin:0;width:195px">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
    	<select id="state" name="state" onchange="findCity(this.value);" style="width: 190px;">
    	<option value=""><?php  echo trans('routes.statelabel'); ?></option>
    	<?php 
    	$states=array('Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jammu and Kashmir','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha(Orissa)','Punjab','Rajasthan','Sikkim','Tamil Nadu','Tripura','Uttar Pradesh','Uttarakhand','West Bengal');
    	?>
    	<?php foreach ($states as $k=>$v){?>
			<option value="<?php echo strtolower($v); ?>" <?php echo strtolower($state)==strtolower($v)? "selected" : ""; ?>><?php echo ucwords($v); ?></option>
        <?php }?>
        </select>
        </div>
    	</div>
    	<div class="span2" style="float:left;margin:0;width:195px">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
    	<?php if(!empty($cityarr)){?>
    	<select id="city" name="city" onchange="findTaluka(this.value);"  style="width: 190px;">
    	<option value=""><?php  echo trans('routes.disrictlabel'); ?></option>
    	<?php foreach ($cityarr as $k=>$v){
    		$selected="";
    		if(strtolower($v->v_district)==$city)
    			$selected="selected";
    	?>
    	<option <?php echo $selected; ?> value="<?php echo strtolower($v->v_district); ?>"><?php echo $v->v_district; ?></option>
    	<?php }?>
    	</select>
    	<?php }else{?>
    	<select id="city" name="city" onchange="findTaluka(this.value);"  style="width: 190px;">
    	<option value=""><?php  echo trans('routes.disrictlabel'); ?></option>
    	<?php if($city!=""){?>
    	<option value="<?php echo $city; ?>" selected><?php echo $city; ?></option>
    	<?php }?>
    	</select>
    	<?php }?>
	    </div>
    	</div>
    	<div class="span2" style="float:left;margin:0;width:195px">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
		<?php if(!empty($talukaarr)){?>
		<select id="taluka" name="taluka"  style="width: 190px;">
    	<option value=""><?php  echo trans('routes.talukalabel'); ?></option>
    	<?php foreach ($talukaarr as $k=>$v){
    		$selected="";
    		if(strtolower($v->v_taluka)==$taluka)
    			$selected="selected";
    	?>
    	<option <?php echo $selected; ?> value="<?php echo strtolower($v->v_taluka); ?>"><?php echo $v->v_taluka; ?></option>
    	<?php }?>
    	</select>
    	<?php }else{?>
    	<select id="taluka" name="taluka"  style="width: 190px;">
	    <option value=""><?php  echo trans('routes.talukalabel'); ?></option>
    	<?php if($taluka!=""){?>
    	<option value="<?php echo $taluka; ?>" selected><?php echo $taluka; ?></option>
    	<?php }?>
    	</select>
    	<?php }?>
    	</div>
        </div>
        <div class="span2" style="float:left;margin:0">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
			<button type="button" name="searchTaluka" id="searchTaluka" style="width:99%;" class="btn btn-primary" onclick="searchAddress();" ><?php  echo trans('routes.searchbtn'); ?></button>
	    </div>
        </div>
        <?php if($state!="" || $city!="" || $taluka!=""){?>
        <div class="span1" style="float:left;margin:0">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
			<a style="width:99%;" class="btn btn-primary" href="{{ url() }}admin/addlocation"><?php  echo trans('routes.reset'); ?></a>
	    </div>
        </div>
        <?php }?>
    	</div>
            <?php if(count($result)>0){?>
          <table class="table table-bordered table-striped table-hover with-check">
              <thead>
                <tr>
                  <th><?php  echo trans('routes.zipcode'); ?></th>
                  <th><?php  echo trans('routes.taluka'); ?></th>
				  <th><?php  echo trans('routes.city'); ?></th>
				  <th><?php  echo trans('routes.state'); ?></th>
				  <th><?php  echo trans('routes.country'); ?></th>
				  <th><?php  echo trans('routes.action'); ?></th>
			    </tr>
              </thead>
             <tbody>
             <?php foreach($result as $key=>$val){?>
              	<tr>
                	<td><?php echo $val->v_pincode; ?></td>
                    <td><?php echo $val->v_taluka; ?></td>
                    <td><?php echo $val->v_district; ?></td>
					<td><?php echo $val->v_state; ?></td>
					<td><?php echo $val->v_country; ?></td>
					<td style="width: 150px">
                     	<a   onclick="javascript:editaddress('<?php echo Hashids::encode($val->bi_id); ?>');" href="javascript:void(0)" class="btn btn-info"><i class="icon-edit icon-white"></i>Edit</a>
					</td>
                 </tr>
                 <?php }?>                	
              </tbody>
                </table>
            <?php }?>
          </div>
	       <!--</form>-->
        </div>
      </div>
    </div>
     @include('template/admin_footer')
  </div>
</div>
@include('template/admin_jsscript')
</body>
</html>
<script src="{{ url() }}/external/js_admin/jquery.validate.js"></script>
<script src="{{ url() }}/external/js/customevalidation.js"></script>
<script type="application/javascript">
var token=$("#_token").val();
var siteurl="{{ url() }}";
$(document).ready(function(){
		$("#frmBeneficiary").validate({
		ignore: ":hidden",
		rules: {
			txtZipcode:{
				required: true,
				validateNumber:true,
				maxlength:8
			},
			txtTaluka:{
				required: true,
				validName:true,
				maxlength:20
			},
			txtDistrict:{
				required: true,
				validName: true,
				maxlength:20
			},
			txtState:{
				required: true,
				validName: true,
				maxlength:20
			},
			txtCountry:{
				required: true,
				validName: true,
				maxlength:20
			}
		},
		messages: 
		{
			txtZipcode:{
				required: "",
				validateNumber: js_allow_number
			},
			txtTaluka:{
				required: "",
				validName: js_allow_name
			},
			txtDistrict:{
				required: "",
				validName: js_allow_name
			},
			txtState:{
				required: "",
				validName: js_allow_name
			},
			txtCountry: {
				required: "",
				validName: js_allow_name
			 }
			
		}
		});
});
$(document).ready(function() {
    $('form:first *:input[type!=hidden]:first').focus();
});
function findCity(state){
	if(state!=""){
		 $.ajax({
	         url : siteurl+'admin/beneficiary/getcitylists',
	         type: "POST",
	         dataType: 'json',
	         data:{'_token':token,'state':state},
	     }).done(function (data) {
	         $("#city").html("");
	         $("#city").append('<option value="">'+js_disrictlabel+'</option>');
	         $("#taluka").html("");
	         $("#taluka").append('<option value="">'+js_talukalabel+'</option>');
	         $.each(data,function(key,value){
	        	 $("#city").append('<option value="'+strtolower(value.v_district)+'">'+value.v_district+'</option>');	
	         });
	     }).fail(function () {
	     	alert('Posts could not be loaded.');
	     });
	} 
}
function findTaluka(city){
	if(city!=""){
		 $.ajax({
	         url : siteurl+'admin/beneficiary/gettalukalists',
	         type: "POST",
	         dataType: 'json',
	         data:{'_token':token,'city':city},
	     }).done(function (data) {
	         $("#taluka").html("");
	         $("#taluka").append('<option value="">'+js_talukalabel+'</option>');
	         $.each(data,function(key,value){
	        	 $("#taluka").append('<option value="'+strtolower(value.v_taluka)+'">'+value.v_taluka+'</option>');	
	         });
	     }).fail(function () {
	     	alert('Posts could not be loaded.');
	     });
	} 
}
function  editaddress(id){
	$("#hdnAddId").val("");
	 $("#txtZipcode").val("");
	 $("#txtTaluka").val("");
	 $("#txtDistrict").val("");
	 $("#txtState").val(""); 
	 $("#txtCountry").val(""); 
	 $.ajax({
         url : siteurl+'admin/editaddress',
         type: "POST",
         dataType: 'json',
         data:{'_token':token,'id':id},
     }).done(function (data) {
         if(data.bi_id!=undefined){
	    	 $("#hdnAddId").val(data.bi_id);
	    	 $("#txtZipcode").val(data.v_pincode);
	    	 $("#txtTaluka").val(data.v_taluka);
	    	 $("#txtDistrict").val(data.v_district);	
	    	 $("#txtState").val(data.v_state);
	    	 $("#txtCountry").val(data.v_country);
	    	 $( "#txtZipcode" ).focus();
         } 
     }).fail(function () {
     	alert('Posts could not be loaded.');
     });
}
function strtolower(str) {
	 	  return (str + '')
	    .toLowerCase();
	}
function searchAddress(){
	var state=$("#state").val()!=""?$("#state").val():"all";
	var dist=$("#city").val()!=""?$("#city").val():"all";
	var taluka=$("#taluka").val()!=""?$("#taluka").val():"all";
	if(state!="all" || dist!="all" || taluka!="all"){
		window.location="{{ url() }}/admin/searchdataaddress/"+state+'/'+dist+'/'+taluka;
	}else{
		alert(js_validadrress);
	}
}
function addressreset(){
	$("#frmBeneficiary")[0].reset();
} 
</script>

