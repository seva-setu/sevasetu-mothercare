<?php 
	$active = Request::segment(2);
	if($active=='dashboard'){ $dashboard="class='active'"; } else { $dashboard=""; }
	if($active=='adminusrs'){ $users="class='active'"; } else { $users=""; }
	if($active=='callchampions'){ $deals="class='active'"; } else { $deals=""; }
	if($active=='fieldworkers'){ $fieldworker="class='active'"; } else { $fieldworker=""; }
	if($active=='beneficiary'){ $mothers="class='active'"; } else { $mothers=""; }
	if($active=='userprofile'){ $profile="class='active'"; } else { $profile=""; }
	if($active=='changepassword'){ $changepassword="class='active'"; } else { $changepassword=""; }
	if($active=='manageInterventionPoint'){ $intervetion="class='active'"; } else { $intervetion=""; }
	if($active=='searchbenificiary'){ $searchben="class='active'"; } else { $searchben=""; }
	if($active=='addlocation'){ $addlocation="class='active'"; } else { $addlocation=""; }
	if($active=='assignbeneficiary'){ $assignben="class='active'"; } else { $assignben=""; }
	if($active=='assigncallchampion'){ $assigncall="class='active'"; } else { $assigncall=""; }
	if($active=='callchampionlists'){ $callchamps="class='active'"; } else { $callchamps=""; }
	if($active=='checklist'){ $checklist="class='active'"; } else { $checklist=""; }
	if($active=='weeklycalllist'){ $weeklycalllistt="class='active'"; } else { $weeklycalllistt=""; }
	
?>
<div id="sidebar"> 
  <a href="#" class="visible-phone"><i class="icon icon-th-list"></i> Menu</a>
    <ul>
       <li <?php echo $dashboard; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/dashboard"><i class="icon icon-home"></i> <span><?php  echo trans('routes.home'); ?></span></a></li>
       <?php 
       	$userinfo=Session::get('user_logged');?>
       	<?php if($userinfo['v_role']==1){?>
       	<li <?php echo $deals; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/callchampions"><i class="icon icon-user"></i> <span><?php  echo trans('routes.callchampion'); ?></span></a></li>
       <li <?php echo $fieldworker; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/fieldworkers"><i class="icon icon-user"></i> <span><?php  echo trans('routes.fieldworker'); ?></span></a></li>
       <?php }?>
        <?php if($userinfo['v_role']==3 || $userinfo['v_role']==1 || $userinfo['v_role']==2){ ?>
       	<li <?php echo $mothers; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/mothers"><i class="icon-menu fa fa-female"></i> <span><?php  echo trans('routes.mothers'); ?></span></a></li>
       	<?php }else{?>
       		<li <?php echo $users; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/adminusrs"><i class="icon icon-user"></i> <span><?php  echo trans('routes.adminuser'); ?></span></a></li>
       		<li <?php echo $deals; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/callchampions"><i class="icon icon-user"></i> <span><?php  echo trans('routes.callchampion'); ?></span></a></li>
       		<li <?php echo $fieldworker; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/fieldworkers"><i class="icon icon-user"></i> <span><?php  echo trans('routes.fieldworker'); ?></span></a></li>
       		<li <?php echo $mothers; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/mothers"><i class="icon-menu fa fa-female"></i> <span><?php  echo trans('routes.mothers'); ?></span></a></li>
       		<li <?php echo $intervetion; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/manageInterventionPoint"><i class="icon-menu fa fa-calendar"></i> <span><?php  echo trans('routes.interventionpoint'); ?></span></a></li>
       		<li <?php echo $addlocation; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/addlocation"><i class="icon-map-marker"></i> <span><?php  echo trans('routes.location'); ?></span></a></li>
       <?php }?>
       
       <?php if($userinfo['v_role']==0 || $userinfo['v_role']==1){ ?>
       <li <?php echo $checklist; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/checklist/"><i class="icon-menu fa fa-list"></i> <span><?php  echo trans('routes.checklists'); ?></span></a></li>
       <?php } ?>
       
       <?php if($userinfo['v_role']==3){?>
       	<!--  li <?php echo $searchben; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/beneficiary/searchbenificiary"><i class="icon icon-user"></i> <span><?php echo trans('routes.searchbenificiary'); ?></span></a></li>-->
       <?php }?>
       <?php if($userinfo['v_role']==1){?>
       	<li <?php echo $assigncall; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/assigncallchampion"><i class="icon icon-user"></i> <span><?php echo trans('routes.assigncall'); ?></span></a></li>
       <?php }?>
       <?php if($userinfo['v_role']==0 ){/*?>
       	<li <?php echo $assignben; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/assignbeneficiary"><i class="icon icon-user"></i> <span><?php echo trans('routes.assignben'); ?></span></a></li>
       <?php */}?>
       
        <?php if($userinfo['v_role']!=3 ){?>
       	<li <?php echo $weeklycalllistt; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/mycalls"><i class="icon-menu fa fa-phone"></i> <span><?php echo trans('routes.mycalls'); ?></span></a></li>
       <?php }?>
       
       <?php if($userinfo['v_role']==3 || $userinfo['v_role']==1 || $userinfo['v_role']==2){?>
       <li <?php echo $profile; ?>><a href="<?php echo Config::get('constant.SITEURL'); ?>admin/userprofile"><i class="icon icon-user"></i> <span><?php echo trans('routes.profile'); ?></span></a></li>
       <?php }?>
       
  </ul>
</div>