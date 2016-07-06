<?php 
$callchampid = DB::select('select bi_calls_champion_id from mct_beneficiary where mct_beneficiary.e_status!="Deleted" and bi_id='.$beneficiaryId.'');
if(count($callchampid)>0){
	$callchampid=$callchampid[0]->bi_calls_champion_id;
}else{
	$callchampid=0;
}
?>
<form class="form-horizontal" accept-charset="utf-8" role="form" method="POST" id="frmList" name="frmList" action="<?php echo Config::get('app.url').'admin/beneficiary/deleteSelected'; ?>">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box">
						<div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
				            <h5><?php //echo ucfirst($formTitle);?></h5>
          				</div>
			 <div class="widget-content">
			 <div id="replace_callchampcontant">
			 <div style="width: 80%;position: relative;" id="keywordtextbox">
			<input placeholder="<?php echo trans('routes.searchbynameorcity'); ?>" type="text" name="searchcallchampion" id="searchcallchampion" style="width:100% !important; margin-bottom:10px;" class="input-new search-input" title="Search by Name or City" onkeyup="clearText(this.id)" value="" >
			<div class="search-reset searchcallchampion" id="search-reset1" onClick="hidesearch(this.id);"></div>
			</div>
			 <table class="table table-bordered table-striped table-hover with-check">
             <input type="hidden" name="hdnBeneficiaryId" id="hdnBeneficiaryId" value="<?php echo $beneficiaryId; ?>">
			 <input type="hidden" name="hdnAddressId" id="hdnAddressId" value="<?php echo $addressId; ?>">
			 <input type="hidden" name="hdnname" id="hdnname" value="<?php echo $name; ?>">
			  <thead>
                <tr>
                  <th><?php echo trans('routes.select'); ?></th>
                  <th><?php echo trans('routes.callchampionsname'); ?></th>
                  <th><?php echo trans('routes.city'); ?></th>
               </tr>
              </thead>
              <?php if(count($result) > 0) { ?>
              <tbody>
              	<?php foreach ($result as $value){
              		$checked="";
              		if($callchampid==$value->bi_id){
  						$checked="checked";            			
              		}
              		?>
			        <tr class="selectCall">
                      <td><input type="radio" <?php echo $checked; ?> name="redCheckedBox" value="<?php echo $value->bi_id; ?>" /></td>
                      <td><?php echo $value->v_name; ?></td>
                      <td><?php echo $value->v_district;?></a></td>
                      <input type="hidden" name="hdnCallChamptionName-<?php echo $value->bi_id; ?>" id="hdnCallChamptionName-<?php echo $value->bi_id; ?>" value="<?php echo $value->v_name;?>">
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
            </div>
          </div>
        </div>
      </div>
    </div>
    </form>
    <script>
    function changeCallChamption(){
    	var token=$("#_token").val();
    	var beneficiaryId=$("#hdnBeneficiaryId").val();
    	var callchampionId=$("input[name=redCheckedBox]:checked").val();
    	var username=$("#hdnCallChamptionName-"+callchampionId).val();
		if(callchampionId!="" && callchampionId!=0 && callchampionId!=undefined){ 
    	var siteurl="<?php echo Config::get('app.url')?>";
	    	$.ajax({
	            type: "POST",
	            url: siteurl+"admin/beneficiary/selCallChamption",
	            data: {'_token':token,'callchampionId':callchampionId,'beneficiaryId':beneficiaryId},
	            success: function(data){
	            	$("#CallChamption-"+beneficiaryId).attr('class','');
	                $("#CallChamption-"+beneficiaryId).text(username);
	                $("#priceChangeModel").modal('hide');
	    			
	            },
	            error: function(){
	                  //alert('error handing here');
	            }
	        });	
		}else{
			alert("Please Select Call Champion");
		}
    }
    </script>
     <script language="javascript" type="text/javascript">

					$('#searchcallchampion').bind('keypress', function(e) 

					{	
						$("#search-reset").show();
						if(e.keyCode==13)

						{

						  if(!$('#hdnData').val())	

						  {		

							  var value = $('#searchcallchampion').val();

							  if(value !=""){		  
								  var id=""; 	
								 shearchSpecCallchampion(id,encodeURIComponent(value)); 	
							  	//window.location="<?php //echo Config::get('app.url'); ?>admin/beneficiary/searchCallchampion?search="+encodeURIComponent(value);

							  }

						  }	  

						}

					});

					

					$("#searchcallchampion").coolautosuggest({

						

						url:"<?php echo Config::get('app.url'); ?>admin/beneficiary/autoCallchampion?chars=",

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

							  shearchSpecCallchampion(result.id,result.data);		  				  

						  		//window.location="<?php //echo Config::get('app.url'); ?>admin/callchampions/searchdatacallchampion/"+result.id+'/'+result.data;

						  }						 

						},		

					});
function shearchSpecCallchampion(id,data){
	var beneficiaryId=$("#hdnBeneficiaryId").val();
	var addressId=$("#hdnAddressId").val();
	var callchampionId=$("input[name=redCheckedBox]:checked").val();
	var name=$("#hdnname").val();
	var token=$("#_token").val();
	var siteurl="<?php echo Config::get('app.url')?>";
	$.ajax({
        type: "POST",
        url: siteurl+"admin/beneficiary/searchCallchampion",
        data: {'_token':token,'id':id,'data':data,'beneficiaryId':beneficiaryId,'addressId':addressId,'name':name},
        success: function(data){
            $("#replace_callchampcontant").html(data);
			//$("#priceChangeModel").modal('show');
			
        },
        error: function(){
              //alert('error handing here');
        }
    });
}
$(".selectCall").click(function(){
	$(this).find('input[name=redCheckedBox]').attr("checked",true);
});
				</script>  