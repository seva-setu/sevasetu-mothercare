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
@include('template/admin_title')
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
@include('template/script_multilanguage')
<div id="content">
	<div id="content-header">
    	<h1><?php echo trans('routes.beneficiary'); ?></h1>
	</div>
	<div id="breadcrumb">
    	<a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php echo trans('routes.home'); ?></a><a class="current"><?php echo trans('routes.beneficiary'); ?></a> 
   	</div>
	<div class="container-fluid"> 
    <br>
    	<div class="span10" style="margin-left:0;">
    	<form class="form-horizontal" accept-charset="utf-8" method="POST" id="frmSearchBeni" name="frmSearchBeni" action="<?php echo Config::get('app.url').'admin/beneficiary/searchbenificiarydata'; ?>">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="span3" style="float:left;">
    	<div style="width: 100%;position: relative;">
			<input  placeholder=" <?php echo trans('routes.sdate'); ?>" readonly="readonly" type="text" name="searchStartDate" id="searchStartDate" class="input-new search-input required" title="<?php echo trans('routes.sdate'); ?>" value="<?php echo $startdate; ?>" onkeyup="clearText(this.id)" >
			<div class="search-reset searchStartDate" id="search-reset"  style="<?php echo $sdisplay; ?>"  onClick="hidesearch(this.id);"></div>
        </div>
    	</div>
    	<div class="span3" style="float:left;">
    	<div style="width: 100%;position: relative;">
			<input placeholder=" <?php echo trans('routes.edate'); ?>" readonly="readonly" type="text" name="searchEndDate" id="searchEndDate" class="input-new search-input required" title="<?php echo trans('routes.edate'); ?>" value="<?php echo $enddate; ?>" onkeyup="clearText(this.id)" >
			<div class="search-reset searchEndDate" id="search-reset1"  style="<?php echo $edisplay; ?>"  onClick="hidesearch(this.id);"></div>
        </div>
    	</div>
    	<div class="span1" style="float:left;">
    	<div style="width: 100%;position: relative;" id="keywordtextbox">
			<button type="button" name="searchTaluka" id="searchTaluka" style="width:99%;" class="btn btn-primary" onclick="searchAddress();" ><?php echo trans('routes.searchbtn'); ?></button>
	    </div>
        </div>
        </form>
        </div>
        <?php echo Session::get('message'); ?>
        	<form class="form-horizontal" accept-charset="utf-8" role="form" method="POST" id="frmList" name="frmList" action="<?php echo Config::get('app.url').'admin/beneficiary/deleteSelected'; ?>">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box">
						<div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
				            <h5><?php //echo ucfirst($formTitle);?></h5>
          				</div>
			 		<div class="widget-content ">
         	<table class="table table-bordered table-striped table-hover with-check">
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" onclick="checkedAll('frmList');" /></th>
                  <th><?php echo trans('routes.name'); ?></th>
                  <th><?php echo trans('routes.husbandname'); ?></th>
				  <th><?php echo trans('routes.language'); ?></th>
				  <th><?php echo trans('routes.phonenumber'); ?></th>
				  <!--  th><?php echo trans('routes.homelabel'); ?>Action</th>-->
                </tr>
              </thead>
              <?php if(count($result) > 0) { ?>
              <tbody>
              	<?php foreach ($result as $value){?>
			        <tr>
                      <td><input type="checkbox" name="chkCheckedBox[]" id="chkCheckedBox" value="<?php echo $value->bi_id; ?>" /></td>
                      <td><?php echo $value->v_name;?></td>
                      <td><?php echo $value->v_husband_name;?></td>
						<td><?php echo $value->v_language;?></td>
						<td><?php echo $value->v_phone_number;?></td>
					  	<!--<td>
					  		  a class="btn btn-success" href="<?php echo Config::get('app.url').'admin/beneficiary/view/'.Hashids::encode($value->bi_id) ?>"><i class="icon-tag icon-white"></i>View</a>
                      		<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/edit/'.Hashids::encode($value->bi_id) ?>"><i class="icon-edit icon-white"></i>Edit</a>
							<a class="btn btn-danger" onclick="return singleCheckDel();" href="<?php echo Config::get('app.url').'admin/beneficiary/delete/'.Hashids::encode($value->bi_id); ?>"><i class="icon-remove icon-white"></i>Delete</a>
                      	</td>-->
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
            
            <div class="pagination" style="float:right;clear:both;"><?php //echo $result->render(); ?></div>
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
$(document).ready(function(){
   	$(function() {
			$( "#searchStartDate" ).datepicker({ minDate: 0 },{ altFormat: "dd/mm/yy" });
			$( "#searchEndDate" ).datepicker({ minDate: 0 },{ altFormat: "dd/mm/yy" });
		});
   });
var siteurl="<?php echo Config::get('app.url')?>";
	$(document).ready(function(){
		$("#frmSearchBeni").validate({
		ignore: ":hidden",
		rules: {
			searchStartDate: {
				required: true
			},
			searchEndDate: {
				required: true
			}
		},
		messages: 
		{
			searchStartDate: {
				required: ""
			},
			searchEndDate: {
				required: ""
			}
		}
		});
});
$(document).ready(function() {
    $('form:first *:input[type!=hidden]:first').focus();
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
            url : siteurl+'admin/adminusrs?page=' + page,
            dataType: 'html',
        }).done(function (data) {
            $('table.table').html(data);
            location.hash = page;
        }).fail(function () {
        	alert('Posts could not be loaded.');
        });
    }

    </script>

  <script language="javascript" type="text/javascript">
  $('#searchStartDate, #searchEndDate').bind('keypress', function(e){
		  if(e.keyCode==13){
	  		searchAddress();
		}
  });
 function searchAddress(){
	 var sdate=$("#searchStartDate").val();
	 var edate=$("#searchEndDate").val();
	 if(sdate!="" || edate!=""){
		$("#frmSearchBeni").submit();
	 }else{
		//alert(js_empty_date);	 
	}
 }	
</script> 

