@include('template/admin_title')>
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/callchampion_sidebar')
<div id="content">
  <div id="content-header">
    <h1><?php  echo trans('routes.beneficiary'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="{{ url() }}/admin/dashboard" title="<?php  echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php  echo trans('routes.home'); ?></a><a href="{{ url() }}/admin/checklist">{{ trans('routes.checklists') }}</a><a class="current">{{ trans('routes.edit') }}</a></div>
 
 
  <div class="container-fluid"> 
    {{-- Flash Messages --}}
@if(Session::has('success'))
 <div class="row-fluid">
 	
 	<div class="alert alert-success" style="clear:both;">
         <button data-dismiss="alert" class="close" type="button">&times;</button>{{ Session::get('success') }}</div>
    </div>    
 @endif   
    @if(Session::has('danger'))
 <div class="row-fluid">
 	
 	<div class="alert alert-danger" style="clear:both;">
         <button data-dismiss="alert" class="close" type="button">&times;</button>{{ Session::get('danger') }}</div>
    </div>    
 @endif  
 
    <div class="row-fluid">
		<div class="span12">
       		<div class="widget-box">
        	  <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
         	   <h5>{{ trans('routes.editchecklist') }}</h5>
         	 </div>
         	 
         	<form class="form-horizontal" role="form" method="POST" id="frmChecklist" name="frmChecklist" action="{{ url() }}/admin/checklist/{{ $action or '' }}">
				<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
   <input type="hidden"  name="hndChklistId" value="{{ $checklistmaster[0]->bi_id }}">
		 	 	
		 	 	 <div class="row-fluid">
          		  <div class="widget-content nopadding">
              		<div class="row-fluid">
                  <div class="span12 clearfix">
                    
                    <div class="control-group">
                      <label class="control-label">{{ trans('routes.category') }}<font color="#FF0000"> *</font></label>
                      <div class="controls">
                     	<select class="form-control required" id="sltCategory" name="sltCategory">
                     		<option value="">-- Select Category --</option>
                     		@foreach($categories as $category)
                     			<option value="{{ $category->bi_id or '' }}" @if($checklistmaster[0]->bi_type_id == $category->bi_id) {{ "selected='selected'" }}  @endif >{{ $category->v_type or '' }}</option>
                     		@endforeach
                     	</select> 	
                     	   @if($errors->first('Category')) <p class="errmsg">{{ $errors->first('Category') }}</p> @endif
                     </div>
                     
                    </div>
                    {{--
                    <div class="control-group">
                     <div class="controls">
                     	<button class="btn btn-success" id="addcategory" type="button"><i class="icon-plus-sign icon-white"></i> {{ trans('routes.addcategory') }}</button>	  
                     </div>
                  
                    </div>
                    --}}
                    <div class="control-group">
                      <label class="control-label">{{ trans('routes.description') }}<font color="#FF0000"> *</font></label>
                      <div class="controls">
                     	<input type="text" class="form-control required" maxlength="100" name="description" value="{{ $checklistmaster[0]->v_description or '' }}" id="description">                    	  
                       @if($errors->first('Description')) <p class="errmsg">{{ $errors->first('Description') }}</p> @endif
                     </div>
                    </div>
                    
                    

                    
                    
                    <div class="control-group">
                      <label class="control-label">{{ trans('routes.recommendtime') }}</label>
                      <div class="controls">
                      	  <input type="text" maxlength="100" name="recommended_time"  id="recommended_time" value="@if($checklistmaster[0]->v_recommended_time !="custom") {{ $checklistmaster[0]->v_recommended_time or '' }} @endif">
                    	@if($errors->first('Recommended time'))  <p class="errmsg">{{ $errors->first('Recommended time') }}</p> @endif
                       </div>
                       
                    </div>
                    
                    
                    <div class="control-group">
                      <label class="control-label"><?php  echo trans('routes.responsestatus'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls" id="dynamicOption">
                      	<?php $options = explode('*',$checklistmaster[0]->v_response_options);  ?>
                      
                          	<input type="text" maxlength='50' placeholder='Option 1' id="optionText1" value="{{ $options[0] or '' }}"  class="form-control optionText required">
                      	
                      	<?php $counter = 2; for($i=1;$i < count($options);$i++){
                      		
                      	    echo "<input type='text' style='margin-top:10px' maxlength='50' placeholder='Option " . $counter . "' id='optionText".$counter."' value='".  $options[$i]  . "' class='form-control optionText'> <span class='input-group-btn'><button class='btn btn-default removeOption' type='button' id='".$counter."' style='margin-top:10px'><i class='fa fa-minus'></i></button></span>"; 
                      		$counter++;
                      	}
	               	      ?>
                      </div>
                       <div class="controls">
                     		@if($errors->first('Response')) <p class="errmsg">{{ $errors->first('Response') }}</p> @endif
                       </div>
                       <input type="hidden" name="optionsMerge" id="optionsMerge">
                    </div>
                    
                      <div class="control-group">
                     <div class="controls">
                     	<button class="btn btn-success" type="button" id="addOptions"><i class="icon-plus-sign icon-white"></i> {{ trans('routes.addoptions') }}</button>	  
                     </div>
                    
                    </div>
                    
                    
                       <div class="control-group">
                      <label class="control-label">{{ trans('routes.type') }}<font color="#FF0000"> *</font></label>
                      <div class="controls">
                     	<select class="form-control required" id="sltForType" name="sltForType">
                     		<option value="">-- Select Type --</option>
                     		<option value="0"  @if($checklistmaster[0]->ti_for=="0") {{ "selected='selected'" }}  @endif >Mother</option>
                     		<option value="1"  @if($checklistmaster[0]->ti_for=="1") {{ "selected='selected'" }}  @endif >Baby</option>
                     	</select> 	
                     	   @if($errors->first('Type')) <p class="errmsg">{{ $errors->first('Type') }}</p> @endif
                     </div>
                     
                    </div>
                    
                    
                   </div>
               </div>
               </div>
               </div>
               
               
               <div class="form-actions">
                  <div class="splitFormSubmitButton">
                    <input type="submit" value="Submit" name="save" class="btn btn-primary">
                                        <a class="btn btn-danger" href="{{ url() }}/admin/dashboard">Cancel</a>
                  </div>
                </div>
        	</form>
         	 
         	</div>
         </div> 
    </div>
    @include('template/admin_footer')
  </div>
</div>


 <!-- Modal -->
 <div class="modal fade hide" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ trans('routes.category') }}</h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal beneficiary_btn" accept-charset="utf-8" method="POST" id="frmAddCategory" name="frmActionItem" action="">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

	   
	 <div class="control-group">
			<label for="txtCategoryName" class="control-label">{{ trans('routes.category') }} : </label>
			<div class="controls">
			<input type="text" id="txtCategoryName" class="form-control">
			<p id="errcategory_name" style="display:none;color:#FF0000;"></p>
			</div>
	 </div>
	 
	 </div>
	 </div>
     
     <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('routes.close') }}</button>
        <button type="button" class="btn btn-primary" id="btnSaveChanges">{{ trans('routes.savechanges') }}</button>
     </div>
     
     </form>
    </div>
  </div>
