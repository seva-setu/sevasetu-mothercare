
    <!DOCTYPE html>
<html lang="en">
<head>
<title>{{ $title or '' }}</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
<div id="content">
  <div id="content-header">
    <h1><?php  echo trans('routes.beneficiary'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="{{ url() }}/admin/dashboard" title="<?php  echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php  echo trans('routes.home'); ?></a><a class="current">{{ trans('routes.checklists') }}</a></div>
 
 
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
         	   <h5>{{ trans('routes.checklists') }}</h5>
         	 </div>
         	 
         	<form class="form-horizontal" role="form" method="POST" id="frmChecklist" name="frmChecklist" action="{{ url() }}/admin/beneficiary/{{ $action or '' }}">
				<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
				<input type="hidden" id="hndBenId" name="hndBenId" value="{{ $beneficiaryid or '' }}" >
		 	 	
		 	 	 <div class="row-fluid">
          		  <div class="widget-content nopadding">
              			<div class="row-fluid">
                     <!-- Check List -->
           <div class="tab-pane" id="faq-cat-3">            
           		<div id="faq-cat-2" class="tab-pane active">            
                        		
          <table class="table table-bordered table-striped">
          <tbody>
                
         
          <!-- Loop Check list -->
         
           <tr>
              <td style="font-weight: 600;" colspan="6" data-toggle="collapse" data-target="#motheraccordian" class="clickable arrow-toggle" align="center">{{ trans('routes.mother') }}<i style="float:right;" class="fa fa-chevron-circle-up fa-lg"></i></td>
            </tr>
            
                        
           <tr>
              <td>
              <div id="motheraccordian" class="collapse in">
              <table class="table table-bordered table-striped">
            <tbody>
            
       
            
        	@foreach($categoriesMother as $catM)
        	 
        	
            <tr>
             
              <th>
              	@if(isset($catM->v_type))
   					 @if($catM->v_type=="Vaccinations")
   						{{ trans('routes.vaccinations') }}
   					 @elseif($catM->v_type=="Check-ups")
   						{{ trans('routes.checkups') }}
   					 @elseif($catM->v_type=="Supplements")
   						{{ trans('routes.supplements') }}
   					 @elseif($catM->v_type=="Delivery Information")
   						{{ trans('routes.deliveryinfo') }}	
   					 @else
   					 	{{ $catM->v_type or '' }}
					 @endif
				@endif
              </th>
              <th>{{ trans('routes.recommendtime') }}</th>
              <th>{{ trans('routes.responsestatus') }}</th>
              <th>{{ trans('routes.anycomments') }}</th>	
            </tr>
            
            @foreach($descr as $desc)
            	@if($desc->bi_type_id == $catM->bi_id)
             <tr>
              <td>{{ $desc->v_description }}</td>
              <td>@if($desc->v_recommended_time!="custom") {{ $desc->v_recommended_time or '' }} @endif</td>
              <td>
              <?php $options = explode('*', $desc->v_response_options );  ?>
              
              
       
              		
              	<select class="form-control sltResponse" name="sltResponse">
		        	<option value=""> -- Not Selected -- </option>
		              	
              		@for($i=0;$i<count($options);$i++)
              		<option value="{{ $options[$i] }}" @foreach($userChecklist as $uchk) @if($desc->bi_id==$uchk->bi_checklist_id && $uchk->v_response===$options[$i]) {{ 'selected'  }}  @endif @endforeach>{{ $options[$i] }}</option>   
              		@endfor
              	</select>
              	
              </td>
              <td><textarea class="form-control txtComments"  name="txtComments" maxlength="250">@foreach($userChecklist as $uchk){{{ ($desc->bi_id==$uchk->bi_checklist_id) ? $uchk->v_comments : '' }}}@endforeach</textarea></td>
              <input type="hidden" class="chkId" value="{{ $desc->bi_id }}" >
            </tr>
            	@endif
            @endforeach
            
            
          @endforeach
        
          
             </tbody></table>
            </div>
            </td>
            </tr>
      
      <!-- Baby Data -->
      
      		 <tr>
              <td style="font-weight: 600;" colspan="6" data-toggle="collapse" data-target="#babyaccordian" class="clickable arrow-toggle" align="center">{{ trans('routes.baby') }}<i style="float:right;" class="fa fa-chevron-circle-up fa-lg"></i></td>
            </tr>
           <tr>
              <td>
              <div id="babyaccordian" class="collapse in">
              <table class="table table-bordered table-striped">
            <tbody>
            
        	@foreach($categoriesBaby as $catB)
        		@if($catB->v_description != "")        	
		            <tr>
		             <th>
              	@if(isset($catB->v_type))
   					 @if($catB->v_type=="Vaccinations")
   						{{ trans('routes.vaccinations') }}
   					 @elseif($catB->v_type=="Check-ups")
   						{{ trans('routes.checkups') }}
   					 @elseif($catB->v_type=="Supplements")
   						{{ trans('routes.supplements') }}
   					 @elseif($catB->v_type=="Delivery Information")
   						{{ trans('routes.deliveryinfo') }}	
   					 @else
   					 	{{ $catB->v_type or '' }}
					 @endif
				@endif
              </th>
              <th>{{ trans('routes.recommendtime') }}</th>
              <th>{{ trans('routes.responsestatus') }}</th>
              <th>{{ trans('routes.anycomments') }}</th>	
		            </tr>
		            
		            @foreach($descrBaby as $desc)
		            	@if($desc->bi_type_id == $catB->bi_id)
		             <tr>
		              <td>{{ $desc->v_description }}</td>
		              <td>@if($desc->v_recommended_time!="custom") {{ $desc->v_recommended_time or '' }} @endif</td>
		              <td>
		               <?php $options = explode('*', $desc->v_response_options );  ?>
		              	<select class="form-control sltResponse" name="sltResponse">
		              	<option value=""> -- Not Selected -- </option>
		              		@for($i=0;$i<count($options);$i++)
		              		<option value="{{ $options[$i] }}" @foreach($userChecklist as $uchk) @if($desc->bi_id==$uchk->bi_checklist_id && $uchk->v_response===$options[$i]) {{ 'selected'  }}  @endif @endforeach>{{ $options[$i] }}</option>   
		              		@endfor
		              	</select>
		              </td>
		               <td><textarea class="form-control txtComments" maxlength="250">@foreach($userChecklist as $uchk){{{ ($desc->bi_id==$uchk->bi_checklist_id) ? $uchk->v_comments : '' }}}@endforeach</textarea></td>
		               <input type="hidden" class="chkId" value="{{ $desc->bi_id }}" >
		            </tr>
		            	@endif
		            @endforeach
            	@endif            
          @endforeach
        
          
             </tbody></table>
            </div>
            </td>
            </tr>
           
            <!-- End Baby data -->
         	
         	
         	<!-- End Checklist -->
          </tbody>
         </table>
             		
             		
             		
           		</div>
           </div>
            <!-- End checklist -->
	               		</div>
	               </div>
	              </div>
               <div class="form-actions">
                  <div class="splitFormSubmitButton">
                    <input type="button" value="Submit" name="save" id="btnSubmit" class="btn btn-primary">
                     	<a class="btn btn-danger" href="{{ url() }}/admin/beneficiary">Cancel</a>
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
<script src="<?php echo Config::get('constant.SITEURL'); ?>external/js_admin/jquery.validate.js"></script>
<style>
	.ajax-loader {
    display: block;
    left: 43%;
    margin-left: -32px;
    margin-top: -32px;
    position: absolute;
    top: 76%;
	}
</style>
<script>
		              		
$(document).ready(function(){

	$("#frmChecklist").validate();


	
	var siteUrl = "<?php echo url(); ?>";
	var token=$("#_token").val();
	// Submit Form but merge options first
	$("#btnSubmit").click(function() {

		$('.txtComments').each(function() {
		    $(this).rules('add', {
		        maxlength: 250,
		    });
		});
		
		var response = {};
		var comments = {};
		var final_array = { };
		var finalArray = [];
		var benid = $('#hndBenId').val();

		var error = false;
		
		$('.txtComments').each(function(){
			if($.trim($(this).val())!=""){
				comments["commentdata"] = ($(this).val());
			}
		})	

		$('.sltResponse').each(function(){
			if($.trim($(this).val())!=""){
				response["responsedata"] = ($(this).val());
			}


		})	
		

		
	if($("#frmChecklist").valid()){

		$('.chkId').each(function(){
			var comment = $.trim($(this).siblings('td').children('.txtComments').val());
			var respond = $(this).siblings('td').siblings('td').children('.sltResponse').val();

			final_array['chkid'] = $(this).val(); 
			final_array['comment'] = comment;
			final_array['respond'] = respond;
			finalArray.push(JSON.stringify(final_array));
		})
	
	
		$.ajax({
			url: siteUrl + "/admin/beneficiary/userchecklist",
			type:"POST",
			data: {
				'_token':token,
				'final':finalArray,
				'benid': benid

			},
			beforeSend:function(){
				$('#loaderdiv').fadeIn();
			},
			success:function(data){
				$('#loaderdiv').fadeOut();
				if(data=="done"){
				 	window.location="<?php echo url().'/admin/beneficiary/view/'.Hashids::encode($beneficiaryid) ?>";
				}		
			}
					
		});
	}
	})
	
	
})
</script>
</body>
</html>