@include('template/admin_title')
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
<div id="content">
  <div id="content-header">
    <h1><?php  echo trans('routes.beneficiary'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php  echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php  echo trans('routes.home'); ?></a><a href="<?php echo Config::get('app.url'); ?>admin/beneficiary"><?php  echo trans('routes.beneficiary'); ?></a><a class="current"><?php  echo trans('routes.view'); ?></a></div>
  <div class="container-fluid"> 
  <span class="insertDelMultipleButton">
  </span>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5><?php  echo trans('routes.view')." ".trans('routes.beneficiary'); ?></h5>
          </div>
          <div class="widget-content">
      <div class=" personaldetails table-responsive">
         <table class="table table-bordered table-striped">
          <tbody>
           <tr>
              <td align="center" style="font-weight: 600;"  data-toggle="collapse" data-target="#accordion1" class="clickable arrow-toggle"><?php echo ucwords($result->v_name)."'s ".trans('routes.detail'); ?><i  style="float:right;" class="fa fa-chevron-circle-up fa-lg"></i></td>
            </tr>
           <tr>
              <td>
              <div id="accordion1" class="collapse in">
              <table class="table table-bordered table-striped">
            <tr>
              <td><?php  echo trans('routes.name'); ?></td>
              <td><?php echo $result->v_name; ?></td>
              <td><?php  echo trans('routes.husbandname'); ?></td>
              <td><?php echo $result->v_husband_name; ?></td>	
            </tr>
            <tr>
              <td><?php  echo trans('routes.phonenumber'); ?></td>
              <td><?php echo $result->v_phone_number; ?></td>
              <td><?php  echo trans('routes.altphonenumber'); ?></td>
              <td><?php echo $result->v_alternate_phone_no; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.language'); ?></td>
              <td><?php echo ucwords(trim(trim($check),","));?></td>
                <td><?php  echo trans('routes.nopregnancies'); ?></td>
              <td><?php echo $result->i_number_pregnancies; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.duedate'); ?></td>
              <td><?php echo $duedate;?></td>
               <td><?php  echo trans('routes.deliverydate'); ?></td>
              <td><?php echo $deliverydate;?></td>
            </tr>
            <!-- tr>
              <td><?php  echo trans('routes.awcvame'); ?></td>
              <td><?php echo $result->v_awc_name;?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.awcnumber'); ?></td>
              <td><?php echo $result->v_awc_number;?></td>
            </tr>-->
            </table>
            </div>
            </td>
            </tr>
            
            <!-- Field Worker Details -->
           @if(!empty($fieldresult))
            <tr>
              <td align="center" style="font-weight: 600;" colspan="6" data-toggle="collapse" data-target="#accordionfield" class="clickable arrow-toggle"><?php echo trans('routes.fieldworker')." ".trans('routes.detail'); ?><i  style="float:right;" class="fa fa-chevron-circle-down fa-lg"></i></td>
            </tr>
           <tr>
              <td>
              <div id="accordionfield" class="collapse">
              <table class="table table-bordered table-striped">
            <tr>
            
              <td>{{  trans('routes.name') }}</td>
              <td>{{ $fieldresult->v_name or '' }}</td>
              <td>{{ trans('routes.email') }}</td>
              <td>{{ $fieldresult->v_email or '' }}</td>	
               <td>{{ trans('routes.altphonenumber') }}</td>
              <td>{{ $fieldresult->v_phone_number or '' }}</td>	
            </tr>
            <tr>
              <td>{{ trans('routes.village') }}</td>
              <td>{{ $fieldresult->v_village or '' }}</td>
              <td>{{ trans('routes.taluka') }}</td>
              <td>{{ $fieldresult->v_taluka or '' }}</td>
              <td>{{ trans('routes.city') or '' }}</td>
              <td>{{ $fieldresult->v_district or '' }}</td>	
            </tr>
            <tr>
               <td>{{ trans('routes.zipcode') }}</td>
               <td>{{ $fieldresult->v_pincode or '' }}</td>
			  	<td> {{ trans('routes.state') }}</td>
              <td>{{ $fieldresult->v_state or '' }}</td>
              <td>{{ trans('routes.country') }}</td>
              <td>{{ $fieldresult->v_country or '' }}</td>            
            </tr>
            </table>
            </div>
            
            </td>
            </tr>
            @endif
            <!-- End Field Worker -->
            
                <!-- Call champion Details -->
           @if(!empty($callchampions))
            <tr>
              <td align="center" style="font-weight: 600;" colspan="6" data-toggle="collapse" data-target="#callchampionaccordian" class="clickable arrow-toggle"><?php echo trans('routes.callchampion')." ".trans('routes.detail'); ?><i  style="float:right;" class="fa fa-chevron-circle-down fa-lg"></i></td>
            </tr>
           <tr>
              <td>
              <div id="callchampionaccordian" class="collapse">
              <table class="table table-bordered table-striped">
            <tr>
            
              <td>{{  trans('routes.name') }}</td>
              <td>{{ $callchampions->v_name or '' }}</td>
              <td>{{ trans('routes.email') }}</td>
              <td>{{ $callchampions->v_email or '' }}</td>	
               <td>{{ trans('routes.altphonenumber') }}</td>
              <td>{{ $callchampions->v_phone_number or '' }}</td>	
            </tr>
            <tr>
              <td>{{ trans('routes.village') }}</td>
              <td>{{ $callchampions->v_village or '' }}</td>
              <td>{{ trans('routes.taluka') }}</td>
              <td>{{ $callchampions->v_taluka or '' }}</td>
              <td>{{ trans('routes.city') or '' }}</td>
              <td>{{ $callchampions->v_district or '' }}</td>	
            </tr>
            <tr>
               <td>{{ trans('routes.zipcode') }}</td>
               <td>{{ $callchampions->v_pincode or '' }}</td>
			  	<td> {{ trans('routes.state') }}</td>
              <td>{{ $callchampions->v_state or '' }}</td>
              <td>{{ trans('routes.country') }}</td>
              <td>{{ $callchampions->v_country or '' }}</td>            
            </tr>
            </table>
            </div>
            
            </td>
            </tr>
            @endif
            <!-- End Call champion -->
            
            <tr>
              <td align="center" style="font-weight: 600;" colspan="6" data-toggle="collapse" data-target="#accordion" class="clickable arrow-toggle"><?php echo trans('routes.contactdetail'); ?><i  style="float:right;" class="fa fa-chevron-circle-down fa-lg"></i></td>
            </tr>
            <tr>
              <td>
              <div id="accordion" class="collapse" >
              <table class="table table-bordered table-striped">
             <tr>
              <td><?php  echo trans('routes.village'); ?></td>
              <td><?php echo $result->v_village; ?></td>
              <td><?php  echo trans('routes.taluka'); ?></td>
              <td><?php echo $result->v_taluka; ?></td>
              <td><?php  echo trans('routes.city'); ?></td>
              <td><?php echo $result->v_district; ?></td>	
            </tr>
            <tr>
               <td><?php echo trans('routes.zipcode'); ?></td>
               <td><?php echo $result->v_pincode; ?></td>
			  	<td><?php  echo trans('routes.state'); ?></td>
              <td><?php echo $result->v_state; ?></td>
              <td><?php  echo trans('routes.country'); ?></td>
              <td><?php echo $result->v_country; ?></td>            
            </tr>
            </table>
            </div>
            </td>
            </tr>
          </tbody>
         </table>
        </div>
        
       <!-- Intervention and other tabs -->
         <!-- <table class="table table-bordered table-striped">
         <tbody>
            <tr>
              <td > -->
       
               <ul class="nav nav-tabs" id="product-table">
                <li class="active"><a href="#faq-cat-1" data-toggle="tab">{{ trans('routes.interventionpoint') }}</a></li>
                <li><a href="#faq-cat-2" data-toggle="tab">{{ trans('routes.callsummary') }}</a></li>
                <li><a href="#faq-cat-3"  data-toggle="tab">{{ trans('routes.checklists') }}</a></li>
                <li><a href="#faq-cat-4"  data-toggle="tab">{{ trans('routes.actionitem') }}</a></li>
              </ul>
              <!-- </td>
            </tr> -->
            
            <div class="tab-content">
             <div class="tab-pane active table-responsive" id="faq-cat-1">
              <table class="table table-bordered table-striped">
             <tr>
              <th>{{ trans('routes.srno') }}</th>
              <th>{{ trans('routes.title') }}</th>
              <th>{{ trans('routes.date') }}</th>
            </tr>
            
            <?php 
            $i=0;
            foreach ($intervention as $k=>$v){
            $i++
            ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php if($v->v_name!=""){ echo $v->v_name;}?></td>
			  <td><?php echo $lmpddate=date('d/m/Y',strtotime("+$v->i_week week".$intecaldate)); ?></td>
            </tr>
            <?php }?>
            </table>
            </div>
            
               <div class="tab-pane table-responsive" id="faq-cat-2">     
               
              <table  class="table table-bordered table-striped">
           @if(!empty($callchampions))       
              @if($permission['addcallreport']) 
             <td colspan="6"><a class="btn btn-primary" onClick="showCallSummary('','<?php echo $result->bi_id; ?>');" href="javascript:void(0);"><i class="icon-plus-sign icon-white"></i> {{ trans('routes.addcallreport') }}</a></td>
            @endif
             <tr>
              <th>{{ trans('routes.srno') }}.</th>
              <th>{{ trans('routes.calldate') }}</th>
              <th>{{ trans('routes.callduration') }}</th>
              <th>{{ trans('routes.callstatus') }}</th>
              <th>{{ trans('routes.callsummary') }}</th>
              @if($permission['editcallsummary'])        
              <th>{{ trans('routes.action') }}</th>
              @endif
            </tr>
            <?php 
            $i=0;
            if(count($callsummary)>0){
            foreach ($callsummary as $k=>$v){
            $i++
            ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo date('d/m/Y',strtotime($v->dt_created_at)); ?></td>
			  <td><?php echo $v->i_call_duration; ?></td>
			  <td><?php echo $v->e_call_status; ?></td>
			  <td><?php 
                          if(isset($v->v_conversation)){
                          	$words = explode(" ",nl2br($v->v_conversation));
                          	$count = str_word_count(nl2br($v->v_conversation));
                          	echo implode(" ",array_splice($words,0,7));
                          		if($count > 7) { 
                          			echo ".... ";
                          		}
                           }
                          ?></td>
                @if($permission['editcallsummary'])          
			  <td><a href="javascript:void(0);"  onclick="showCallSummary('<?php echo $v->bi_id; ?>');" class="btn btn-info"><i class="icon-edit icon-white"></i> {{ trans('routes.edit') }}</a></td>
            @endif
            </tr>
            <?php }}else{?>
            <tr>
              <td>No record found</td>
           </tr>
            <?php }?>
             @else
        			<div class="alert alert-danger">{{ trans('routes.nocallchampionexist') }} @if($permission['canassigncallchampion']) <a href="javascript:void(0);" class="btn btn-info" onClick="showCallChamption('{{ $result->i_address_id or '' }}','{{ $result->bi_id or '' }}','{{ $result->v_name or '' }}')" >{{ trans('routes.assigncall') }}</a> @endif</div>
          
        		
             @endif
            </table>
            
            </div>
           
            
            <!-- Check List -->
           <div class="tab-pane table-responsive" id="faq-cat-3">            
           		<div id="faq-cat-2" class="tab-pane active">            
                        		
          <table class="table table-bordered table-striped">
           @if(!empty($callchampions))
          <tbody>
       {{--   @if($permission['canupdatechecklist']) 
                <td colspan="6"><a class="btn btn-info" href="{{ url() }}/admin/beneficiary/userchecklist/{{ Hashids::encode($result->bi_id) }}"><i class="icon-edit icon-white"></i> {{ trans('routes.editchecklist') }} </a></td>
        @endif
         --}}
         
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
                @if($permission['canupdatechecklist'])   <th>{{ trans('routes.edit') }}</th> @endif
            </tr>
            
            @foreach($descr as $desc)
            	@if($desc->bi_type_id == $catM->bi_id)
             <tr id="tr{{ $desc->bi_id }}">
              <td>{{ $desc->v_description }}</td>
              <td>@if($desc->v_recommended_time!="custom") {{ $desc->v_recommended_time or '' }} @endif</td>
              <td class="tdResponse">@foreach($userChecklist as $uchk) @if($desc->bi_id==$uchk->bi_checklist_id) {{  $uchk->v_response }}  @endif @endforeach</td>
              <td class="tdComment">@foreach($userChecklist as $uchk){{{ ($desc->bi_id==$uchk->bi_checklist_id) ? $uchk->v_comments : '' }}}@endforeach</td>
               @if($permission['canupdatechecklist'])    <td><button class="btn btn-info btnEditChecklist" type="button"><i class="icon-edit icon-white"></i> {{ trans('routes.edit') }}</button></td> @endif
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
               @if($permission['canupdatechecklist'])   <th>{{ trans('routes.edit') }}</th> @endif
		            </tr>
		            
		            @foreach($descrBaby as $desc)
		            	@if($desc->bi_type_id == $catB->bi_id)
		             <tr>
		            <tr id="tr{{ $desc->bi_id }}">
              <td>{{ $desc->v_description }}</td>
              <td>@if($desc->v_recommended_time!="custom") {{ $desc->v_recommended_time or '' }} @endif</td>
              <td class="tdResponse">@foreach($userChecklist as $uchk) @if($desc->bi_id==$uchk->bi_checklist_id) {{  $uchk->v_response }}  @endif @endforeach</td>
              <td class="tdComment">@foreach($userChecklist as $uchk){{{ ($desc->bi_id==$uchk->bi_checklist_id) ? $uchk->v_comments : '' }}}@endforeach</td>
                @if($permission['canupdatechecklist'])  <td><button class="btn btn-info btnEditChecklist" type="button"><i class="icon-edit icon-white"></i> {{ trans('routes.edit') }}</button></td> @endif
              <input type="hidden" class="chkId" value="{{ $desc->bi_id }}" >
            </tr>
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
            @else
        		<div class="alert alert-danger">{{ trans('routes.nocallchampionexist') }} @if($permission['canassigncallchampion']) <a href="javascript:void(0);" class="btn btn-info" onClick="showCallChamption('{{ $result->i_address_id or '' }}','{{ $result->bi_id or '' }}','{{ $result->v_name or '' }}')" >{{ trans('routes.assigncall') }}</a> @endif</div>
        	@endif
         </table>
             		
             		
             		
           		</div>
           </div>
            <!-- End checklist -->
            
            <div class="tab-pane table-responsive" id="faq-cat-4">
              <table class="table table-bordered table-striped">
              @if(!empty($callchampions))
            @if($permission['addactionitem'])
             <tr>
           <!-- <td colspan="6"><a class="btn btn-primary" href="javascript:void(0);"  onclick=><i class="icon-plus-sign icon-white"></i> Add Action Item</a></td> -->
           <td colspan="6"><a class="btn btn-primary" onClick="showActionItem('','{{ $result->bi_id or '' }}');" href="javascript:void(0);"><i class="icon-plus-sign icon-white"></i> {{ trans('routes.addactionitem') }}</a></td>
            </tr>
            @endif
            
             <tr>
              <th>{{ trans('routes.srno') }}.</th>
              <th>{{ trans('routes.generateddate') }}</th>
              <th>{{ trans('routes.action') }}</th>
              <th>{{ trans('routes.completiondate') }}</th>
              <th>{{ trans('routes.comment') }}</th>
              <th>{{ trans('routes.edit') }}</th>
      
            </tr>
            
            <?php 
            $i=0;
            if(count($actionitem)>0){
            foreach ($actionitem as $k=>$v){
            $i++
            ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo date('d/m/Y',strtotime($v->dt_created_at)); ?></td>
			  <td>
			  <?php if(isset($v->t_emergency_note)){
                    $words = explode(" ",nl2br($v->t_emergency_note));
                    $count = str_word_count(nl2br($v->t_emergency_note));
                    echo implode(" ",array_splice($words,0,7));
                    	if($count > 7) { 
                        	echo ".... ";
                         }
                    }
              ?>
			  </td>
			  <td><?php echo $deliverydate=strtotime($v->dt_complated_at) > 0?date('d/m/Y',strtotime($v->dt_complated_at)):""; ?></td>
			  <td> <?php 
                          if(isset($v->t_emergency_comment)){
                          	$words = explode(" ",nl2br($v->t_emergency_comment));
                          	$count = str_word_count(nl2br($v->t_emergency_comment));
                          	echo implode(" ",array_splice($words,0,10));
                          		if($count > 10) { 
                          			echo ".... ";
                          		}
                           }
                          ?></td>
         
	            @if(isset($v->ti_iscomplete) && $v->ti_iscomplete)         
	           	  <td><a href="javascript:void(0);"  onclick="showActionItem('<?php echo $v->bi_id; ?>');" class="btn btn-success"><i class="icon-check icon-white"></i> {{ trans('routes.view') }}</a></td>
	            @else 
	            	@if($permission['cancompleteaction'])
				      <td><a href="javascript:void(0);"  onclick="showActionItem('<?php echo $v->bi_id; ?>');" class="btn btn-info"><i class="icon-edit icon-white"></i> {{ trans('routes.update') }}</a></td>
	           		@else
	           		  <td><a href="javascript:void(0);"  onclick="showActionItem('<?php echo $v->bi_id; ?>');" class="btn btn-info"><i class="icon-edit icon-white"></i> {{ trans('routes.edit') }}</a></td>
	           		@endif  
	            @endif
        
            
            </tr>
            <?php }}else{?>
            <tr>
              <td>No record found</td>
           </tr>
            <?php }?>
            
             @else
        		<div class="alert alert-danger">{{ trans('routes.nocallchampionexist') }} @if($permission['canassigncallchampion']) <a href="javascript:void(0);" class="btn btn-info" onClick="showCallChamption('{{ $result->i_address_id or '' }}','{{ $result->bi_id or '' }}','{{ $result->v_name or '' }}')" >{{ trans('routes.assigncall') }}</a> @endif</div>
             @endif
             
            </table>
            </div>
            <table>
             <tr>
            <td colspan="2">
            <a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/edit/'.Hashids::encode($result->bi_id) ?>"><i class="icon-edit icon-white"></i><?php  echo trans('routes.edit'); ?></a>
			<!--  a class="btn btn-danger" onclick="return singleCheckDel();" href="<?php echo Config::get('app.url').'admin/beneficiary/delete/'.Hashids::encode($result->bi_id); ?>"><i class="icon-remove icon-white"></i><?php  echo trans('routes.delete'); ?></a>-->
            <?php if($result->e_status=="Active"){?>
            	<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/delete/'.Hashids::encode($result->bi_id)."/".Hashids::encode(0); ?>"></i><?php echo trans('routes.inactive'); ?></a>					  	
            <?php }elseif($result->e_status=="Inactive"){?>
            	<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/delete/'.Hashids::encode($result->bi_id)."/".Hashids::encode(1); ?>"></i><?php echo trans('routes.active'); ?></a>					  	
            <?php }?>
            <a href="<?php echo Config::get('app.url');?>admin/beneficiary" class="btn btn-primary"><?php  echo trans('routes.back'); ?></a></td>
            </tr>
            </table>
           </div>
       <!--   </tbody>
         </table> -->
          </div>
        </div>
      </div>
    </div>
    @include('template/admin_footer')
  </div>

 <!-- Checklist Modal -->
 <div class="modal fade hide" id="checkListModal" tabindex="-1" role="dialog" aria-labelledby="checkListModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="checkListModalLabel">{{ trans('routes.editchecklist') }}</h4>
	      </div>
	      
	     <div class="modal-body">
	       <form class="form-horizontal beneficiary_btn" accept-charset="utf-8" method="POST" id="frmChecklist" name="frmChecklist">
				<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			    <input type="hidden" id="chkBeneficiaryId" name="chkBeneficiaryId" value="{{ $result->bi_id or '' }}">   
			    <input type="hidden" id="chkLid" name="chkLid" value="">   
			  
			  <div class="control-group showDescription" style="display:none">
				<label class="control-label">{{ trans('routes.description') }} : </label>
					<div class="controls">
						<input id="txtDesc"  type="text" class="form-control"  readonly="readonly" value="">
					</div>
			   </div>
			   
			   <div class="control-group showRecommend" style="display:none">
				<label class="control-label">{{ trans('routes.recommendtime') }} : </label>
				 <div class="controls">
						<input id="recommendedtime" type="text" class="form-control" readonly="readonly" value="">
					</div>
			   </div>
				 
			   <div class="control-group">
					<label for="txtComments" class="control-label">{{ trans('routes.responsestatus') }} : </label>
					<div class="controls">
						<select name="sltResponse" id="sltResponse" class="required">
							<option value=""></option>
						</select>
					</div>
				 </div>
			 
			 	 <div class="control-group">
					<label for="txtComments" class="control-label">{{ trans('routes.anycomments') }} : </label>
					<div class="controls">
					<textarea cols="4" rows="2" maxlength="250" id="txtComments" name="txtComments" placeholder="{{ trans('routes.anycomments') }}" @if(!$permission['editactionitem'])  readonly="readonly" @endif></textarea>
					</div>
				 </div>
		 </div>
	</div>
     
     <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('routes.close') }}</button>
        <button type="button" class="btn btn-primary" id="btnSaveChecklist" >{{ trans('routes.savechanges') }}</button>
     </div>
     
     </form>
    </div>
  </div>
