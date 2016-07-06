<?php 
$cstartdate = date('d/m/Y',strtotime('last sunday'));
$cenddate = date('d/m/Y',strtotime('this saturday'));
$state=isset($state) && $state!="all"?$state:"";
$city=isset($city) && $city!="all"?$city:"";
$taluka=isset($taluka) && $taluka!="all" ?$taluka:"";
//$tag=isset($searchTag)?$searchTag:"";
//if($tag!="")
	//$display="display:block;";
//else
//	$display="display:none;";
$userinfo=Session::get('user_logged');
$startdate=isset($startdate)?date('d/m/Y',strtotime($startdate)):"";
$enddate=isset($enddate)?date('d/m/Y',strtotime($enddate)):"";
$datelable="";
if($startdate!="" && $enddate!=""){
	$datelable=$startdate." to ".$enddate;
}	 
$languagedata= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@include('template/admin_cssscripta')
<style>
.custom-label label{float:left;width:25%;margin-left:6%;} 
</style>
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
@include('template/script_multilanguage')
<div id="content">
	<div id="content-header">
    	<h1><?php echo trans('routes.assignben'); ?></h1>
	</div>
	<div id="breadcrumb">
    	<a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="Go to Home" class="tip-bottom"><i class="icon-home"></i><?php  echo trans('routes.home'); ?></a><a class="current"><?php echo trans('routes.assignben'); ?></a> 
   	</div>
	<div class="container-fluid"> 
    <br>
    	<div class="span12" style="margin-left:0;">
    	<!-- div class="span4" style="float:left;">
    	<div style="width: 20%"><?php  echo trans('routes.search'); ?></div>
		<div style="width: 80%;position: relative;" id="keywordtextbox">
			<input placeholder="<?php  echo trans('routes.searchlabel'); ?>" type="text" name="searchadministrator" id="searchadministrator" style="width:99%;" class="input-new search-input" title="<?php  echo trans('routes.searchlabel'); ?>" value="<?php //echo $tag; ?>" onkeyup="clearText(this.id)" >
			<?php //if($tag!=""){?>
			<a class="search-reset searchadministrator" id="search-reset" style="<?php //echo $display; ?>" href="<?php //echo Config::get('app.url').'admin/assignbeneficiary'?>"></a>
			<?php //}else{?>
			<div class="search-reset searchadministrator" id="search-reset" onClick="hidesearch(this.id);"></div>
			<?php //}?>
	    </div 
    	</div>-->
    	<div class="span5 custom-label"  style="margin:0px">
    	<label><?php echo trans('routes.weekof')?></label>
    	<?php if(count($result) > 0) { ?>
        <label>
    		<a href="javascript:void(0);" onclick="return checkreport();"><?php  echo trans('routes.downloadreport'); ?></a>
  		</label>
  		<?php }?>
    	<form class="" accept-charset="utf-8" method="POST" id="frmSearchBeni" name="frmSearchBeni" action="<?php echo Config::get('app.url').'admin/assignbeneficiary/searchbenificiarydata'; ?>">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="startDate" id="startDate" value="">
			<input type="hidden" name="endDate" id="endDate" value="">
		<div class="span4" style="float:left;">
    	<div style="width: 100%;position: relative;">
    		<input  style="width: 70%;" placeholder=" <?php echo trans('routes.sdate'); ?>" readonly="readonly" type="text" name="searchStartDate" id="searchStartDate" class="uneditable-input input-new search-input required" title="<?php echo trans('routes.sdate'); ?>" value="<?php echo $datelable; ?>" onkeyup="clearText(this.id)" >
			<a href="javascript:showDatePicker();" style="position: relative;" class="add-on"><i class="icon-calendar" style="right: 5px;top: -3px;"></i></a>
        </div>
    	</div>
    	<!--  div class="span1" style="float:left;">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
			<button type="button" name="searchTaluka" id="searchTaluka" style="width:99%;" class="btn btn-primary" onclick="showbeficiarybydate();" ><?php echo trans('routes.searchbtn'); ?></button>
	    </div>
	    </div>-->
	    </form>
        </div>
        </div>
        <div class="span10" style="margin-left:0;">
    	<div class="span2" style="float:left;">
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
    	<div class="span2" style="float:left;">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
    	<?php if(!empty($cityarr)){?>
    	<select id="city" name="city" onchange="findTaluka(this.value);"  style="width: 190px;">
    	<option value=""><?php  echo trans('routes.disrictlabel'); ?></option>
    	<?php foreach ($cityarr as $k=>$v){?>
    	<option value="<?php echo strtolower($v->v_district); ?>"><?php echo $v->v_district; ?></option>
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
    	<div class="span2" style="float:left;">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
		<?php if(!empty($talukaarr)){?>
		<select id="taluka" name="taluka"  style="width: 190px;">
    	<option value=""><?php  echo trans('routes.talukalabel'); ?></option>
    	<?php foreach ($talukaarr as $k=>$v){?>
    	<option value="<?php echo strtolower($v->v_taluka); ?>"><?php echo $v->v_taluka; ?></option>
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
        <div class="span1" style="float:left;">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
			<a name="searchTaluka" id="searchTaluka" style="width:99%;" class="btn btn-primary" href="javascript:void(0);" onclick="searchAddress();" ><?php  echo trans('routes.searchbtn'); ?></a>
	    </div>
        </div>
        <?php if($state!="" || $city!="" || $taluka!=""){?>
        <div class="span1" style="float:left;">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
			<a style="width:99%;" class="btn btn-primary" href="<?php echo Config::get('app.url').'admin/assignbeneficiary';?>"><?php  echo trans('routes.reset'); ?></a>
	    </div>
        </div>
        <?php }?>
        <?php if(session('callchampid')!=""){?>
        <div class="span1" style="float:left;">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
			<a style="width:99%;" class="btn btn-primary" href="<?php echo Config::get('app.url').'admin/callchampions';?>"><?php  echo trans('routes.back'); ?></a>
	    </div>
        </div>
        <?php }?>
        </div>
        <?php echo Session::get('message'); ?>
        <?php  
			//$attributes = array('class' => 'form-horizontal', 'id' => 'frmList', 'name' => 'frmList');
			//echo form_open(SITEURLADM.$cntrlName.'/deleteSelected',$attributes);?>
		   	<?php //echo $this->session->flashdata('dispMessage');?>
		   	<form class="form-horizontal" accept-charset="utf-8" role="form" method="POST" id="frmList" name="frmList" action="<?php echo Config::get('app.url').'admin/assignbeneficiary/downlaodreport'; ?>">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box">
						<div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
				            <h5><?php //echo ucfirst($formTitle);?></h5>
          				</div>
			 <div class="widget-content ">
			 <div id="replace_pagecontant">
			 <table class="table table-bordered table-striped table-hover with-check">
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" onclick="checkedAll('frmList');" /></th>
                  <th><?php  echo trans('routes.uniqueid'); ?></th>
                  <th><?php echo trans('routes.name'); ?></th>
                  <th><?php echo trans('routes.husbandname'); ?></th>
				  <th><?php  echo trans('routes.language'); ?></th>
				  <th><?php  echo trans('routes.phonenumber'); ?></th>
				  <th><?php  echo trans('routes.action'); ?></th>
			    </tr>
              </thead>
              <input type="hidden" name="hdnRepId" id="hdnRepId"  value="">
              <input type="hidden" name="hdnRepStart" id="hdnRepStart"  value="<?php echo $startdate;?>">
              <input type="hidden" name="hdnRepEnd" id="hdnRepEnd"  value="<?php echo $enddate;?>">
              <?php if(count($result) > 0) { ?>
              <tbody>
              	<?php foreach ($result as $value){
              		 	$check="";
              			foreach ($languagedata as $lang){
              				if($value->v_language!=""){
              					$lanarr=explode(",", $value->v_language);
              					if(in_array($lang->bi_id,$lanarr))
              						$check.=$lang->v_language.", ";
              				}
              			}
              		?>
			        <tr>
			          <td><input type="checkbox" name="chkCheckedBox[]" id="chkCheckedBox"  class="CheckedBox" value="<?php echo $value->report_id; ?>" /></td>
                      <td><?php echo $value->v_unique_code;?></td>
                      <td><?php echo $value->v_name;?></td>
                      <td><?php echo $value->v_husband_name;?></td>
					  <td><?php echo ucwords(trim(trim($check),","));?></td>
					  <td><?php echo $value->v_phone_number;?></td>
					  <td>
					  <?php if($startdate>=$cstartdate  && $userinfo['v_role']==2){
					  	if($value->i_is_edit==1){
              				$label=trans('routes.edit');
						}else{
              				$label=trans('routes.fillreport');
              			}	?>
					  <a href="<?php echo Config::get('app.url').'admin/assignbeneficiary/edit/'.Hashids::encode($value->report_id); ?>" class="btn btn-info"><i class="icon-edit icon-white"></i><?php echo $label; ?></a>
					  <?php }else{?>
					  <a href="<?php echo Config::get('app.url').'admin/assignbeneficiary/view/'.Hashids::encode($value->report_id); ?>" class="btn btn-info"><?php echo trans('routes.view'); ?></a>
					  <?php }?>
					  </td>
					</tr>                	
                <?php } ?>
              </tbody>
              <?php } else { ?>
              <tbody>
              	  <tr>
                    <td colspan="10"><center><em><?php echo trans('routes.norecord'); ?></em></center></td>
                  </tr>
              </tbody>
              <?php } ?>
            </table>
            <div class="pagination" style="float:right;clear:both;"><?php echo $result->render(); ?></div>
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
<script src="<?php echo Config::get('constant.SITEURL'); ?>external/js/customevalidation.js"></script> 
<script>
var token=$("#_token").val();
var siteurl="<?php echo Config::get('app.url')?>";
function showDatePicker(){ 
	 $("#searchStartDate").datepicker("show");
}
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
	            window.location.href = siteurl+'admin/assignbeneficiary/searchbenificiarydata/'+str_replace("/","-",$('#startDate').val());
	            //$("#frmSearchBeni").submit();
	        });
});
       $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getPosts(page);
            }
        }
    });

    $(document).ready(function() {
        $(document).on('click', '.pagination a', function (e) {
            getPosts($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
    });

    function getPosts(page) {
        $.ajax({
            url : siteurl+'admin/beneficiary?page=' + page,
            dataType: 'html',
        }).done(function (data) {
            $('#replace_pagecontant').html(data);
            location.hash = page;
        }).fail(function () {
        	alert('Posts could not be loaded.');
        });
    }
    function myDateFormatter (dt) {
        var d = new Date(dt);
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();
        if (day < 10) {
            day = "0" + day;
        }
        if (month < 10) {
            month = "0" + month;
        }
        var date = day + "/" + month + "/" + year;

        return date;
    }; 


    /* Download Button */
    
    </script>

  <script language="javascript" type="text/javascript">
  $('#searchState, #searchDistrict, #searchTaluka').bind('keypress', function(e){
  	if(e.keyCode==13){
  		searchAddress();		
	}
  });
    
					$('#searchadministrator').bind('keypress', function(e){
						$("#search-reset").show();	

						if(e.keyCode==13)

						{

						  if(!$('#hdnData').val())	

						  {		

							  var value = $('#searchadministrator').val();

							  if(value !=""){		  

							  window.location="<?php echo Config::get('app.url'); ?>admin/assignbeneficiary/searchdatabeneficiary?search="+encodeURIComponent(value);

							  }

						  }	  

						}

					});

					

					$("#searchadministrator").coolautosuggest({

						

						url:"<?php echo Config::get('app.url'); ?>admin/assignbeneficiary/autocompletebeneficiary?chars=",

						idField:$("#hdnData"),

					//	submitOnSelect:true,

						onSelected:function(result){

							if(result.fullname==1)

							{

									var fullname=result.fullname;	

							}

						  // Check if the result is not null

						  if(result!=null)

						  {				

						  				  				  

						  		window.location="<?php echo Config::get('app.url'); ?>admin/assignbeneficiary/searchdatabeneficiary/"+result.id+'/'+result.data;

						  }						 

						},		

					});
					
					$("#searchState").coolautosuggest({
						url:"<?php echo Config::get('app.url'); ?>admin/beneficiary/autocompletebenaddress?flag=v_state&chars=",
						idField:$("#hdnData"),
						onSelected:function(result){
						  // Check if the result is not null
						  if(result!=null){				
						  }						 
						},		
					});
					$("#searchDistrict").coolautosuggest({
						url:"<?php echo Config::get('app.url'); ?>admin/beneficiary/autocompletebenaddress?flag=v_district&chars=",
						idField:$("#hdnData"),
						onSelected:function(result){
						  // Check if the result is not null
						  if(result!=null){				
						  }						 
						},		
					});
					$("#searchTaluka").coolautosuggest({
						url:"<?php echo Config::get('app.url'); ?>admin/beneficiary/autocompletebenaddress?flag=v_taluka&chars=",
						idField:$("#hdnData"),
						onSelected:function(result){
						  // Check if the result is not null
						  if(result!=null){				
						  }						 
						},		
					});
					function searchAddress(){
						var state=$("#state").val()!=""?$("#state").val():"all";
						var dist=$("#city").val()!=""?$("#city").val():"all";
						var taluka=$("#taluka").val()!=""?$("#taluka").val():"all";
						var startdate="<?php echo str_replace("/", "-", $startdate); ?>";
						var enddate="<?php echo str_replace("/", "-", $enddate); ?>";
						if(state!="all" || dist!="all" || taluka!="all"){
							window.location="<?php echo Config::get('app.url'); ?>admin/assignbeneficiary/searchdataaddress/"+state+'/'+dist+'/'+taluka+'/'+startdate+'/'+enddate;
						}else{
							alert(js_validadrress);
						}
					}
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
function strtolower(str) {
	 	  return (str + '')
	    .toLowerCase();
	}
function showbeficiarybydate(){
	if($("#searchStartDate").val()!=""){
		$("#frmSearchBeni").submit();
	}
 }
function checkreport(msg){
	var val = [];
    $('.CheckedBox:checkbox').each(function(i){
		  val[i] = $(this).val();
    });
    $("#hdnRepId").val(val.join(","));
	document.frmList.submit();
}
function str_replace(search, replace, subject, count) {
	  	var i = 0,
	    j = 0,
	    temp = '',
	    repl = '',
	    sl = 0,
	    fl = 0,
	    f = [].concat(search),
	    r = [].concat(replace),
	    s = subject,
	    ra = Object.prototype.toString.call(r) === '[object Array]',
	    sa = Object.prototype.toString.call(s) === '[object Array]';
	  s = [].concat(s);
	  if (count) {
	    this.window[count] = 0;
	  }

	  for (i = 0, sl = s.length; i < sl; i++) {
	    if (s[i] === '') {
	      continue;
	    }
	    for (j = 0, fl = f.length; j < fl; j++) {
	      temp = s[i] + '';
	      repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
	      s[i] = (temp)
	        .split(f[j])
	        .join(repl);
	      if (count && s[i] !== temp) {
	        this.window[count] += (temp.length - s[i].length) / f[j].length;
	      }
	    }
	  }
	  return sa ? s : s[0];
	}
</script> 

