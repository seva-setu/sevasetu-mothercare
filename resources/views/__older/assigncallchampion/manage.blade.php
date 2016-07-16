<?php 
$startdate=isset($startdate)?$startdate:"";
$enddate=isset($enddate)?$enddate:"";
if($startdate!="")
	$sdisplay="display:block;";
else
	$sdisplay="display:none;";
if($enddate!="")
	$edisplay="display:block;";
else
	$edisplay="display:none;";
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
@include('template/admin_sidebar')
@include('template/script_multilanguage')
<div id="content">
	<div id="content-header">
    	<h1><?php echo trans('routes.assigncall'); ?></h1>
	</div>
	<div id="breadcrumb">
    	<a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php echo trans('routes.home'); ?></a><a class="current"><?php echo trans('routes.assigncall'); ?></a> 
   	</div>
	<div class="container-fluid"> 
    <br>
    	<?php  echo Session::get('message'); ?>
    	<div class="span11" style="margin-left:0;">
    	<div class="span5">
    	<form class="" accept-charset="utf-8" method="POST" id="frmSearchBeni" name="frmSearchBeni" action="<?php echo Config::get('app.url').'admin/beneficiary/searchbenificiarydata'; ?>">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="startDate" id="startDate" value="">
			<input type="hidden" name="endDate" id="endDate" value="">
		<div class="span3" style="float:left;">
    	<div style="width: 100%;position: relative;">
    	<label><?php echo trans('routes.weekof')?></label>
			<input  placeholder=" <?php echo trans('routes.sdate'); ?>" readonly="readonly" type="text" name="searchStartDate" id="searchStartDate" class="uneditable-input input-new search-input required" title="<?php echo trans('routes.sdate'); ?>" value="<?php echo $startdate; ?>" onkeyup="clearText(this.id)" >
			<div class="search-reset searchStartDate" id="search-reset"  style="<?php echo $sdisplay; ?>"  onClick="hidesearch(this.id);"></div>
			<a href="javascript:showDatePicker();" style="position: relative;" class="add-on"><i class="icon-calendar" style="right: 5px;top: -3px;"></i></a>
        </div>
    	</div>
    	<div class="span1" style="float:left;">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
			<button type="button" name="searchTaluka" id="searchTaluka" style="width:99%;" class="btn btn-primary" onclick="searchAddress();" ><?php echo trans('routes.searchbtn'); ?></button>
	    </div>
	    </div>
	    </form>
	    </div>
	    <div class="span5" style="display:none;" id="callchampionlists">
    	<div class="span3">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
    	<?php if(!empty($callchampionlists)){ ?>
			<select id="callChamptions" style="width: 100%;" name="callChamptions" onChange="chnagecallchamption(this.value)">
			<option value="0">Select Call Champion</option>
			<?php foreach ($callchampionlists as $key=>$value){?>
			<option value="<?php echo $value->bi_id; ?>"><?php echo $value->v_name; ?></option>
			<?php }?>
			</select>
			<?php }?>
	    </div>
	    </div>
	    <div class="span1" style="float:right;">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
			<button type="button" name="searchTaluka" id="searchTaluka" style="width:99%;" class="btn btn-primary" onclick="assigncallchamption();" ><?php echo trans('routes.select'); ?></button>
	    </div>
	    </div>
	    </div>
	    </div>
        	<form class="form-horizontal" accept-charset="utf-8" role="form" method="POST" id="frmList" name="frmList" action="<?php echo Config::get('app.url').'admin/assigncallchampion/assigncallchamption'; ?>">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="callChamptionUser" id="callChamptionUser" value="">
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box">
						<div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
				            <h5><?php //echo ucfirst($formTitle);?></h5>
          				</div>
			<div class="widget-content ">
			<div id="beneficiary_data">
			<table class="table table-bordered table-striped table-hover with-check" >
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" onclick="checkedAll('frmList');" /></th>
                  <th><?php echo trans('routes.name'); ?></th>
                  <th><?php echo trans('routes.husbandname'); ?></th>
				  <th><?php echo trans('routes.language'); ?></th>
				  <th><?php echo trans('routes.phonenumber'); ?></th>
				  <th><?php echo trans('routes.village'); ?></th>
				  <th><?php echo trans('routes.week'); ?></th>
                </tr>
              </thead>
               <tbody>
              	   <tr id="loading-image" style="display: none;">
                    <td colspan="10"><center>
					<img src="<?php echo Config::get('app.url');?>external/images/loader.gif " >
				</td>
				</tr>
              	  <tr id="flush-td">
                    <td colspan="10"><center><em><?php echo trans('routes.norecord'); ?></em></center></td>
                  </tr>
              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    </form>
    <!--</form>-->
	<?php 
    //echo form_close();
    ?>
    @include('template/admin_footer')
  </div>
</div>
@include('template/admin_jsscript')
<script src="<?php echo Config::get('constant.SITEURL'); ?>external/js_admin/jquery.validate.js"></script> 
<script type="application/javascript">
function showDatePicker(){ 
	 $("#searchStartDate").datepicker("show");
}
var siteurl="<?php echo Config::get('app.url')?>";
var startDate;
var endDate;
$(document).ready(function(){
	    $('#searchStartDate').datepicker( {
	        format: "dd/mm/yyyy",
		    autoclose: true
	    }).on('changeDate', function(dateText, inst) { 
	            var date = $(this).datepicker('getDate');
				startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
	            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
	            $('#startDate').val(myDateFormatter (startDate));
	            $('#endDate').val(myDateFormatter (endDate));
	            $('#searchStartDate').val($('#startDate').val()+" to "+$('#endDate').val());
	            //window.location.href = siteurl+'admin/assignbeneficiary/searchbenificiarydata/'+str_replace("/","-",$('#startDate').val());
	            //$("#frmSearchBeni").submit();
	        });
});
	$(document).ready(function(){
		$("#frmassignBen").validate({
		ignore: ":hidden",
		rules: {
			callChamptions: {
				required: true
			}
		},
		messages: 
		{
			callChamptions: {
				required: ""
			}
		}
	});
});
$(document).ready(function() {
    $('form:first *:input[type!=hidden]:first').focus();
});
</script>
<script language="javascript" type="text/javascript">
 function searchAddress(){
	if($("#searchStartDate").val()!=""){
	 	  $.ajax({
	            type:"post",
	            url:siteurl+'admin/assigncallchampion/getbeneficiary',
	            data:$("#frmSearchBeni").serialize(),
	            beforeSend: function() {
	                $("#loading-image").show();
	                $("#flush-td").html("");
	             },
	            success:function(response){
	                $('#beneficiary_data').html(response);
	                $("#loading-image").hide();
	            }
	        });
	}
 }
 $('#chkCheckedBox').live('change',function () {
	 var checkedNum = $('input[name="chkCheckedBox[]"]:checked').length;
	 if(checkedNum)
		    $("#callchampionlists").show();  // checked
		else
		    $("#callchampionlists").hide();  // unchecked
});
function assigncallchamption(){
	var callchampion=$("#callChamptionUser").val();
	if(callchampion!=0)
		$("#frmList").submit();
	else
		alert("<?php echo trans("routes.valid_call"); ?>");
}
function chnagecallchamption(id){
	$("#callChamptionUser").val(id);
}
</script> 

