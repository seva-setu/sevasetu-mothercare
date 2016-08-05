
@include('template/admin_title')
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/callchampion_sidebar')
<div id="content">
<div id="content-header">
  <h1>
    <?php  echo trans('routes.beneficiary'); ?>
  </h1>
</div>
<div id="breadcrumb"> <a href="{{ url() }}/dashboard" title="<?php  echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i>
  <?php  echo trans('routes.home'); ?>
  </a><a class="current">{{ trans('routes.checklists') }}</a></div>
<div class="container-fluid">
{{-- Flash Messages --}}
@if(Session::has('success'))
<div class="row-fluid">
  <div class="alert alert-success" style="clear:both;">
    <button data-dismiss="alert" class="close" type="button">&times;</button>
    {{ Session::get('success') }}</div>
</div>
@endif   
    @if(Session::has('danger'))
<div class="row-fluid">
  <div class="alert alert-danger" style="clear:both;">
    <button data-dismiss="alert" class="close" type="button">&times;</button>
    {{ Session::get('danger') }}</div>
</div>
@endif
<div class="row-fluid">
  <div class="span12">
    <div class="widget-box table-responsive">
      <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
        <h5>{{ trans('routes.checklists') }}</h5>
      </div>
      <div class="widget-content">
        <div id="replace_pagecontant"> 
          
          <!-- Check List -->
          
          <table class="table table-bordered table-striped">
              <tbody>
            
            
            <!-- Loop Check list -->
            
            <tr>
              <td colspan="6"><a href="{{ url() }}/checklist/add" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i> {{ trans('routes.addchecklist') }} </a></td>
              
              <!-- Loop Check list --> 
              
            </tr>
            <tr>
              <td style="font-weight: 600;" colspan="6" data-toggle="collapse" data-target="#motheraccordian" class="clickable arrow-toggle" align="center">{{ trans('routes.mother') }}<i style="float:right;" class="fa fa-chevron-circle-up fa-lg"></i></td>
            </tr>
            <tr>
              <td><div id="motheraccordian" class="collapse in">
                  <table class="table table-bordered table-striped">
                    <tbody>
                    
                    @foreach($categoriesMother as $catM)
                    <tr>
                      <th> @if(isset($catM->v_type))
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
                        @endif </th>
                      <th>{{ trans('routes.recommendtime') }}</th>
                      <th>{{ trans('routes.responsestatus') }}</th>
                      <th></th>
                    </tr>
                    @foreach($descr as $desc)
                    @if($desc->bi_type_id == $catM->bi_id)
                    <tr>
                      <td>{{ $desc->v_description }}</td>
                      <td>@if($desc->v_recommended_time!="custom") {{ $desc->v_recommended_time or '' }} @endif</td>
                      <td><?php $options = explode('*', $desc->v_response_options );  ?>
                        <select>
                          <option> -- View Options -- </option>
                          
              		@for($i=0;$i<count($options);$i++)
              		
                          <option>{{ $options[$i] }}</option>
                             
              		@endfor
              	
                        </select></td>
                      <td style="min-width:110px;"><!-- <a class="btn btn-info" href="{{url() }}/checklist/edit/{{ $desc->bi_id or '' }}"><i class="icon-edit icon-white"></i> {{ trans('routes.edit') }}</a> -->
                        
                        <ul class="nav nav-pills">
                          <li> <a href="{{ url() }}/checklist/edit/{{ $desc->bi_id or '' }}"> <img id="detail-icon-img" src="{{ url() }}/external/img/edit.png" alt="ceditimg" height="12" width="16"> {{ trans('routes.edit') }}</a> </li>
                        </ul></td>
                    </tr>
                    @endif
                    @endforeach
                    
                    
                    @endforeach
                      </tbody>
                  </table>
                </div></td>
            </tr>
            
            <!-- Baby Data -->
            
            <tr>
              <td style="font-weight: 600;" colspan="6" data-toggle="collapse" data-target="#babyaccordian" class="clickable arrow-toggle" align="center">{{ trans('routes.baby') }}<i style="float:right;" class="fa fa-chevron-circle-up fa-lg"></i></td>
            </tr>
            <tr>
              <td><div id="babyaccordian" class="collapse in">
                  <table class="table table-bordered table-striped">
                    <tbody>
                    
                    @foreach($categoriesBaby as $catB)
                    @if($catB->v_description != "")
                    <tr>
                      <th> @if(isset($catB->v_type))
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
                        @endif </th>
                      <th>{{ trans('routes.recommendtime') }}</th>
                      <th>{{ trans('routes.responsestatus') }}</th>
                      <th></th>
                    </tr>
                    @foreach($descrBaby as $desc)
                    @if($desc->bi_type_id == $catB->bi_id)
                    <tr>
                      <td>{{ $desc->v_description }}</td>
                      <td>@if($desc->v_recommended_time!="custom") {{ $desc->v_recommended_time or '' }} @endif</td>
                      <td><?php $options = explode('*', $desc->v_response_options );  ?>
                        <select>
                          <option> -- View Options -- </option>
                          
		              		@for($i=0;$i<count($options);$i++)
		              		
                          <option>{{ $options[$i] }}</option>
                             
		              		@endfor
		              	
                        </select></td>
                      <td style="min-width:110px;"><!-- <a class="btn btn-info" href="{{url() }}/checklist/edit/{{ $desc->bi_id or '' }}"><i class="icon-edit icon-white"></i> {{ trans('routes.edit') }} </a>-->
                        
                        <ul class="nav nav-pills">
                          <li> <a href="{{ url() }}/checklist/edit/{{ $desc->bi_id or '' }}"> <img id="detail-icon-img" src="{{ url() }}/external/img/edit.png" alt="ceditimg" height="12" width="16"> {{ trans('routes.edit') }}</a> </li>
                        </ul></td>
                    </tr>
                    @endif
                    @endforeach
                    @endif            
                    @endforeach
                      </tbody>
                  </table>
                </div></td>
            </tr>
            
            <!-- End Baby data --> 
            
            <!-- End Checklist -->
            
              </tbody>
            
          </table>
          
          <!-- End checklist --> 
          
        </div>
      </div>
    </div>
    @include('template/admin_footer') </div>
</div>
@include('template/admin_jsscript')
</body>
</html>