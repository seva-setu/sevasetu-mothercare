<?php 
$userinfo=Session::get('user_logged');
?>
<div id="wrapper">
<div class="navbar navbar-inverse navbar-fixed-top">
	
	<div class="adjust-nav">
		<div class="navbar-header" style="margin-left:10px;">
			<a target="#" href="#">
				<img class="logo" src="{{ url() }}/assets/img/logo1.jpg" alt="Sevasetu" width="60" height="80">
			</a>
			<span class="logout-spn" style="padding-left:10px;border-left:0px;margin-left:0px">
				<a href="#" style="color:#fff;font-size:20px">Seva Setu's Mother care tool</a>  
		    </span>
		</div>
	   <span class="logout-spn" >
	   <a href="<?php echo url(); ?>/admin/logout" style="color:#fff;font-size:15px;">Logout</a>  
	   </span>
	   <span class="logout-spn" >
	   <p style="color:#fff;font-size:15px;width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Welcome <?php echo $userinfo['v_user_name'] ?></p>
	   </span>
	   <div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  </button>
		  
	   </div>
	   
	</div>
 </div>

<?php if(isset($userinfo['v_name'])){
	$role="";
    if($userinfo['v_role']==0)
    	$role="(Admin)";
    elseif($userinfo['v_role']==1)
    	$role="(Programm Coordinater)";
    elseif($userinfo['v_role']==2)
    	$role="(Call Champion)";
    elseif($userinfo['v_role']==3)
    	$role="(Field Worker)";
	}
?>