<!-- End modal -->
  
         
 <!-- Modal -->
 <div class="modal fade hide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ trans('routes.actionitem') }}</h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal beneficiary_btn" accept-charset="utf-8" method="POST" id="frmActionItem" name="frmActionItem" enctype='multipart/form-data' action="">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
	   <input type="hidden" id="hndrepoId" name="hndrepoId" value="">
	   <input type="hidden" id="hndBeneficiaryId" name="hndBeneficiaryId" value="{{ $result->bi_id or '' }}">
	   
	 <div class="control-group">
			<label for="txtEmergancyNote" class="control-label">{{ trans('routes.actionitem') }} : </label>
			<div class="controls">
			<textarea cols="4" rows="2" id="txtEmergancyNote" name="txtEmergancyNote" placeholder="{{ trans('routes.enteractionitem') }}" @if(!$permission['editactionitem'])  readonly="readonly" @endif></textarea>
			</div>
	 </div>
	 
	 <div class="displayOptional">
      <div class="control-group">
			<label for="txtCompletionDate" class="control-label">{{ trans('routes.completiondate') }} : </label>
			<div class="controls">
				<input type="text" class="form-control" style="cursor: pointer;" id="txtCompletionDate" name="txtCompletionDate"  readonly="readonly" value="">
			</div>
	   </div>
        <div class="control-group">
			<label for="txtComment" class="control-label">{{ trans('routes.comment') }} : </label>
			<div class="controls">
			<textarea id="txtComment"  name="txtComment" cols="4" rows="2" placeholder="{{ trans('routes.entercomment') }}" ></textarea>
			</div>
	   </div>
	   </div>
	
	 </div>
	 </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('routes.close') }}</button>
        <button type="button" class="btn btn-primary" id="btnSaveChanges" onClick="saveActionItem();" >{{ trans('routes.savechanges') }}</button>
      </div>
      </form>
    </div>
  </div>
