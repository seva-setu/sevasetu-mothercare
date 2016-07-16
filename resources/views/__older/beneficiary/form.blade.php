<?php 
$duedate=strtotime($result->dt_due_date) > 0?date('d/m/Y',strtotime($result->dt_due_date)):"";
$deliverydate=strtotime($result->dt_delivery_date) > 0?date('d/m/Y',strtotime($result->dt_delivery_date)):"";

if($action=="add")
	$benlabel= trans('routes.add');
else 
	$benlabel= trans('routes.edit');
if(isset($_GET['userid']))
	$userid=$_GET['userid'];
else
	$userid="";

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
    <h1><?php  echo trans('routes.beneficiary'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php  echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php  echo trans('routes.home'); ?></a><a href="<?php echo Config::get('app.url'); ?>admin/beneficiary"><?php  echo trans('routes.beneficiary'); ?></a><a class="current"><?php echo $benlabel;?></a></div>
  <div class="container-fluid">
  <div id="errorInsertion"></div>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5><?php echo $benlabel;?> <?php  echo trans('routes.beneficiary'); ?></h5>
          </div>
          <form class="form-horizontal" role="form" method="POST" id="frmBeneficiary" name="frmBeneficiary" action="<?php echo Config::get('app.url').'admin/beneficiary/'.$action; ?>">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			<input type="hidden" id="hdnId" name="hdnId" value="<?php echo $result->bi_id; ?>" >
			<input type="hidden" id="hdnUserId" name="hdnUserId" value="<?php echo $userid; ?>" >
		  <div class="row-fluid">
            <div class="widget-content nopadding">
              <div class="row-fluid">
                  <div class="span12 clearfix">
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.name'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" class="required" maxlength="20" name="txtUsername" id="txtUsername" value="<?php echo $result->v_name; ?>">
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.husbandname'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" class="required" maxlength="20" name="txtHusbname" id="txtHusbname" value="<?php echo $result->v_husband_name; ?>">
                       </div>
                    </div>  
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.phonenumber'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" maxlength="15" class="required" name="txtPhoneNumber" id="txtPhoneNumber" value="<?php echo $result->v_phone_number; ?>">
                       </div>
                    </div> 
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.altphonenumber'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="15" name="txtAltPhoneNumber" id="txtAltPhoneNumber" value="<?php echo $result->v_alternate_phone_no; ?>">
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
                      	 <input type='checkbox' name='txtLanguage[]' class='checkbox' <?php echo $check; ?> value='<?php echo $lang->bi_id; ?>'><?php echo ucwords($lang->v_language); ?><br>
    				   </label>
    				   <?php }?>
                       </div>
                       <br/>
    				   <label class="error" generated="true" for="txtLanguage[]" style="text-align: center;"></label>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.nopregnancies'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="2" name="txtNumberPregnancies" id="txtNumberPregnancies" value="<?php echo $result->i_number_pregnancies; ?>">
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
                     <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.awcvame'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="20"  name="txtAwcName" id="txtAwcName" value="<?php echo $result->v_awc_name; ?>">
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.awcnumber'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="10"  name="txtAwcNumber" id="txtAwcNumber" value="<?php echo $result->v_awc_number; ?>">
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.duedate'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls" style="position: relative;">
                      	  <input type="text" readonly="readonly" name="txtDueDate" id="txtDueDate" value="<?php echo $duedate; ?>">
                          <a class="add-on" href="javascript:showDatePicker('txtDueDate');"><i class="icon-calendar"></i></a>	
                       </div>
                    </div>
                    <div class="control-group text-center">
                      OR
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.deliverydate'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls" style="position: relative;">
                      	  <input type="text" readonly="readonly" name="txtDeliveryDate" id="txtDeliveryDate" value="<?php echo $deliverydate; ?>">
                       	<a class="add-on" href="javascript:showDatePicker('txtDeliveryDate');"><i class="icon-calendar"></i></a>	
                       </div>
                    </div>
                     </div>
	             </div>
                </div>
                <div class="form-actions">
                  <div class="splitFormSubmitButton">
                    	<input type="submit" class="btn btn-primary" name="save" value="<?php  echo trans('routes.submit'); ?>" >
                    <a href="<?php echo Config::get('app.url');?>admin/beneficiary" class="btn btn-danger"><?php  echo trans('routes.cancel'); ?></a>
                  </div>
                </div>
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
var siteurl="<?php echo Config::get('app.url')?>";
</script>
@include('template/admin_jsscript')
</body>
</html>
<script src="<?php echo Config::get('constant.SITEURL'); ?>external/js_admin/jquery.validate.js"></script>
<script src="<?php echo Config::get('constant.SITEURL'); ?>external/js/customevalidation.js"></script> 
<script type="application/javascript">
function showDatePicker(id){ 
	 $("#"+id).datepicker("show");
}
$(document).ready(function(){
	$(function() {
			$( "#txtDueDate" ).datepicker({ 
				format: "dd/mm/yyyy",
			    endDate: "+270d",
			    autoclose: true,
			    startDate: "today"
			});
			$( "#txtDeliveryDate" ).datepicker({
				format: "dd/mm/yyyy",
			    endDate: "today",
			    autoclose: true,
			    startDate: "-270d"
			});
		});
   });
var token=$("#_token").val();
$(document).ready(function(){
		$("#frmBeneficiary").validate({
		ignore: ":hidden",
		rules: {
			txtUsername: {
				required: true,
				userName:true,
				minlength:3,
				maxlength:20
			},
			txtHusbname: {
				required: true,
				userName:true,
				minlength:3,
				maxlength:20
			},
			txtPhoneNumber: {
				required: true,
				validMobileNumber: true,
				minlength:10, 
				maxlength:20
			},
			txtLanguage: {
				checkbox: true
			},
			txtZipcode:{
				required: true,
				validateNumber:true,
				remote: {
					data: {'_token' : token},
					url : siteurl+'admin/beneficiary/checkZipcode',
					type : 'post'
				},
				maxlength:10
			},
			txtAddress:{
				selectcheck: true
			},
			txtDueDate: {
				validfield: true
			 },
			 txtDeliveryDate: {
				 validfield: true
			}
		},
		messages: 
		{
			txtUsername: {
				required: "",
				userName: js_allow_user
			},
			txtHusbname: {
				required: "",
				userName:js_allow_user
			},
			txtPhoneNumber: {
				required: "",
				minlength: js_number_limit10,
				validMobileNumber: js_allow_number
			},
			txtZipcode:{
				required: "",
				validateNumber: js_allow_number,
				remote: jQuery.format("This zipcode is invalid")
			},
			txtDueDate: {
				validfield: js_allow_date
			 },
			 txtDeliveryDate: {
				 validfield: js_allow_date
			},
			txtAddress: {
				selectcheck : ""
			}
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
	if(e.keyCode==13 || e.keyCode==9){
		filladress($("#hdnZipcode").val(),$("#txtZipcode").val());
		fillvillage($("#txtZipcode").val());
	}	
});
</script>

