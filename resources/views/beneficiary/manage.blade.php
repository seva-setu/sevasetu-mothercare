<?php 
$userinfo=Session::get('user_logged');
$usertype=$userinfo['v_role'];
$userid=$userinfo['b_id'];
$errormsg=count(Session::get('errormessage'))>0 ? Session::get('errormessage'):array();
$state=isset($state) && $state!="all"?$state:"";
$city=isset($city) && $city!="all"?$city:"";
$taluka=isset($taluka) && $taluka!="all" ?$taluka:"";
$tag=isset($searchTag)?$searchTag:"";
$citydis="";
$statedis="";
$talukadis="";
if($tag!="")
	$display="display:block;";
else 
	$display="display:none;";
if($city!="")
	$citydis="display:block;";
if($state!="")
	$statedis="display:block;";
if($taluka!="")
	$talukadis="display:block;";
$fieldworker= DB::table('mct_field_workers')->where('e_status',"!=",'Deleted')->orderBy('bi_id', 'DESC')->get();
$callchampion= DB::table('mct_call_champions')->where('e_status',"!=",'Deleted')->orderBy('bi_id', 'DESC')->get();

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
	<div id="breadcrumb">
    	<a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="Go to Home" class="tip-bottom"><i class="icon-home"></i><?php  echo trans('routes.home'); ?></a><a class="current"><?php  echo trans('routes.beneficiary'); ?></a> 
   	</div>
	<div class="container-fluid"> 
	<div class="row-fluid">
      	<div class="span12" style="margin-left:0;">
      	
      	<div class="form-inline">
  <h4>{{ trans('routes.filterbyaddress') }}</h4>
      	<span id="keywordtextbox">
    	<select id="state" name="state" onChange="findCity(this.value);">
    	<option value=""><?php  echo trans('routes.statelabel'); ?></option>
    	<?php 
    	$states=array('Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jammu and Kashmir','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha(Orissa)','Punjab','Rajasthan','Sikkim','Tamil Nadu','Tripura','Uttar Pradesh','Uttarakhand','West Bengal');
    	?>
    	<?php foreach ($states as $k=>$v){?>
			<option value="<?php echo strtolower($v); ?>" <?php echo strtolower($state)==strtolower($v)? "selected" : ""; ?>><?php echo ucwords($v); ?></option>
        <?php }?>
        </select>
        </span>
        
        
        
        <span id="keywordtextbox">
    	<?php if(!empty($cityarr)){?>
    	<select id="city" name="city" onChange="findTaluka(this.value);">
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
    	<select id="city" name="city" onChange="findTaluka(this.value);">
    	<option value=""><?php  echo trans('routes.disrictlabel'); ?></option>
    	<?php if($city!=""){?>
    	<option value="<?php echo $city; ?>" selected><?php echo $city; ?></option>
    	<?php }?>
    	</select>
    	<?php }?>
	    </span>
	    
	    <span id="keywordtextbox">
		<?php if(!empty($talukaarr)){?>
		<select id="taluka" name="taluka">
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
    	<select id="taluka" name="taluka">
	    <option value=""><?php  echo trans('routes.talukalabel'); ?></option>
    	<?php if($taluka!=""){?>
    	<option value="<?php echo $taluka; ?>" selected><?php echo $taluka; ?></option>
    	<?php }?>
    	</select>
    	<?php }?>
    	</span>
	    
	    <span id="keywordtextbox">
			<button type="button" name="searchTaluka" id="searchTaluka" class="btn btn-primary" onClick="searchAddress();" ><?php  echo trans('routes.searchbtn'); ?></button>
	    </span>
         <?php if($state!="" || $city!="" || $taluka!=""){?>
        <span id="keywordtextbox">
			<a class="btn btn-primary" href="<?php echo Config::get('app.url').'admin/beneficiary';?>"><?php  echo trans('routes.reset'); ?></a>
	    </span>
        <?php }?>