<!-- End modal -->
  
  <!-- Assign Call Champion Modal  -->
    <div class="modal fade hide mobile_height" id="priceChangeModel" tabindex="-1" role="dialog" aria-labelledby="Beneficiarylabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="Beneficiarylabel"></h4>
      </div>
      <div class="modal-body" id="callchampion-lists">
     </div>
	 </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('routes.close') }}</button>
        <button type="button" class="btn btn-primary" onClick="saveCallChampion();" >{{ trans('routes.save') }}</button>
      </div>
    </div>
  </div>
  <!-- End Assign Call Champion -->
 
  <div class="modal fade hide mobile_height" id="mySummaryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ trans('routes.beneficiarycallsummarydetail') }}</h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal beneficiary_btn" accept-charset="utf-8" method="POST" id="frmSummaryReport" name="frmSummaryReport" enctype='multipart/form-data' action="">
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
	   <input type="hidden" id="hndreposumId" name="hndreposumId" value="">
	   <input type="hidden" id="hndBeneficiarycallId" name="hndBeneficiarycallId" value="">
	   
	    <div class="control-group">
			<label for="txtFirstName" class="control-label">{{ trans('routes.calldate') }} : </label>
			<div class="controls">
				<input type="text" class="form-control" style="cursor: pointer;" id="txtCallDate" name="txtCallDate" readonly="readonly" value="">
			</div>
	   </div>
     <div class="control-group">
			<label for="txtFirstName" class="control-label">{{ trans('routes.callduration') }} : </label>
			<div class="controls">
			<input type="text" id="txtCallDuration" name="txtCallDuration" placeholder="{{ trans('routes.entercallduration') }}" >
			</div>
	 </div>
      <div class="control-group">
			<label for="txtFirstName" class="control-label">{{ trans('routes.callstatus') }} : </label>
			<div class="controls">
				<select name="selCallStatus" id="selCallStatus">
				<option value="Received">Received</option>
				<option value="Not Received">Not Received</option>
				<option value="Not Reachble">Not Reachble</option>
				<option value="Incorrect Number">Incorrect Number</option>
				</select>
			</div>
	   </div>
        <div class="control-group">
			<label for="txtFirstName" class="control-label">{{ trans('routes.callsummary') }} : </label>
			<div class="controls">
			<textarea id="txtrepoComment"  name="txtrepoComment" cols="4" rows="2" placeholder="{{ trans('routes.entercallsummary') }}" ></textarea>
			</div>
	   </div>
	   <div class="control-group">
			<label for="txtFirstName" class="control-label">{{ trans('routes.actionitem') }} : </label>
			<div class="controls">
			<textarea id="txtEmergancyNoteCall"  name="txtEmergancyNoteCall" cols="4" rows="2" placeholder="{{ trans('routes.enteractionitem') }}" ></textarea>
			</div>
	   </div>
	 </div>
	 </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('routes.close') }}</button>
        <button type="button" class="btn btn-primary" onClick="saveCallSummary();" >{{ trans('routes.savechanges') }}</button>
      </div>
      </form>
    </div>
  </div>
