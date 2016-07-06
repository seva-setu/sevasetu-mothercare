@include('template/admin_title')
@include('template/admin_cssscripta')
<style>
.hand {
	cursor: pointer;
}
</style>
</head><body>
@include('template/admin_header')
@include('template/admin_sidebar')
<div id="content">
  <div id="content-header">
    <h1>
      <?php  echo trans('routes.interventionpoint'); ?>
    </h1>
  </div>
  <div id="breadcrumb"> <a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php  echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i>
    <?php  echo trans('routes.home'); ?>
    </a><a class="current">
    <?php  echo trans('routes.interventionpoint'); ?>
    </a> </div>
  <div class="container-fluid"> <br>
    <?php echo Session::get('message'); ?>
    <?php  
			//$attributes = array('class' => 'form-horizontal', 'id' => 'frmList', 'name' => 'frmList');
			//echo form_open(SITEURLADM.$cntrlName.'/deleteSelected',$attributes);?>
    <?php //echo $this->session->flashdata('dispMessage');?>
    <form class="form-horizontal" accept-charset="utf-8" role="form" method="POST" id="frmList" name="frmList" action="<?php echo Config::get('app.url').'admin/updateInterventionPoint'; ?>">
      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="txtHdn" id="txtHdn" value="<?php echo count($result); ?>" />
      <div class="row-fluid">
        <div class="span12">
          <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
              <h5>
                <?php  echo trans('routes.interventionpoint'); ?>
              </h5>
            </div>
            <div class="widget-content">
              <div id="replace_pagecontant">
                <div id="no-more-tables">
                  <div id="service_table" class="service_table"> 
                    <!--table table-hover with-check table-bordered table-striped-->
                    
                    <table class="table table-hover with-check table-condensed cf">
                      <thead id="insertMore" class="cf mar-btn">
                        <tr>
                          <th><?php  echo trans('routes.interventionpoint'); ?></th>
                          <th><?php  echo trans('routes.week'); ?></th>
                          <th>{{ trans('routes.title') }}</th>
                          <th>{{ trans('routes.description') }}</th>
                          <th><img width="18" height="18" class="hand" onClick="addcomponents('clone');" title="Add More Intervention Point" alt="Add Product Intervention Point" src="<?php echo Config::get('app.url'); ?>external/images/add.png"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $arr=array('12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60','61','62','63','64'); ?>
                        <?php 
				$i=0;
				foreach($result as $k=>$v){ 
				$i++;
					?>
                        <tr id="<?php echo $i;?>">
                          <td style="text-align: center;" data-title="Intervention Point"><?php echo $i; ?></td>
                          <td style=" text-align: center;" data-title="Week"><input type="hidden" name="hdnInverationId[]" value="<?php echo $v->bi_id; ?>">
                            <select name="txtInterventionPoint[]" class="hideInterventionPoint" id="txtInterventionPoint<?php echo $i;?>">
                              <?php foreach($arr as $val){
              	if($val==$v->i_week)
              		$sel="selected";
              	else 
              		$sel="";
              ?>
                              <option value="<?php echo $val?>" <?php echo $sel; ?>><?php echo $val; ?> Week</option>
                              <?php }?>
                            </select></td>
                          <td style="text-align: center;" data-title="Title"><input type="text" class="txttile" name="txtTitle[]" value="<?php echo $v->v_name; ?>" ></td>
                         <!--width: 270px;--> <td style="text-align: center;" data-title="Description"><textarea class="txtdescription" name="txtDescritpion[]" cols="5" rows="5" ><?php echo $v->t_description; ?> </textarea></td>
                          <td style=" text-align: center;"><img src="<?php echo Config::get('app.url'); ?>external/images/min.png" alt="Delete Intervention Point" title="Delete Intervention Point" onClick="removecomponents(<?php echo $i;?>,'<?php echo $v->bi_id; ?>');" height="20" width="20" class="hand" /></td>
                        </tr>
                        <?php }?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-actions">
        <div class="splitFormSubmitButton">
          <input type="submit" id="submit" value="Submit" name="save" class="btn btn-primary">
        </div>
      </div>
    </form>
    <!--</form>-->
    <?php 
    //echo form_close();
    ?>
    @include('template/admin_footer') </div>
</div>
@include('template/admin_jsscript') 
<script>
function addcomponents(objBtn) {
	//i++;
	var str="";
	var noofrow=0;
	for(var j=12;j<65;j++){
		str+="<option value="+j+">"+j+" Week</option>";
	}	
	i=parseInt($("#txtHdn").val())+parseInt(1);
   var indx = $("#insertMore").find("tr").length;
	var row = '<tr id="'+i+'">';
	row += '<td style=" text-align: center; ">'+indx+'</td>';
	//getProductCode();											  
	row += '<td style=" text-align: center;"><select name="txtInterventionPoint[]" class="hideInterventionPoint" id="txtInterventionPoint'+i+'">'+str+'</select></td>';
	//getProductComponents();
	row += '<td style="text-align: center;" ><input type="text" class="txttile" name="txtTitle[]" value="" ></td>';
	row += '<td style="text-align: center;width: 270px;"><textarea class="txtdescription" name="txtDescritpion[]" cols="5" rows="5" ></textarea></td>';
              
	row += '<td style=" text-align: center;"><img src="<?php echo Config::get('app.url'); ?>external/images/min.png" title="Delete Intervention Point" width="20" height="20" onclick="removecomponents('+i+',0);" class="hand" /></td>';											  
	row += '</tr>';
	$('#insertMore').append(row);
	$("#txtHdn").val(i);
	buttonsetting(<?php echo count($result); ?>,$("#txtHdn").val()); 
	//getProductComponents();
}
var baseURL="<?php echo Config::get('app.url'); ?>";
function removecomponents(trId,id)
{
	var token=$("#_token").val();
	var temp=$("#txtHdn").val();
	var total=parseInt("<?php echo count($result); ?>");
	$("#txtHdn").val(--temp);
	$('#'+ trId).remove();
	$('.alert').remove();
	var url = baseURL+"admin/intervation_delete";
	if(parseInt(id)!=0){
		total=total-1;
		$.ajax ({
			cache: false,  		
			type:"post",
			url:url,
			data: {'_token':token,'id':parseInt(id)},
			success: function(response){
				$(".container-fluid").prepend(response);
			}
		});
	}
	buttonsetting(total,$("#txtHdn").val()); 
}
$(".hideInterventionPoint").on("change",function(){
    $(this).find("option").show();
    $("option:selected", $(this)).hide();
});
function buttonsetting(temp,total){
	console.log(temp+"-"+total);
	/*if(total==temp){
		$("#submit").attr("disabled",true);
	}else{
		$("#submit").attr("disabled",false);
	}*/
}
$(".txttile").bind('keypress', function(e){
	$("#submit").attr("disabled",false);
});
$(".txtdescription").bind('keypress', function(e){
	$("#submit").attr("disabled",false);
});
</script> 