</div>
      	
    	<div class="search_for">
    	<h4><?php  echo trans('routes.search'); ?></h4>
		<span id="keywordtextbox"  style="position: relative;" >
			<input placeholder="<?php  echo trans('routes.searchlabel'); ?>" type="text" name="searchadministrator" id="searchadministrator" style="width:99%;" class="input-new search-input" title="<?php  echo trans('routes.searchlabel'); ?>" value="<?php echo $tag; ?>" onKeyUp="clearText(this.id)" >
			<?php if($tag!=""){?>
			<a class="search-reset searchadministrator" id="search-reset" style="<?php echo $display; ?>" href="<?php echo Config::get('app.url').'admin/beneficiary'?>"></a>
			<?php }else{?>
			<div class="search-reset searchadministrator" id="search-reset" onClick="hidesearch(this.id);"></div>
			<?php }?>
	    </span>
    	</div>
    	</div>
        <?php 
       	$style="display:none";
       	if($usertype==3)
       	   	$style="display:block";
       	?>
       	<?php if(isset($filterfwid)) { $style="display:block"; } ?>
        <div class="field_worker">
       	<?php $active = Request::segment(3); ?>
        <?php if($usertype!=3){?>
        	<div class="span3" style="max-width:231px;" >
   		<h4>{{ trans('routes.filterbyfw') }}</h4>
    		<select name="selFieldworker" id="selFieldworker" onChange="selectFiedworker(this.value)">
    		<option value="">{{ trans('routes.selectfw') }}</option>
    	@if(!empty($fieldworker))	
    		@foreach($fieldworker as $key=>$val)
    		<option value="<?php echo Hashids::encode($val->bi_id); ?>" <?php if(isset($filterfwid) && $filterfwid == $val->bi_id) { echo "selected";  } ?>><?php echo $val->v_name; ?></option>
    		@endforeach
    	@endif	
    		</select>
    		</div>
  		<?php  }?>
  		
  		<div class="span3" style="max-width:231px;margin-left:0px;">
  			<h4>{{ trans('routes.filterbycall') }}</h4>
  		  		<select name="selCallChampion" id="selCallChampion" onChange="selectCallChampion(this.value)">
    		<option value="">{{ trans('routes.selectcallc') }}</option>
    	@if(!empty($callchampion))	
    		@foreach ($callchampion as $key=>$val)
    		<option value="<?php echo Hashids::encode($val->bi_id); ?>" <?php if(isset($filterccid) && $filterccid == $val->bi_id) { echo "selected";  } ?>><?php echo $val->v_name; ?></option>
    		@endforeach
    	@endif	
    		</select>
    	</div>
    
  
    <div class="span7" style="margin-left:0px;">
  	  <h4>{{ trans('routes.filterbyassigned') }}</h4>
  	  	<select name="selAssigned" id="selAssigned" onChange="selectAssigned(this.value)">
    		<option value="all">{{ trans('routes.selectall') }}</option>
    		<option value="unassigned" @if(isset($filterassgned) && $filterassgned=="unassigned") {{ "selected"  }} @endif>{{ trans('routes.unassigned') }}</option>
    		<option value="assigned" @if(isset($filterassgned) && $filterassgned=="assigned") {{ "selected"  }} @endif>{{ trans('routes.assigned') }}</option>
    	</select>
 	</div>
    	
    		
   	@if($usertype==2||$usertype==3)	
  	 	<div class="search_for">
       		<input class="check_assing" type="checkbox" name="chkAssignedBeneficiary" id="chkAssignedBeneficiary"  @if($active=="") checked   @endif>Show Assigned Beneficiary
        </div>
    @else
    	
       
    @endif  
          
        
        <div>
			<a href="{{ URL::previous() }}" class="btn btn-primary"><i class="icon-arrow-left icon-white"></i> {{ trans('routes.back') }}</a>
    	</div>
    
    	
        <div id="importfile" style="<?php echo $style; ?>" >
    	<form class="form-horizontal beneficiary_btn" accept-charset="utf-8" method="POST" id="frmUploadExcel" name="frmUploadExcel" enctype='multipart/form-data' action="<?php echo Config::get('app.url').'admin/beneficiary/importExcel'; ?>">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" id="fieldwrokerid" name="fieldwrokerid" value="{{ isset($filterfwid) ? Hashids::encode($filterfwid) : ''  }}" />
			<div class="fileUpload btn btn-primary">
    			<span><?php  echo trans('routes.impbeneficiary'); ?></span>
    			<input type="file" id="txtExcel" name="txtExcel" class="upload" />
    		</div>
			</form>
		</div>
		
		
    	
		<?php if($usertype==3){?>
		
		<div>
  			<a href="<?php echo Config::get('app.url'); ?>admin/beneficiary/edit" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i><?php  echo trans('routes.addbeneficiary'); ?></a>
    	</div>
    	
    	
    	<?php }else{?>
    	<div id="addbenificiary" style="<?php echo $style; ?>">
  			<a href="javascript:void()" onClick="addbenificiaryfeid();" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i> {{ trans('routes.addbeneficiary') }}</a>
    	</div>
    	<?php }?>
    	
    	<div>
        <a href="<?php echo Config::get('app.url'); ?>admin/beneficiary/downlaodsample" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i> {{ trans('routes.downloadlabel') }}</a>
    	</div>
    	
    	<div>
    		<a href="javascript:void(0);" onClick="return checkDelete();" class="btn btn-danger"><i class="icon-remove icon-white"></i> {{ trans('routes.deletebeneficiary') }}</a>
  		</div>
  		</div>
  		</div>
        </div>
        
        <?php if(!empty($errormsg)){?>
        	<div class="span10" style="margin-left:0;">
        	<div class="alert alert-error" style="clear:both;height: 100px;overflow-y: scroll;">
              <button data-dismiss="alert" class="close" type="button">×</button>
        	<?php foreach ($errormsg as $key=>$val){
	        	echo "Row number ".$key."<br/>";
	        	if(isset($val['name']))
	        		echo $val['name'][0]."<br/>";
	        	if(isset($val['language']))
	        		echo $val['language'][0]."<br/>";
	        	if(isset($val['number_pregnancies']))
	        		echo $val['number_pregnancies'][0]."<br/>";
	        	if(isset($val['husband_name']))
	        		echo $val['husband_name'][0]."<br/>";
	        	if(isset($val['phone_number']))
	        		echo $val['phone_number'][0]."<br/>";
	        	if(isset($val['alternate_phone_no']))
	        		echo $val['alternate_phone_no'][0]."<br/>";
	        	if(isset($val['village']))
	        		echo $val['village'][0]."<br/>";
	        	if(isset($val['pincode']))
	        		echo $val['pincode'][0]."<br/>";
	        	if(isset($val['taluka']))
	        		echo $val['taluka'][0]."<br/>";
	        	if(isset($val['awc_name']))
	        		echo $val['awc_name'][0]."<br/>";
	        	if(isset($val['awc_number']))
	        		echo $val['awc_number'][0]."<br/>";
	        	if(isset($val['due_date']))
	        		echo $val['due_date'][0]."<br/>";
	        	if(isset($val['delivery_date']))
	        		echo $val['delivery_date'][0]."<br/>";
	        	if(isset($val['zipcode']))
	        		echo $val['zipcode']."<br/>";	
	        	echo "<br/>";
        	}?>
	        </div>
	        </div>
        <?php }?>
        <?php echo Session::get('message'); ?>
        <?php  
			//$attributes = array('class' => 'form-horizontal', 'id' => 'frmList', 'name' => 'frmList');
			//echo form_open(SITEURLADM.$cntrlName.'/deleteSelected',$attributes);?>
		   	<?php //echo $this->session->flashdata('dispMessage');?>
		   	<form class="form-horizontal" accept-charset="utf-8" role="form" method="POST" id="frmList" name="frmList" action="<?php echo Config::get('app.url').'admin/beneficiary/deleteSelected'; ?>">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box">
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
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" onClick="checkedAll('frmList');" /></th>
                  <th>{{{ trans('routes.uniqueid') }}}</th>
                  <th>{{{ trans('routes.name') }}}</th>
                  <th>{{{ trans('routes.husbandname') }}}</th>
				  <th>{{{ trans('routes.location') }}}</th>
				  <th>{{{ trans('routes.phonenumber') }}}</th>
				  <th>{{{ trans('routes.callchampion') }}}</th>
                </tr>
              </thead>
              <?php if(count($result) > 0) { ?>
              <tbody>
              	<?php foreach ($result as $value){?>
			        <tr>
                      <td><input type="checkbox" name="chkCheckedBox[]" id="chkCheckedBox" value="<?php echo $value->bi_id; ?>" /></td>
                      <td data-title="{{{ trans('routes.uniqueid') }}}"><a href="<?php echo Config::get('app.url').'admin/beneficiary/view/'.Hashids::encode($value->bi_id) ?>"><?php echo $value->v_unique_code;?></a></td>
                      <td data-title="{{{ trans('routes.name') }}}"><a href="<?php echo Config::get('app.url').'admin/beneficiary/view/'.Hashids::encode($value->bi_id) ?>"><?php echo $value->v_name;?></a></td>
                      <td data-title="{{{ trans('routes.husbandname') }}}">{{{ $value->v_husband_name or '' }}}</td>
					  <td data-title="{{{ trans('routes.location') }}}">@if($value->v_village!="") {{{ $value->v_village.", ".$value->v_taluka.", ".$value->v_district }}} @endif</td>
					  <td data-title="{{{ trans('routes.phonenumber') }}}">{{{ $value->v_phone_number }}}</td>
					  <td data-title="{{{ trans('routes.callchampion') }}}">
					  <?php if($usertype!=2 && $usertype!=3){?>
					  <?php if($value->bi_calls_champion_id==""){ 
					  	?>
					  	<a class="btn btn-info" href="javascript:void(0);" id="CallChamption-<?php echo $value->bi_id; ?>" onClick="showCallChamption('<?php echo $value->i_address_id; ?>','<?php echo $value->bi_id; ?>','<?php echo $value->v_name; ?>')" >{{ trans('routes.assigncall') }}</a>
					  <?php }else{
					  	$callchampid = DB::select('select v_name from mct_call_champions where e_status!="Deleted" and bi_id='.$value->bi_calls_champion_id.'');
					  	if(count($callchampid)>0){
					  	$callchamname=$callchampid[0]->v_name;
					  	?>
					  	<a href="javascript:void(0);" id="CallChamption-<?php echo $value->bi_id; ?>" onClick="showCallChamption('<?php echo $value->i_address_id; ?>','<?php echo $value->bi_id; ?>','<?php echo $value->v_name; ?>')" ><?php echo $callchamname; ?></a>
					  <?php }else{?>
					  	<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/edit/'.Hashids::encode($value->bi_id) ?>"><i class="icon-edit icon-white"></i><?php echo trans('routes.edit'); ?></a>
					  <?php }}
        				}else {?>
        					<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/edit/'.Hashids::encode($value->bi_id) ?>"><i class="icon-edit icon-white"></i><?php echo trans('routes.edit'); ?></a>
							<!-- a class="btn btn-danger" onclick="return singleCheckDel();" href="<?php //echo Config::get('app.url').'admin/callchampions/delete/'.Hashids::encode($value->bi_id)."/".Hashids::encode($value->bi_user_login_id); ?>"><i class="icon-remove icon-white"></i><?php echo trans('routes.delete'); ?></a> -->
							<?php 
							if($value->e_status=="Active"){?>
                      			<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/delete/'.Hashids::encode($value->bi_id)."/".Hashids::encode(0); ?>"></i><?php echo trans('routes.inactive'); ?></a>					  	
                      		<?php }elseif($value->e_status=="Inactive"){?>
                      			<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/delete/'.Hashids::encode($value->bi_id)."/".Hashids::encode(1); ?>"></i><?php echo trans('routes.active'); ?></a>					  	
                      		<?php }?>
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
          </div></div>
          <!-- end of nomoretables -->
            </div>
          </div>
          
        </div>
      </div>
      
       <div class="widget-box">
       <div class="pagination" style="float:right;clear:both;"><?php echo $result->render(); ?></div>
       </div>
       
    </div>
    </form>
    <!--</form>-->
	<?php 
    //echo form_close();
    ?>
    @include('template/admin_footer')
  </div>
  <div class="modal fade hide mobile_height" id="priceChangeModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" id="callchampion-lists">
     </div>
	 </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('routes.close') }}</button>
        <button type="button" class="btn btn-primary" onClick="changeCallChamption();" >{{ trans('routes.save') }}</button>
      </div>
    </div>
  </div>

@include('template/admin_jsscript')
<script>
function showCallChamption(addressid,beneficiaryId,name){
	var token=$("#_token").val();
	var siteurl="<?php echo Config::get('app.url')?>";
	$.ajax({
        type: "POST",
        url: siteurl+"admin/beneficiary/getCallChamption",
        data: {'_token':token,'addressid':addressid,'beneficiaryId':beneficiaryId,'name':name},
        beforeSend:function(){
			$('#loaderdiv').fadeIn();
		},
        success: function(data){
        	$('#loaderdiv').fadeOut();
            $("#myModalLabel").text(" <?php echo trans('routes.assigncallchampionto'); ?> "+name);
            $("#callchampion-lists").html(data);
			$("#priceChangeModel").modal('show');
			
        },
        error: function(){
              //alert('error handing here');
        }
    });
}

var token=$("#_token").val();
var siteurl="<?php echo Config::get('app.url')?>";

   function addbenificiaryfeid(){
	   var id=$("#fieldwrokerid").val();
	   window.location="<?php echo Config::get('app.url'); ?>admin/beneficiary/edit?userid="+id;
   } 
  function selectFiedworker(id){

    if(id!=""){
        	$("#fieldwrokerid").val(id);	
			$("#importfile").show();
			$("#addbenificiary").show();
	    }else{
			$("#fieldwrokerid").val(id);
			$("#importfile").hide();
			$("#addbenificiary").hide();
	    }
    	
	/* Filter by Field worker */
	if(id!=""){
    	window.location= siteurl + "admin/beneficiary/filterbyfieldworker/"+id;
	}else{
		window.location= siteurl + "admin/beneficiary";
	}
  }  

  
 function selectCallChampion(id){
		/* Filter by Field worker */
		if(id!=""){
	    	window.location= siteurl + "admin/beneficiary/filterbycallchampion/"+id;
		}else{
			window.location= siteurl + "admin/beneficiary";
		}
 }  

 function selectCallChampion(id){
		/* Filter by Field worker */
		if(id!=""){
	    	window.location= siteurl + "admin/beneficiary/filterbycallchampion/"+id;
		}else{
			window.location= siteurl + "admin/beneficiary";
		}
  }

 function selectAssigned(paramval){
		/* Filter by Field worker */
		if(paramval=="assigned"){
	    	window.location= siteurl + "admin/beneficiary/assigned";
		}else if(paramval=="unassigned"){
			window.location= siteurl + "admin/beneficiary/unassigned";
		}else{
			window.location= siteurl + "admin/beneficiary";
		}	
}
 

 	$('#chkAssignedBeneficiary').change(function(){
 		if(this.checked) {
 			 window.location= siteurl + "admin/beneficiary/";
 	    }else{
 	    	  window.location= siteurl + "admin/beneficiary/all/";
 	    }  
 			
 	})
  
   
 /*    $(window).on('hashchange', function() {
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
 */
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

							  window.location="<?php echo Config::get('app.url'); ?>admin/beneficiary/searchdatabeneficiary?search="+encodeURIComponent(value);

							  }

						  }	  

						}

					});

					

					$("#searchadministrator").coolautosuggest({

						

						url:"<?php echo Config::get('app.url'); ?>admin/beneficiary/autocompletebeneficiary?chars=",

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

						  				  				  

						  		window.location="<?php echo Config::get('app.url'); ?>admin/beneficiary/searchdatabeneficiary/"+result.id+'/'+result.data;

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
						if(state!="all" || dist!="all" || taluka!="all"){
							window.location="<?php echo Config::get('app.url'); ?>admin/beneficiary/searchdataaddress/"+state+'/'+dist+'/'+taluka;
						}else{
							alert(js_validadrress);
						}
					}
					$(document).on('change',':file',function () {
						if(this.files.length > 0)
						{
							var name = this.files[0].name;
							var emailReg = new RegExp(/(xls|xlsx|csv)$/g);
							var valid = emailReg.test(name);
							
							if(!valid)
							{
								alert(js_excelvalid);
								$(this).val("");
								return false;
							}else{
								$("#frmUploadExcel").submit();
								return true;
							}
						 }
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
function strtolower(str) {
	 	  return (str + '')
	    .toLowerCase();
	}
</script> 