</div>
  </div>
@include('template/admin_jsscript')
<script src="<?php echo Config::get('constant.SITEURL'); ?>external/js_admin/jquery.validate.js"></script>
</body>
</html>
<script>
$(document).ready(function(){

	
	//Checklist Modal
	$('.btnEditChecklist').click(function(){
		var chkId = $(this).parents('td').siblings('.chkId').val();
		var beneficiaryId = $('#chkBeneficiaryId').val();
			
		$.ajax({
			url: siteurl + "admin/beneficiary/getUserCheckById",
			type:"POST",
			
			data:{
				'_token':token,
				'beneficiaryid' : beneficiaryId,
				'chklist_id': chkId
			},
			beforeSend:function(){
				$('#loaderdiv').fadeIn();
			},
			success:function(data){
				$('#loaderdiv').fadeOut();
				
			    var chklist_data=$.parseJSON(data);
		        if(jQuery.isEmptyObject(chklist_data)){
			        
		        	$('#txtComments').val("");
		        }else{
		        	var options = chklist_data.v_response_options.split('*');
		        	$('#sltResponse').html('<option value=""> -- Select Response -- </option>');
			        $(options).each(function(index,value){
				        var isSelected = "";
				        if(chklist_data.v_response){
					        if(chklist_data.v_response.toLowerCase() === value.toLowerCase()){
					        	isSelected = "selected='selected'";
						        }    
				        }
						$('#sltResponse').append('<option value="'+ value + '"'+ isSelected + ' >'+value+'</option>');
			        });   

			    	$('.showDescription').show();
					$('#txtDesc').val(chklist_data.v_description);
					$('#chkLid').val(chklist_data.bi_checklist_id)
					
					if(chklist_data.v_recommended_time!=""){
						$('.showRecommend').show();
						$('#recommendedtime').val(chklist_data.v_recommended_time)
					}else{
						$('.showRecommend').hide();
					}		

					if(chklist_data.v_comments){	        
						$('#txtComments').val(chklist_data.v_comments);
					}
			    }	
				$('#checkListModal').modal('show');
			},
			error: function(){
	              //alert('error handing here');
	        }
		})	

		
	})	 
	
	//Save Checklist
	$('#btnSaveChecklist').click(function(){
		var chkId = $('#chkLid').val();
		var beneficiaryId = $('#chkBeneficiaryId').val();
		var response = $('#sltResponse').val();
		var comments = $('#txtComments').val();

		$("#frmChecklist").validate();

		 $('#txtComments').rules('add', {
		        maxlength: 250,
		    });	

		if($('#frmChecklist').valid()){		
		$.ajax({
			url: siteurl + "admin/beneficiary/saveUserCheckById",
			type:"POST",
			data: {
				'_token':token,
				'checklist_id': chkId,
				'beneficiary_id': beneficiaryId,
				'response': response,
				'comments': comments
			},
			beforeSend:function(){
				$('#loaderdiv').fadeIn();
			},
			success:function(data){
				$('#loaderdiv').fadeOut();
				if(data=="success"){
					
					$('#tr'+chkId+ ' > .tdResponse ').text(response);	
					$('#tr'+chkId+ ' > .tdComment ').text(comments);	
					
				}
				$('#checkListModal').modal('hide');
			}	
		 });
		}
		
	})
	 
	 
	$("#frmSummaryReport").validate({
		ignore: ":hidden",
		rules: {
			txtCallDate:"required",
			txtCallDuration:"required",
			txtrepoComment:"required"
	    },
	   	messages: {
	   		txtCallDate:"",
			txtCallDuration:"",
			txtrepoComment:""
	   }
	})
}); 
$(document).ready(function(){
	$("#frmActionItem").validate({
		ignore: ":hidden",
		rules: {
			txtEmergancyNote:"required",
			txtCompletionDate:"required",
			txtComment:"required"
	    },
	   	messages: {
	   		txtEmergancyNote:"",
			txtCompletionDate:"",
			txtComment:""
	   }
	})
	$("#frmInsertActionItem").validate({
		ignore: ":hidden",
		rules: {
			txtActionEmergancyNote:"required",
	    },
	   	messages: {
	   		txtActionEmergancyNote:"",
	   }
	})
}); 
 $(".arrow-toggle").click(function(){
	 var classflag=$(this).find("i").hasClass('fa-chevron-circle-up');
	if(classflag==true){
		$(this).find("i").removeClass('fa-chevron-circle-up');
		$(this).find("i").addClass('fa-chevron-circle-down');
	}else{
		$(this).find("i").removeClass('fa-chevron-circle-down');
		$(this).find("i").addClass('fa-chevron-circle-up');
	}
		
 });

