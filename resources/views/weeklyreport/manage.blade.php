<?php // echo count($beneficiaries);exit; ?>
<?php 
$cstartdate = date('d/m/Y',strtotime('last sunday'));
$cenddate = date('d/m/Y',strtotime('this saturday'));
$state=isset($state) && $state!="all"?$state:"";
$city=isset($city) && $city!="all"?$city:"";
$taluka=isset($taluka) && $taluka!="all" ?$taluka:"";

$userinfo=Session::get('user_logged');
$startdate=isset($startdate)?date('d/m/Y',strtotime($startdate)):"";
$enddate=isset($enddate)?date('d/m/Y',strtotime($enddate)):"";
$datelable="";
if($startdate!="" && $enddate!=""){
	$datelable=$startdate." to ".$enddate;
}	 
$languagedata= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
 
?>
@include('template/admin_title')
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
    	<h1><?php echo trans('routes.weeklycalllist'); ?></h1>
	</div>
	<div id="breadcrumb">
    	<a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="Go to Home" class="tip-bottom"><i class="icon-home"></i><?php  echo trans('routes.home'); ?></a><a class="current"><?php echo trans('routes.weeklycalllist'); ?></a> 
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

      
  	
    	<form class="" accept-charset="utf-8" method="POST" id="frmSearchBeni" name="frmSearchBeni" action="<?php echo Config::get('app.url').'admin/weeklycalllist/searchbenificiarydata'; ?>">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="startDate" id="startDate" value="">
			<input type="hidden" name="endDate" id="endDate" value="">
		<div class="span4" style="float:left;">
    	<div style="width: 100%;position: relative;">
    		<input  style="width: 70%;" placeholder="<?php echo trans('routes.sdate'); ?>" readonly="readonly" type="text" name="searchStartDate" id="searchStartDate" class="uneditable-input input-new search-input required" title="<?php echo trans('routes.sdate'); ?>" value="<?php echo $datelable; ?>" onkeyup="clearText(this.id)" >
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
        
        <div class="span5 custom-label pull-right"  style="margin:0px">
    	<label>{{ trans('routes.exportreport') }}</label>

        <label>
    		<a class="btn btn-primary" href="javascript:void(0);" id="btnDownload"><i class="icon-plus-sign icon-white"></i> {{ trans('routes.download') }}</a>
  		</label>
     	<div class="span4" style="float:left;">
    	<select id="sltftype">
    		<option value="xls">{{ trans('routes.excelformat') }}</option>
    		<option value="pdf">{{ trans('routes.pdfformat') }}</option>
    	
    	</select>
    	</div>
        </div>
        
        </div>
        
        <?php echo Session::get('message'); ?>
 		   	<?php //echo $this->session->flashdata('dispMessage');?>
		   	<form class="form-horizontal" accept-charset="utf-8" role="form" method="POST" id="frmList" name="frmList" action="<?php echo url().'/admin/weeklycalllist/downloadreport'; ?>">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="ftype" id="ftype" value="xls">
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box table-responsive">
						<div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
				            <h5><?php //echo ucfirst($formTitle);?></h5>
          				</div>
			 <div class="widget-content">
			 <div id="replace_pagecontant">
				<div id="no-more-tables">
                    <div id="service_table" class="service_table">
                    <table class="table table-hover with-check table-condensed cf">
                       <thead class="cf mar-btn">
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" onclick="checkedAll('frmList');" /></th>
                  <th>{{ trans('routes.uniqueid') }}</th>
                  <th>{{ trans('routes.name') }}</th>
                  <th>{{ trans('routes.location') }}</th>
				  <th>{{ trans('routes.phonenumber') }}</th>
				  <th>{{ trans('routes.alternateno') }}</th>
				  <th>{{ trans('routes.interventionpoint') }}</th>
				  @if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1) 
				    <th>{{ trans('routes.callchampion') }}</th> 
				  @endif
			    </tr>
              </thead>
              <input type="hidden" name="hdnRepId" id="hdnRepId"  value="">
              <input type="hidden" name="hdnRepStart" id="hdnRepStart"  value="<?php echo $startdate;?>">
              <input type="hidden" name="hdnRepEnd" id="hdnRepEnd"  value="<?php echo $enddate;?>">
              <?php if(!empty($result) && count($result) > 0) { ?>
              <tbody>
              	<?php foreach ($result as $value){ ?>
			       <tr>
			        <td><input type="checkbox" name="chkCheckedBox[]" id="chkCheckedBox"  class="CheckedBox" value="<?php echo $value['bi_id']; ?>" /></td>
                    <td data-title="{{ trans('routes.uniqueid') }}"><a href="<?php echo url().'/admin/beneficiary/view/'.Hashids::encode($value['bi_id']); ?>" ><?php echo $value['v_unique_code'];?></a></td>
                    <td data-title="{{ trans('routes.name') }}"><a href="<?php echo url().'/admin/beneficiary/view/'.Hashids::encode($value['bi_id']); ?>" ><?php echo $value['v_name'];?></a></td>
					<td @if(isset($value['v_village']) && $value['v_village']!="") data-title="{{ trans('routes.location') }}" @endif>@if(isset($value['v_village']) && $value['v_village']!=""){{{  $value['v_village'] or '' }}}, {{{ $value['v_taluka'] or '' }}}, {{{  $value['v_district'] or '' }}} @endif</td>
     				<td data-title="{{ trans('routes.phonenumber') }}">{{ $value['v_phone_number'] or '' }}</td>
					<td @if($value['v_alternate_phone_no']!="") data-title="{{ trans('routes.alternateno') }}" @endif>{{ $value['v_alternate_phone_no'] or '' }}</td>
					<td data-title="{{ trans('routes.interventionpoint') }}">{{ $value['intervention_date'] or '' }}</td>
				 @if(session('user_logged')['v_role']==0 || session('user_logged')['v_role']==1)    <td @if($value['champ_name']!="") data-title="{{ trans('routes.callchampion') }}" @endif>{{{ $value['champ_name'] or '' }}}</td> @endif
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
            </div></div>
            <!-- End of nomoretables -->
            <div class="pagination" style="float:right;clear:both;"><button id="btnLoadMore" style="display:{{ $morebutton or 'none' }}" type="button" class="btn btn-default"><i class="fa fa-refresh"></i> {{ trans('routes.loadmore') }}</button></div>
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
<script src="{{ url() }}/external/js/jquery.livequery.min.js"></script>
<script>
$(document).ready(function(){
	var token=$("#_token").val();
	var siteurl="<?php echo url(); ?>";
	
	var startDate;
	var endDate;
	
	$('#searchStartDate').datepicker({
	        format: "dd/mm/yyyy",
		    autoclose: true
	    }).on('changeDate', function(dateText, inst) { 
	            var date = $(this).datepicker('getDate');
				startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
	            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
	            $('#startDate').val(myDateFormatter (startDate));
	            $('#endDate').val(myDateFormatter (endDate));
	            $('#searchStartDate').val($('#startDate').val()+" to "+$('#endDate').val());
	            window.location.href = siteurl+'/admin/weeklycalllist/searchbenificiarydata/'+str_replace("/","-",$('#startDate').val());
	            //$("#frmSearchBeni").submit();
	        });


	/* Ajax Pagination for Beneficiary Array */
	var numclicks = 1;
	var total_count = "<?php echo (isset($count) && isset($perPage)) ? ceil($count / $perPage) - 1 : 0;  ?>";

	
	if(total_count>0){
		$('#btnLoadMore').removeAttr('disabled');
	}
	
	$('#btnLoadMore').click(function(){
		
		$.ajax({
			url: siteurl+"/admin/weeklycalllist/showmore",
			type:"POST",
			data:{
		        '_token':token,
		        'currentPage': numclicks
			},
			beforeSend:function(){
				$('#loaderdiv').fadeIn();
			},
			success:function(data){
				$('#loaderdiv').fadeOut();
				if(data!=""){
					$('#replace_pagecontant tbody').append(data);		
				}
			}
		})
		
		
		if(numclicks==total_count){
			$(this).attr('disabled','disabled');
			$(this).html('<i class="icon-ban-circle"></i> <?php echo trans('routes.noload'); ?>');
		}
		numclicks++;
			
	})
	/* End Pagination */	


	$('#btnDownload').livequery(function(){ 
	    	$(this).click(function() { 
	    		var val = [];
	    	    var checkedValues = $('tbody input:checkbox:checked').map(function() {
	    	        return this.value;
	    	    }).get();
	    	    
	    	    if(checkedValues==""){
					alert("<?php echo trans('routes.pleaseselectben'); ?>");
	    	    }else{
		    		$('#ftype').val($("#sltftype").val());
	    	   		document.frmList.submit();
	    	    }
       	})
  	})
});


</script>

<script>
function showDatePicker(){ 
		 $("#searchStartDate").datepicker("show");
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
  
  
