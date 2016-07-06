<?php 
if($action=="add")
	$adminlabel= trans('routes.add');
else
	$adminlabel= trans('routes.edit');
$birthdata=strtotime($result->dt_birthdate) != 0?date('d/m/Y',strtotime($result->dt_birthdate)):"";

if($result->v_profile_pic=="")
	$profile_pic=Config::get('app.url')."external/profile_picture/mother_care.jpg";
else
	$profile_pic=Config::get('app.url')."external/profile_picture/".$result->v_profile_pic;
$village=array();
if($result->v_pincode!="")
	$village=DB::table('mct_address')->distinct()->select('v_village','v_village_pincode','bi_id')->where('v_pincode', $result->v_pincode)->get();	

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
    <h1><?php echo trans('routes.adminuser'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php echo trans('routes.home'); ?></a><a href="<?php echo Config::get('app.url'); ?>admin/adminusrs"><?php echo trans('routes.adminuser'); ?></a><a class="current"><?php echo $adminlabel;?></a></div>
  <div class="container-fluid">
  <div id="errorInsertion"></div>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5><?php echo $adminlabel;?> <?php echo trans('routes.adminuser'); ?></h5>
          </div>
          <form class="form-horizontal" role="form" method="POST" id="frmCallChampion" enctype="multipart/form-data" name="frmCallChampion" action="<?php echo Config::get('app.url').'admin/adminusrs/'.$action; ?>">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			<input type="hidden" id="hdnId" name="hdnId" value="<?php echo $result->bi_id; ?>" >
			<input type="hidden" id="hdUserId" name="hdUserId" value="<?php echo $result->bi_user_login_id; ?>" >
          <div class="row-fluid">
            <div class="widget-content nopadding">
              <div class="row-fluid">
                  <div class="span12 clearfix">
                    <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.name'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" class="required"  maxlength="20" name="txtUsername" id="txtUsername" value="<?php echo $result->v_name; ?>">
                       </div>
                    </div>  
                    <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.phonenumber'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" maxlength="20"  class="required" name="txtPhoneNumber" id="txtPhoneNumber" value="<?php echo $result->v_phone_number; ?>">
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
                      <label class="control-label"><?php echo trans('routes.email'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" class="required" name="txtEmail" id="txtEmail" value="<?php echo $result->v_email; ?>">
                       </div>
                    </div>
                     <div class="control-group">
                    <label for="txtProfilePic" class="col-sm-4 control-label"><?php echo trans('routes.imagelabel'); ?><font color="#FF0000"> *</font></label>
                    <div class="controls" id="profileImg">
                    <div class="col-sm-6"> <img height="70" width="70" src="<?php echo $profile_pic; ?>" /> </div>
                  </div>
                  <div class="col-sm-2" style="text-align: center;" >
                    	<div class="fileUpload btn btn-primary">
    						<span><?php echo trans('routes.selectimage'); ?></span>
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
                      	  <a href="javascript:showDatePicker();" class="add-on"><i class="icon-calendar"></i></a>
                       </div>
                    </div>
                     <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.profession'); ?></label>
                      <div class="controls">
                      	  <input type="text" name="txtProfession"  maxlength="20" id="txtProfession" value="<?php echo $result->v_profession; ?>">
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.marital'); ?></label>
                      <div class="controls">
                      	<select name="txtMaritalStatus" id="txtMaritalStatus" >
						  <option <?php if($result->e_marital_status=="Single"){ echo "selected"; } ?> value="Single" >Single</option>
						  <option <?php if($result->e_marital_status=="Married"){ echo "selected"; } ?> value="Married">Married</option>
						</select> 
					 	</div>
                     </div>
                     <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.gender'); ?></label>
                      <div class="controls">
                      	  <select name="txtGenderStatus" id="txtGenderStatus" >
						  	<option <?php if($result->e_gender=="Male"){ echo "selected"; } ?> value="Male" >Male</option>
						  	<option <?php if($result->e_gender=="Female"){ echo "selected"; } ?> value="Female">Female</option>
						  </select> 
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
                      	  <select name="txtAddress" id="txtAddress" onChange="setzipid(this.value)">
                      	  <option value="" ><?php echo trans('routes.villagelbl'); ?></option>
                      	  <?php if(count($village)>0){
                      	  foreach ($village as $key=>$val){
                      	  	$check="";
                      	  	if($val->bi_id==$result->i_address_id)
                      	  		$check="selected";
							?>
                      	  	<option <?php echo $check; ?> value="<?php echo $val->bi_id; ?>"><?php echo $val->v_village." (".$val->v_village_pincode.")";?></option>
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
	             </div>
                </div>
                <div class="form-actions">
                  <div class="splitFormSubmitButton">
                    <input type="submit" class="btn btn-primary" name="save" value="<?php echo trans('routes.submit'); ?>" >
                    <?php if($result->bi_user_login_id!=0){?>
                    <a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/changeuserpassword/'.Hashids::encode($result->bi_user_login_id); ?>"><i class="icon-white icon-edit"></i><?php echo trans('routes.changepassword'); ?></a>					  	
                    <?php }?>
                    <a href="<?php echo Config::get('app.url'); ?>admin/adminusrs" class="btn btn-danger"><?php echo trans('routes.cancel'); ?></a>
                  </div>
                </div>
            </div>
          </div>
	       <!--</form>-->
        </div>
      </div>
    </div>
     @include('template/admin_footer')
  </div>
</div>
<script type="text/javascript">
var token=document.getElementById('_token').value;
var siteurl="<?php echo Config::get('app.url')?>";
</script>
@include('template/admin_jsscript')
</body>
</html>
<script src="<?php echo Config::get('constant.SITEURL'); ?>external/js_admin/jquery.validate.js"></script>
<script src="<?php echo Config::get('constant.SITEURL'); ?>external/js/customevalidation.js"></script> 
<script type="application/javascript">
function showDatePicker(){ 
	 $("#txtBirthDate").datepicker("show");
}
$(document).ready(function(){
   	$(function() {
			$( "#txtBirthDate" ).datepicker({  
				format: "dd/mm/yyyy",
			    endDate: "today",
			    autoclose: true
			}).on('changeDate', function(ev) {
			    if($('#txtBirthDate').valid()){
			        $('#txtBirthDate').removeClass('invalid').addClass('success');   
			     }
		});
   });
});
var token=$("#_token").val();
$(document).ready(function(){
		$("#frmCallChampion").validate({
		ignore: ":hidden",
		rules: {
			txtUsername: {
				required: true,
				userName:true,
				maxlength:20
			},
			
			txtPhoneNumber: {
				required: true,
				validMobileNumber : true,
				minlength:10, 
				maxlength:20
			},
			txtEmail: {
				required: true,
				validEmail: true,
				remote: {
					data: {'_token' : token,'action':'<?php echo $action; ?>','hdUserId':$("#hdUserId").val()},
					url : '<?php echo Config::get('app.url'); ?>admin/checkEmail',
					type : 'post'
				}
			},
			txtAddress: {
				selectcheck: true
			},
			txtBirthDate: {
				required: true
				//ofAge:true
			},
			txtLanguage: {
				checkbox: true
			},
			txtZipcode:{
				required: true,
				validateNumber:true,
				maxlength:10,
				remote: {
					data: {'_token' : token},
					url : siteurl+'admin/beneficiary/checkZipcode',
					type : 'post',
					asyc:false
				}
			},
			txtProfilePic:{
				validFilesize: true,
				extension: true
			},
			txtContactPersoneNo: "required"
		},
		messages: 
		{
			txtUsername: {
				required: "",
				userName:js_allow_user
			},
			txtPhoneNumber: {
				required: "",
				minlength:js_number_limit10,
				validMobileNumber : js_allow_number
				},
			txtEmail: {
				required: "",
				validEmail: js_allow_email,
				remote: jQuery.format(js_allow_alr_email)
			},
			txtAddress: {
				selectcheck: ""
			},
			txtBirthDate: {
				required: ""
				//ofAge:js_birthlimit
			},
			txtZipcode:{
				required: "",
				validateNumber: js_allow_number,
				remote: jQuery.format(js_validzip)
			},
			txtProfilePic:{
				validFilesize: js_validfilesiz,
				extension : js_validfile
			},
			txtContactPersoneName: "",
			txtContactPersoneNo: ""
		}
		});
});
$(document).ready(function() {
    $('form:first *:input[type!=hidden]:first').focus();
});
$("#txtZipcode").coolautosuggest({
	url:"<?php echo Config::get('app.url'); ?>admin/beneficiary/autocompleteaddress?chars=",
	idField:$("#hdnZipcode"),
	//submitOnSelect:true,
	onSelected:function(result){
		filladress($("#hdnZipcode").val(),$("#txtZipcode").val());
		fillvillage($("#txtZipcode").val());
	},		
});

$(document).on('keydown',"#txtZipcode",function(e) {
	console.log(e.keyCode);
	if(e.keyCode==13 || e.keyCode==9){
		filladress($("#hdnZipcode").val(),$("#txtZipcode").val());
		fillvillage($("#txtZipcode").val());
	}	
});
</script>