var token=$("#_token").val();
var siteurl="<?php echo Config::get('app.url')?>";

function InsertActionItem(){
	if($("#frmInsertActionItem").valid()){
		var datastring=$("#frmInsertActionItem").serialize();
		$.ajax({
		            type: "POST",
		            url: siteurl+"admin/beneficiary/insertBeneficiaryReport",
		            data: datastring,
		            beforeSend:function(){
						$('#loaderdiv').fadeIn();
			        },
		            success: function(data) {
		            	$('#loaderdiv').fadeOut();
		            	$("#InsertModal").modal('hide');
		            	location.href = location.href;
		            },
		            error: function(){
		                  //alert('error handing here');
		            }
		 });
	}
}

function showActionItem(id,beneficiaryid){

	if(id==""){
		// Insert Form
		$("#txtComment").rules("remove", "required");
		$("#txtCompletionDate").rules("remove", "required");
		$(".displayOptional").hide();
	}else{
		// Update Form
		$("#txtComment").rules("add", "required");
		$("#txtCompletionDate").rules("add", "required");
		$(".displayOptional").show();
	}
	
	$("label.error").hide();
	  $(".error").removeClass("error");
	$.ajax({
        type: "POST",
        url: siteurl+"admin/beneficiary/getBeneficiaryReport",
        data: {'_token':token,'reportid':id},
        beforeSend:function(){
			$('#loaderdiv').fadeIn();
        },
        success: function(data){
        	$('#loaderdiv').fadeOut();
            var txt=$.parseJSON(data);
        	if(jQuery.isEmptyObject(txt)){
        		$("#hndrepoId").val("");
        		$("#hndBeneficiaryId").val(beneficiaryid);
				$("#txtEmergancyNote").val("");
				//$("#txtCompletionDate").val("<?php echo date('d/m/Y'); ?>");
				$("#txtCompletionDate").val("");
				$("#txtComment").val("");
				$("#btnSaveChanges").show();
				$("#btnSaveChanges").text(" {{ trans('routes.savechanges') }} ");
	            $("#myModal").modal('show');
            }else{	
	            $("#hndrepoId").val(txt.bi_id);
	            $("#hndBeneficiaryId").val(beneficiaryid);
	            $("#txtEmergancyNote").val(txt.t_emergency_note);
				if(txt.dt_complated_at!="0000-00-00")	
					$("#txtCompletionDate").val(formatDate(txt.dt_complated_at));
				else
				$("#txtCompletionDate").val("");
				$("#txtComment").val(txt.t_emergency_comment);

				if(txt.ti_iscomplete==1){
					$("#btnSaveChanges").hide();
					$("#txtComment").attr('readonly','readonly');
					$("#txtEmergancyNote").attr('readonly','readonly');
					$("#txtCompletionDate").attr('disabled','disabled');
				}else{
					$("#btnSaveChanges").show();
					var cancompleteaction = "<?php echo $permission['cancompleteaction']; ?>";
					var editactionitem = "<?php echo $permission['editactionitem']; ?>";


					//Can User edit action item text.	
					if(editactionitem){
						$('#txtEmergancyNote').removeAttr('readonly');
					}else{
						$('#txtEmergancyNote').attr('readonly','readonly');
					}

					//Can User close/complete action item.
					if(cancompleteaction){
						$("#btnSaveChanges").text("{{ trans('routes.completed') }}");
						$("#txtCompletionDate").removeAttr('disabled');
						$("#txtComment").removeAttr('readonly');
						
						$("#txtComment").rules("add", "required");
						$("#txtCompletionDate").rules("add", "required");
					
						
						$(".displayOptional").show();
					}else{
						$("#btnSaveChanges").text("{{ trans('routes.savechanges') }} ");
						$("#txtCompletionDate").attr('disabled');
						$("#txtComment").attr('readonly','readonly');
						
						$("#txtComment").rules("remove", "required");
						$("#txtCompletionDate").rules("remove", "required");
						
						$(".displayOptional").hide();
					}
					//	$("#txtComment").removeAttr('readonly');
					//	$("#txtEmergancyNote").removeAttr('readonly');
					//	$("#txtCompletionDate").removeAttr('disabled');
				}
				
	            $("#myModal").modal('show');
        	}
        },
        error: function(){
              //alert('error handing here');
        }
    });

    
}

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
            $("#Beneficiarylabel").text(" <?php echo trans('routes.assigncallchampionto'); ?> "+name);
            $("#callchampion-lists").html(data);
			$("#priceChangeModel").modal('show');
			
        },
        error: function(){
              //alert('error handing here');
        }
    });
}

