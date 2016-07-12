<!DOCTYPE html>
<html lang="en">
<head>
<title>{{ $title or '' }}</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@include('template/admin_cssscripta')
<link rel="stylesheet" href="<?php echo Config::get('constant.SITEURL'); ?>external/css_admin/dashboard.css" />
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
<div id="content">
  <div id="content-header">
    <h1><?php  echo trans('routes.dashboard'); ?></h1>
  </div>
  <?php echo Session::get('message'); ?>
  <div id="breadcrumb">
   	<a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php echo trans('routes.homelabel')?>" class="tip-bottom"><i class="icon-home"></i><?php  echo trans('routes.home'); ?></a><a href="javascript:void(0);" class="current"><?php  echo trans('routes.dashboard'); ?></a> 
   </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-bookmark"></i> </span>
            <h5><?php echo trans('routes.shortcuts')?></h5>
          </div>
          <div class="widget-content">
            <div align="center" class="row-fluid">
              <div class="shortcuts">
               <?php 
               $userinfo=Session::get('user_logged');
               ?> 
               
		       <?php if($userinfo['v_role'] == 2){?>
					<a class="shortcut" href="<?php echo Config::get('constant.SITEURL');?>
					admin/mothers"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label">
					<?php 
						echo trans('routes.assigned_beneficiary').":<br/>";
						foreach($assigned_beneficiaries as $mother)
							echo("<b>".$mother[0]."</b> from <b>".$mother[1]."</b><br/>");
					 
					?></span> </a> 
				   
				   <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/mycalls"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label">
				   <?php 
						echo trans('routes.next_scheduled').": ".$next_scheduled_call;
					?></span> </a> 
				   
					<a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/mycalls"> <i class="shortcut-icon icon-edit"></i> <span class="shortcut-label">
					<?php echo trans('routes.number_calls').": ".$number_of_calls; ?></span> </a>
				<?php }?>	
				
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('template/admin_footer')
  </div>
</div>
@include('template/admin_jsscript')
</body>
</html>
