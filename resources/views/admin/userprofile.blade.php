<?php 
if($result->v_profile_pic=="")
	$profile_pic="{{ url() }}/external/profile_picture/mother_care.jpg";
else
	$profile_pic="{{ url() }}/external/profile_picture/".$result->v_profile_pic;
$birthdata=strtotime($result->dt_birthdate) != 0?date('d/m/Y',strtotime($result->dt_birthdate)):"";

$village=array();
if($result->v_pincode!="")
	$village=DB::table('mct_address')->distinct()->select('v_village','v_village_pincode','bi_id')->where('v_pincode', $result->v_pincode)->get();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/callchampion_sidebar')
@include('template/script_multilanguage')
<div id="content">
  <div id="content-header">
    <h1><?php echo trans('routes.profile'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="{{ url() }}/dashboard" title="<?php echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php echo trans('routes.home'); ?></a><a class="current"><?php echo trans('routes.profile'); ?></a></div>
  <div class="container-fluid">
  <div id="errorInsertion"></div>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5><?php echo trans('routes.profile'); ?></h5>
          </div>
          <?php echo Session::get('message') ?>
          @if (count($errors) > 0)
		  	<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		 @endif
            <form class="form-horizontal" role="form" method="POST" id="frmEditProfile" name="frmEditProfile" enctype="multipart/form-data" action="{{ url() }}/editprofile">
				<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="hdnId" id="hdnId" value="<?php echo $result->bi_id; ?>" >
				<input type="hidden" id="hdUserId" name="hdUserId" value="<?php echo $result->bi_user_login_id; ?>" >
			        <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.name'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                        <input type="text" maxlength="20" name="txtUsername" id="txtUsername" value="<?php echo $result->v_name; ?>" >
                      </div>
                    </div>
                    
                    <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.email'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                        <input type="text" name="txtEmail" id="txtEmail" value="<?php echo $result->v_email; ?>" >
                      </div>
                    </div>
                     <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.language'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      <?php foreach ($languagedata as $lang){
                      	$check="";
                      	if($result->v_language!=""){
                      		$lanarr=explode(",", $result->v_language);
                      		if(in_array($lang->bi_id,$lanarr))
                      			$check="checked";
                      	}
                      	?>
                      	<label class="checkbox-custome">
                      	 <input type='checkbox' name='txtLanguage[]' class="checkbox" <?php echo $check; ?> value='<?php echo $lang->bi_id; ?>'><?php echo ucwords($lang->v_language); ?>
    				   </label>
    				   <?php }?>
    				   </div>
    				   <br/>
    				   <label class="error" generated="true" for="txtLanguage[]" style="text-align: center;"></label>
                    </div>
                     <div class="control-group">
                    <label for="txtProfilePic" class="col-sm-4 control-label">Profile Picture</label>
                    <div class="controls" id="profileImg">
                    <div class="col-sm-6"> <img height="70" width="70" src="<?php echo $profile_pic; ?>" /> </div>
                  </div>
                  <div class="col-sm-2" style=" margin-left: 17%;" >
                    	<div class="fileUpload btn btn-primary">
    						<span>Select Photo</span>
    						<input type="file" id="txtProfilePic" name="txtProfilePic" class="upload" value="<?php if($result->v_profile_pic)?>" />
						</div>
                    </div>
                     <label class='text-center'>* File size less then 2MB</label>
                  <label for="txtProfilePic" generated="true" class="error"></label>
                  </div>
                  <div class="control-group" id="bName">
                      <label class="control-label"><?php echo trans('routes.birthdate'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls" style="position: relative;">
                      	  <input type="text" readonly="readonly" class="required" name="txtBirthDate" id="txtBirthDate" value="<?php echo $birthdata; ?>">
                      	  <a style="position: relative;" href="javascript:showDatePicker();" class="add-on"><i class="icon-calendar" style=" right: 5px;top: 2px;"></i></a>
                       </div>
                       <label class="error" generated="true" for="txtBirthDate" style="text-align: center;"></label>
                    </div>
                    
                    <!-- Marital Status -->
                      <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.marital'); ?></label>
                      <div class="controls">
                      	<select name="txtMaritalStatus" id="txtMaritalStatus" >
						  <option <?php if($result->e_marital_status=="Single"){ echo "selected"; } ?> value="Single" >Single</option>
						  <option <?php if($result->e_marital_status=="Married"){ echo "selected"; } ?> value="Married">Married</option>
						</select> 
					 	</div>
                     </div>
                   
                   <!-- Motherhood status for Call Champion only -->
                    @if(Session::get('user_logged')['v_role']=="2")
                    <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.motherhood'); ?></label>
                      <div class="controls">
                      <input type="hidden" name="txtMotherhoodStatus" id="txtMotherhoodStatus" value="No">
                      	  <select name="MotherhoodStatus" id="MotherhoodStatus" <?php if($result->e_marital_status!="Married"){ echo "disabled='disabled'"; } ?>  >
						  	<option <?php if($result->e_motherhood_status=="Yes"){ echo "selected"; } ?> value="Yes" >Yes</option>
						  	<option <?php if($result->e_motherhood_status=="No"){ echo "selected"; } ?> value="No">No</option>
						  </select> 
					   </div>
                    </div>
                    @endif
                    
                     <!-- Gender for all except Call Champion -->
                     @if(Session::get('user_logged')['v_role']!="2")
                     <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.gender'); ?></label>
                      <div class="controls">
                      	  <select name="txtGenderStatus" id="txtGenderStatus" >
						  	<option <?php if($result->e_gender=="Male"){ echo "selected"; } ?> value="Male" >Male</option>
						  	<option <?php if($result->e_gender=="Female"){ echo "selected"; } ?> value="Female">Female</option>
						  </select> 
					   </div>
                     </div>
                    @endif
                    
                    
                    
                    <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.phonenumber'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                        <input type="text" maxlength="15" name="txtPhoneNumber" id="txtPhoneNumber" value="<?php echo $result->v_phone_number; ?>" >
                      </div>
                    </div>
<div class="control-group">
                      <label class="control-label"><?php echo trans('routes.profession'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" class="required"  name="txtProfession"  maxlength="20" id="txtProfession" value="<?php echo $result->v_profession; ?>">
                       </div>
                    </div>
                    <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.zipcode'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	<input type="text" maxlength="8"  class="required" name="txtZipcode" id="txtZipcode" value="<?php echo $result->v_pincode; ?>">
                        <input type="hidden" class="required" name="hdnZipcode" id="hdnZipcode" value="<?php echo $result->i_address_id; ?>">
                       </div>
                    </div>
                      <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.village'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <select name="txtAddress" id="txtAddress" onchange="setzipid(this.value)">
                      	  <option value="" ><?php echo trans('routes.villagelbl'); ?></option>
                      	  <?php if(count($village)>0){
                      	  foreach ($village as $key=>$val){
                      	  	$check="";
                      	  	if($val->bi_id==$result->i_address_id)
                      	  		$check="selected";
							?>
                      	  	<option <?php echo $check; ?> value="<?php echo $val->bi_id; ?>"><?php echo $val->v_village."(".$val->v_village_pincode.")";?></option>
                      	  <?php }}?>
						  </select>	                       
                       </div>
                    </div>
                    <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.taluka'); ?></label>
                      <div class="controls">
                      	  <input type="text" readonly="readonly" maxlength="20" name="txtTaluka" id="txtTaluka" value="<?php echo $result->v_taluka; ?>">
                       </div>
                    </div>
                    <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.city'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="20" readonly="readonly" name="txtDistrict" id="txtDistrict" value="<?php echo $result->v_district; ?>">
                       </div>
                    </div>
                    <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.state'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="20" readonly="readonly" name="txtState" id="txtState" value="<?php echo $result->v_state; ?>">
                       </div>
                    </div>
                    <div class="control-group" id="bName">
                      <label class="control-label"><?php  echo trans('routes.country'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="20" readonly="readonly" name="txtCountry" id="txtCountry" value="<?php echo $result->v_country; ?>">
                       </div>
                    </div>
                                 </div>
                <div class="form-actions">
                  <div class="splitFormSubmitButton">
                    <button type="submit" class="btn btn-primary"><?php echo trans('routes.submit'); ?></button>
                    <a href="{{ url() }}/admin" onClick="btnCancel();" class="btn btn-danger"><?php echo trans('routes.cancel'); ?></a>
                  </div>
                </div>
           </form>
        </div>
      </div>
    </div>
     @include('template/admin_footer')
  </div>
</div>
<script type="text/javascript">
var token=document.getElementById('_token').value;
var siteurl="{{ url() }}";
</script>
@include('template/admin_jsscript')
</body>
</html>
<script src="{{ url() }}/external/js_admin/jquery.validate.js"></script> 
<script src="{{ url() }}/external/js/customevalidation.js"></script> 
<script>
function showDatePicker(){ 
	 $("#txtBirthDate").datepicker("show");
}
$(document).ready(function(){
   	$(function() {
			$( "#txtBirthDate" ).datepicker({  
				format: "dd/mm/yyyy",
			    endDate: "today",
			    autoclose: true
			});
		});
   });  
var token=$("#_token").val();
$(document).ready(function(){
		$("#frmEditProfile").validate({
		ignore: ":hidden",
		rules: {
			txtUsername: {
				required: true,
				userName:true,
				maxlength:20
			},
			txtPhoneNumber: {
				required: true,
				validateNumber: true,
				minlength:10, 
				maxlength:15
			},
			txtProfession:{
				required: true,
				validName:true
			},
			txtLanguage:{
				checkbox: true				
			},
			txtBirthDate: {
				required: true
				//ofAge:true
			},
			txtEmail: {
				required: true,
				validEmail: true,
				remote: {
					data: {'_token' : token,'action':'update','hdUserId':$("#hdUserId").val()},
					url : '{{ url() }}/checkEmail',
					type : 'post'
				}
			},
			txtZipcode:{
				required: true,
				validateNumber:true,
				maxlength:10,
				remote: {
					data: {'_token' : token},
					url : siteurl+'admin/beneficiary/checkZipcode',
					type : 'post'
				}
			},
			txtAddress:{
				required: true,
				validName:true,
				maxlength:20
			},
			txtProfilePic:{
				validFilesize: true,
				extension: true
			}
		},
		messages: 
		{
			txtUsername: {
				required: "",
				userName:js_allow_user
			},
			txtPhoneNumber: {
				required: "",
				minlength: js_number_limit10,
				validateNumber: js_allow_number
			},
			txtEmail: {
				required: "",
				validEmail: js_allow_email,
				remote: jQuery.format(js_allow_alr_email)
			},
			txtProfession:{
				required: "",
				validName:js_allow_name
			},
			txtBirthDate: {
				required: ""
				//ofAge: js_birthlimit
			},
			txtZipcode:{
				required: "",
				validateNumber: js_allow_number,
				remote: jQuery.format(js_validzip)
			},
			txtAddress: {
				required: "",
				validName: js_allow_name
			},
			txtProfilePic:{
				validFilesize: js_validfilesiz,
				extension : js_validfile
			}
		}
		});
		
});                      	
$(document).ready(function() {
    $('form:first *:input[type!=hidden]:first').focus();
});

$("#txtMaritalStatus").change(function(){
	var mrgstatus=$(this).val();
	if(mrgstatus=="Single"){
		$('#MotherhoodStatus option[value=No]').attr('selected','selected');
		$('#txtMotherhoodStatus').val('No');
		$('#MotherhoodStatus').attr('disabled', 'disabled');
	}else if(mrgstatus=="Married"){
		$('#MotherhoodStatus').removeAttr("disabled");
	}
});
$("#MotherhoodStatus").change(function(){
	var mrgstatus=$(this).val();
		$('#txtMotherhoodStatus').val(mrgstatus);
});

$("#txtZipcode").coolautosuggest({
	url:"{{ url() }}admin/beneficiary/autocompleteaddress?chars=",
	idField:$("#hdnZipcode"),
	//submitOnSelect:true,
	onSelected:function(result){
		filladress($("#hdnZipcode").val(),$("#txtZipcode").val());
		fillvillage($("#txtZipcode").val());
	},		
});
$(document).on('keydown',"#txtZipcode",function(e) {
	if(e.keyCode==13 || e.keyCode==9){
		filladress($("#hdnZipcode").val(),$("#txtZipcode").val());
		fillvillage($("#txtZipcode").val());
	}	
});
</script>
</body>
</html>