<!-- End modal -->

    
@include('template/admin_jsscript')
<script src="{{ url() }}/external/js_admin/jquery.validate.js"></script>
<script>
	$(document).ready(function(){
		//Validate Checklist
		$("#frmChecklist").validate();
		
		siteurl = "<?php echo url() ?>";
		var token=$("#_token").val();
		
		$("#frmAddCategory").valid();
		
		$('#addcategory').click(function(){	
			$('#categoryModal').modal('show');
		})	

		$('#btnSaveChanges').click(function(){
			$("#txtCategoryName").rules("add", "required");
			var isvalid = false;

			if($("#frmAddCategory").valid()){
				
				$.ajax({
					url: siteurl + "/admin/checklist/addcategory",
					type:"POST",
					data: {'_token':token,'category_name':$("#txtCategoryName").val()},
					beforeSend:function(){
						$('#loaderdiv').fadeIn();
					},
					success:function(response){
						$('#loaderdiv').fadeOut();
						var errors = response.responseJSON;
						
				       	if(response.fail){
					    $.each(response.errors, function( index, value ) {
						    $('#errcategory_name').show();	
				          	$('#errcategory_name').text(value);
	        			
					    });
				       	}else{
				    		$('#sltCategory > option').each(function() {
								$(this).removeAttr('selected');
							})
				    		
				       	 	$('#sltCategory').append('<option value="' + response.insertid + '" selected="selected">'+ $("#txtCategoryName").val()+'</option>');
				       		$('#errcategory_name').hide();	
				       		$('#txtCategoryName').val('');	
							$('#categoryModal').modal('hide');
				       	}
					},  
					error: function(){
		            }				
				});
			}
		});

		var counter = "<?php echo $counter-1; ?>";
		$('#addOptions').click(function(){
			
			if(counter < 7){
			counter++;
			$("#dynamicOption").append("<input type='text' style='margin-top:10px' maxlength='50' placeholder='Option "+counter+"' id='optionText"+counter+"' class='form-control optionText'> <span class='input-group-btn'><button class='btn btn-default removeOption' type='button' id='"+counter+"' style='margin-top:10px'><i class='fa fa-minus'></i></button></span>");
			}
		
		});

		var removecounter = 2;	
		$('.removeOption').livequery(function(){ 
		
        	$(this).click(function() { 
        	
            	var id = $(this).attr('id');
            		$('#optionText'+id).remove();
            		$(this).remove();
            		removecounter--;
            		counter--;
        	})
  	 	})
		
		
		// Submit Form but merge options first
		$("#frmChecklist").submit(function( event ) {
			var options_list = [];
			$('.optionText').each(function(i, obj) {
				if($(this).val()!=""){
					options_list.push($(this).val());
				}
			});

			if(options_list.length>0){
				$('#optionsMerge').val(options_list);
			}
			
			//validation	
		})
		
	})
</script>
<script src="{{ url() }}/external/js/jquery.livequery.min.js"></script>
</body>
</html>