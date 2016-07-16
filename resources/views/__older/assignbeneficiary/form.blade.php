<?php
$duedate=strtotime($result->dt_due_date) != 0?date('d/m/Y',strtotime($result->dt_due_date)):"";
$deliverydate=strtotime($result->dt_delivery_date) != 0?date('d/m/Y',strtotime($result->dt_delivery_date)):"";
$calldur=$result->i_call_duration != 0?$result->i_call_duration:"";
$lmpdate=strtotime($result->dt_lmp_date) != 0?date('d/m/Y',strtotime($result->dt_lmp_date)):"";
$benlabel= trans('routes.edit');
$action='update';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@include('template/admin_cssscripta')
<style>
.report-table{border-top: 1px solid #00000a; border-left: 1px solid #00000a;margin-top:10px;width: 100%; }
.report-table td{border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in}
</style>
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
@include('template/script_multilanguage')
<div id="content">
  <div id="content-header">
    <h1><?php  echo trans('routes.assignben'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php  echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php  echo trans('routes.home'); ?></a><a href="<?php echo Config::get('app.url'); ?>admin/assignbeneficiary"><?php  echo trans('routes.assignben'); ?></a><a class="current"><?php echo $benlabel;?></a></div>
  <div class="container-fluid">
  <div id="errorInsertion"></div>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5><?php echo $benlabel;?> <?php  echo trans('routes.assignben'); ?></h5>
          </div>
          <form class="form-horizontal" role="form" method="POST" id="reportForm" name="reportForm" action="<?php echo Config::get('app.url').'admin/assignbeneficiary/'.$action; ?>">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			<input type="hidden" id="hdnId" name="hdnId" value="<?php echo $result->report_id; ?>" >
			<input type="hidden" id="hdnFieldId" name="hdnFieldId" value="<?php echo $result->bi_field_worker_id; ?>" >
		  <div class="row-fluid">
            <div class="widget-content nopadding">
			<!--table class="report-table" >
			<tbody>
			<tr>
			<td COLSPAN=9>
				<p ALIGN=CENTER ><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=3><b><?php echo trans('routes.personaldata'); ?></b></FONT></FONT></FONT></p>
			</td>
		</tr>
            <tr>
            <td BGCOLOR="#a6a6a6">
				<p ALIGN=CENTER><FONT COLOR="#ffffff"><b><?php  echo trans('routes.name'); ?></b></FONT></p>
			</td>
			<td BGCOLOR="#a6a6a6">
				<p ALIGN=CENTER><FONT COLOR="#ffffff"><b><?php  echo trans('routes.husbandname'); ?></b></FONT>
				</p>
			</td>
			<td BGCOLOR="#a6a6a6">
				<p ALIGN=CENTER><FONT COLOR="#ffffff"><b><?php  echo trans('routes.awcvame'); ?></b></FONT></p>
			</td>
			<td BGCOLOR="#a6a6a6">
				<p ALIGN=CENTER><FONT COLOR="#ffffff"><b><?php  echo trans('routes.phonenumber'); ?></b></FONT></p>
			</td>
			<td BGCOLOR="#a6a6a6">
				<p ALIGN=CENTER><FONT COLOR="#ffffff"><b><?php  echo trans('routes.lmpdate'); ?></b></FONT></p>
			</td>
			<td BGCOLOR="#a6a6a6">
				<p ALIGN=CENTER><FONT COLOR="#ffffff"><b><?php  echo trans('routes.deliverydate'); ?></b></FONT></p>
			</td>
			<td BGCOLOR="#a6a6a6">
				<p ALIGN=CENTER><FONT COLOR="#ffffff"><b><?php echo trans('routes.uniqueid'); ?></b></FONT></p>
			</td>
			<td BGCOLOR="#a6a6a6">
				<p ALIGN=CENTER><FONT COLOR="#ffffff"><b><?php  echo trans('routes.altphonenumber'); ?></b></FONT></p>
			</td>
			<td BGCOLOR="#a6a6a6">
				<p ALIGN=CENTER><FONT COLOR="#ffffff"><FONT FACE="Arial, serif"><FONT SIZE=3><b><?php  echo trans('routes.fieldworkername'); ?></b></FONT></FONT></FONT></p>
			</td>
			</tr>
			<tr>
			<td><p ALIGN=CENTER><b><?php echo $result->v_name; ?></b></p></td>
			<td><p ALIGN=CENTER><b><?php echo $result->v_husband_name; ?></b></p></td>
			<td><p ALIGN=CENTER><b><?php echo $result->v_awc_name; ?></b></p></td>
			<td><p ALIGN=CENTER><b><?php echo $result->v_phone_number; ?></b></p></td>
			<td><p ALIGN=CENTER><b><?php echo $lmpdate; ?></b></p></td>
			<td><p ALIGN=CENTER><b><?php echo $deliverydate; ?> </b></p></td>
			<td><p ALIGN=CENTER><b><?php echo $result->v_unique_code; ?></b></p></td>
			<td><p ALIGN=CENTER><b><?php echo $result->v_alternate_phone_no; ?></b></p></td>
			<td><p ALIGN=CENTER><b><?php echo $result->field_worker_name; ?></b></p></td>
		</tr>
			</tbody>
			</table-->
			 <table class="table table-bordered table-striped" style="border: 1px solid #ddd;margin: 1%;width: 98%;">
          <tbody>
           <tr>
              <td colspan="4" style="text-align:center"><b><?php  echo trans('routes.personaldata'); ?></b></td>
            </tr>
           <tr>
              <td><b><?php  echo trans('routes.fieldworkername'); ?></b></td>
              <td><?php echo $result->field_worker_name; ?></td>
              <td><b><?php  echo trans('routes.fieldworkernumber'); ?></b></td>
              <td><?php echo $result->field_worker_number; ?></td>
            </tr>
             <tr>
              <td><b><?php  echo trans('routes.name'); ?></b></td>
              <td><?php echo $result->v_name; ?></td>
              <td><b><?php  echo trans('routes.husbandname'); ?></b></td>
              <td><?php echo $result->v_husband_name; ?></td>
            </tr>
            <tr>
              <td><b><?php  echo trans('routes.phonenumber'); ?></b></td>
              <td><?php echo $result->v_phone_number; ?></td>
              <td><b><?php  echo trans('routes.altphonenumber'); ?></b></td>
              <td><?php echo $result->v_alternate_phone_no; ?></td>
            </tr>
            <tr>
              <td><b><?php  echo trans('routes.duedate'); ?></b></td>
              <td><?php echo $duedate;?></td>
              <td><b><?php  echo trans('routes.deliverydate'); ?></b></td>
              <td><?php echo $deliverydate;?></td>
            </tr>
            <tr>
              <td><b><?php  echo trans('routes.awcvame'); ?></b></td>
              <td><?php echo $result->v_awc_name;?></td>
              <td><b><?php  echo trans('routes.awcnumber'); ?></b></td>
              <td><?php echo $result->v_awc_number;?></td>
            </tr>
          </tbody>
        </table>
              <div class="row-fluid">
                  <div class="span6 clearfix">
                 
                  <!--   <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.fieldworkername'); ?></label>
                      <div class="controls">
                      	  <input type="text" class="required" maxlength="20" readonly="readonly" name="txtFieldworkerName" id="txtFieldworkerName" value="<?php echo $result->field_worker_name; ?>">
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.fieldworkernumber'); ?></label>
                      <div class="controls">
                      	  <input type="text" class="required" maxlength="20" readonly="readonly" name="txtFieldworkerNumber" id="txtFieldworkerNumber" value="<?php echo $result->field_worker_number; ?>">
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.name'); ?></label>
                      <div class="controls">
                      	  <input type="text" class="required" maxlength="20" readonly="readonly" name="txtUsername" id="txtUsername" value="<?php echo $result->v_name; ?>">
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.husbandname'); ?></label>
                      <div class="controls">
                      	  <input type="text" class="required" maxlength="20" readonly="readonly" name="txtHusbname" id="txtHusbname" value="<?php echo $result->v_husband_name; ?>">
                       </div>
                    </div>  
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.phonenumber'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="15" class="required" readonly="readonly" name="txtPhoneNumber" id="txtPhoneNumber" value="<?php echo $result->v_phone_number; ?>">
                       </div>
                    </div> 
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.altphonenumber'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="15" name="txtAltPhoneNumber" readonly="readonly" id="txtAltPhoneNumber" value="<?php echo $result->v_alternate_phone_no; ?>">
                       </div>
                    </div> 
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.nopregnancies'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="2" name="txtNumberPregnancies" readonly="readonly" id="txtNumberPregnancies" value="<?php echo $result->i_number_pregnancies; ?>">
                       </div>
                    </div>
                     <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.awcvame'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="20"  name="txtAwcName" readonly="readonly" id="txtAwcName" value="<?php echo $result->v_awc_name; ?>">
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.awcnumber'); ?></label>
                      <div class="controls">
                      	  <input type="text" maxlength="10"  name="txtAwcNumber" id="txtAwcNumber" readonly="readonly" value="<?php echo $result->v_awc_number; ?>">
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.duedate'); ?></label>
                      <div class="controls" style="position: relative;">
                      	  <input type="text" readonly="readonly" name="txtDueDate" id="txtDueDate" value="<?php echo $duedate; ?>">
                          <a class="add-on" href="javascript:void(0);"><i class="icon-calendar"></i></a>	
                       </div>
                    </div>
                    <div class="control-group text-center">
                      OR
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.deliverydate'); ?></label>
                      <div class="controls" style="position: relative;">
                      	  <input type="text" readonly="readonly" name="txtDeliveryDate" id="txtDeliveryDate" value="<?php echo $deliverydate; ?>">
                       	<a class="add-on" href="javascript:void(0);"><i class="icon-calendar"></i></a>	
                       </div>
                    </div>-->
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.beforcall'); ?></label>
                      <div class="controls">
                      	  <select name="txtBeforCall" id="txtBeforCall" >
						  	<option <?php if($result->v_befor_call=="Yes"){ echo "selected"; } ?> value="Yes" >Yes</option>
						  	<option <?php if($result->v_befor_call=="No"){ echo "selected"; } ?> value="No">No</option>
						  </select> 
					   </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.conversation'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                       <textarea class="form-control" rows="4" cols="" maxlength="5000" name="txtConversation" id="txtConversation"><?php echo $result->v_conversation; ?></textarea>
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.callduration'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                      	  <input type="text" maxlength="2" max="60"  name="txtCallDuration" id="txtCallDuration" value="<?php echo $calldur; ?>">
                       </div>
                    </div>
                    
                    </div>
	             </div>
                </div>
                <div class="form-actions">
                  <div class="splitFormSubmitButton">
                    	<input type="submit" class="btn btn-primary" name="save" value="<?php  echo trans('routes.submit'); ?>" >
                    <a href="<?php echo Config::get('app.url');?>admin/assignbeneficiary" class="btn btn-danger"><?php  echo trans('routes.cancel'); ?></a>
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
@include('template/admin_jsscript')
</body>
</html>
<script src="<?php echo Config::get('constant.SITEURL'); ?>external/js_admin/jquery.validate.js"></script>
<script src="<?php echo Config::get('constant.SITEURL'); ?>external/js/customevalidation.js"></script> 
<script type="application/javascript">
var token=$("#_token").val();
$(document).ready(function(){
	$("#reportForm").validate({
	ignore: ":hidden",
	rules: {
		txtConversation: {
			required: true,
			minlength:20
		},
		txtCallDuration: {
			required: true,
			validateNumber: true
		}
	},
	messages: 
	{
		txtConversation: {
			required: "",
			minlength:js_conv_message
		},
		txtCallDuration: {
			required: "",
			max:js_max_milit60,
			validateNumber: js_allow_number
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
		
	  // Check if the result is not null
		$("#txtTaluka").val("");
		$("#txtDistrict").val("");
		$("#txtState").val("");
		$("#txtCountry").val("");
		
	  if(result!=null)

	  {				

		//$("#txtAddress").val(result.village);
		$("#txtTaluka").val(result.taluka);
		$("#txtDistrict").val(result.district);
		$("#txtState").val(result.state);
		$("#txtCountry").val(result.country);
		//$().val(result.village);
	  }						 

	},		

});
$(document).ready(function() {
    $('form:first *:input[type!=hidden]:first').focus();
});
</script>

