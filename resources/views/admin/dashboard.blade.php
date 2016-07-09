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
   	<a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php echo trans('routes.homelabel')?>" class="tip-bottom"><i class="icon-home"></i><?php  echo trans('routes.home'); ?></a> <a href="javascript:void(0);" class="current"><?php  echo trans('routes.dashboard'); ?></a> 
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
               <?php if($userinfo['v_role']==1){?>
       			<a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/callchampions"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.callchampion'); ?></span> </a>
                <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/fieldworkers"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.fieldworker'); ?></span> </a> 
                <?php }?>
		      <?php if($userinfo['v_role']==3 || $userinfo['v_role']==2 || $userinfo['v_role']==1 ){ ?>
               <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/beneficiary"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.beneficiary'); ?></span> </a> 
               <?php }else{?>
                <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/adminusrs"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.adminuser'); ?></span> </a> 
                <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/callchampions"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.callchampion'); ?></span> </a>
                <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/fieldworkers"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.fieldworker'); ?></span> </a> 
                <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/beneficiary"> <i class="dashicon fa fa-female"></i> <span class="shortcut-label"><?php echo trans('routes.beneficiary'); ?></span> </a> 
                <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/manageInterventionPoint"> <i class="dashicon fa fa-calendar"></i> <span class="shortcut-label"><?php echo trans('routes.interventionpoint'); ?></span> </a> 
                <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/addlocation"> <i class="shortcut-icon icon-map-marker"></i> <span class="shortcut-label"><?php echo trans('routes.location'); ?></span> </a> 
                <?php }?>
                <?php if($userinfo['v_role']==1){?>
       			<a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/assigncallchampion"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.assigncall'); ?></span> </a> 
                <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/callchampions"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.callchampion'); ?></span> </a>
                <?php }?>
				
		       	<?php if($userinfo['v_role']==3){?>
		       	<!--  a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/beneficiary/searchbenificiary"><i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.searchbenificiary'); ?></span></a>-->
		       <?php }?>
			   
		       <?php if($userinfo['v_role']==2){/*?>
		       	<a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/assignbeneficiary"><i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.assignben'); ?></span></a>
		       <?php */}?>
			   
		       <?php if($userinfo['v_role']==3 || $userinfo['v_role']==1 || $userinfo['v_role']==2){?>
		       <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/userprofile"> <i class="shortcut-icon icon-user"></i> <span class="shortcut-label"><?php echo trans('routes.profile'); ?></span> </a> 
               <?php }?>	
			   
                <a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/changepassword"> <i class="shortcut-icon icon-edit"></i> <span class="shortcut-label"><?php echo trans('routes.changepassword'); ?></span> </a>
				
				<a class="shortcut" href="<?php echo Config::get('constant.SITEURL'); ?>admin/changepassword"> <i class="shortcut-icon icon-edit"></i> <span class="shortcut-label"><?php echo trans('routes.number_of').": ".$number_of; ?></span> </a>
				
				
                <!--  a class="shortcut" href="<?php //echo Config::get('constant.SITEURLADM'); ?>logout"> <i class="shortcut-icon icon-share-alt"></i> <span class="shortcut-label"><?php echo trans('routes.logout'); ?></span> </a>-->  
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