function saveCallChampion(){
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
            beforeSend:function(){
            	$('#loaderdiv').fadeIn();
            },
            success: function(data){
            	$('#loaderdiv').fadeOut();
                $("#priceChangeModel").modal('hide');
              	location.href = location.href;
    		},
            error: function(){
                  //alert('error handing here');
            }
        });	
	}
}


function showCallSummary(id,beneficiaryid){
	$("label.error").hide();
	$(".error").removeClass("error");
	$.ajax({
        type: "POST",
        url: siteurl+"admin/beneficiary/getCallShummery",
        data: {'_token':token,'sumreportid':id},
        beforeSend:function(){
			$('#loaderdiv').fadeIn();
        },
        success: function(data){
            $('#loaderdiv').fadeOut();
            var txt=$.parseJSON(data);
        	if(jQuery.isEmptyObject(txt)){
        		$("#hndreposumId").val("");
        		$("#hndBeneficiarycallId").val(beneficiaryid);
        		$("#txtCallDate").val("<?php echo date('d/m/Y'); ?>");
				$("#txtCallDuration").val("");
				$("#txtrepoComment").val("");
				$("#txtEmergancyNoteCall").val("");
				$("#mySummaryModal").modal('show');
            }else{	
            	$("#hndreposumId").val(txt.bi_id);
            	$("#hndBeneficiarycallId").val(beneficiaryid);
        		$("#txtCallDate").val(formatDate(txt.dt_created_at));
				$("#txtCallDuration").val(txt.i_call_duration);
				$('#selCallStatus option[value="'+txt.e_call_status+'"]').attr('selected','selected');
				$("#txtrepoComment").val(txt.v_conversation);
				$("#txtEmergancyNoteCall").val(txt.t_emergency_note);
				$("#mySummaryModal").modal('show');
        	}
        },
        error: function(){
              //alert('error handing here');
        }
    });
}
$(document).ready(function(){

	 
	 
	 
   	$(function() {
			$( "#txtCompletionDate" ).datepicker({  
				format: "dd/mm/yyyy",
				autoclose: true,
				endDate:"today",			    
			});
			
			$( "#txtCallDate" ).datepicker({  
				format: "dd/mm/yyyy",
			    //endDate: "today",
			    autoclose: true
			});
		});
   });
 function saveActionItem(){
		var datastring=$("#frmActionItem").serialize();
		if($("#frmActionItem").valid()){
		$.ajax({
		            type: "POST",
		            url: siteurl+"admin/beneficiary/updateBeneficiaryReport",
		            data: datastring,
		            beforeSend:function(){
		    			$('#loaderdiv').fadeIn();
		            },
		            success: function(data) {
		            	$('#loaderdiv').fadeOut();
			            $("#myModal").modal('hide');
		            	window.location="<?php echo Config::get('app.url').'admin/beneficiary/view/'.Hashids::encode($result->bi_id) ?>";
		            },
		            error: function(){
		                  //alert('error handing here');
		            }
		        });
		}
}
 function saveCallSummary(){
	 var datastring=$("#frmSummaryReport").serialize();
	 if($("#frmSummaryReport").valid()){
		$.ajax({
		            type: "POST",
		            url: siteurl+"admin/beneficiary/updateBeneficiaryCall",
		            data: datastring,
		            beforeSend:function(){
		    			$('#loaderdiv').fadeIn();
		            },
		            success: function(data) {
		            	$('#loaderdiv').fadeOut();
		            	$("#mySummaryModal").modal('hide');
		             	window.location="<?php echo Config::get('app.url').'admin/beneficiary/view/'.Hashids::encode($result->bi_id) ?>";
				     },
		            error: function(){
		                  //alert('error handing here');
		            }
		        });
	 }




}
</script>
<script>
$('#product-table a:first').tab('show');
</script>
