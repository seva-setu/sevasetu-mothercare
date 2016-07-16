<?php 
$userinfo=Session::get('user_logged');
?>
<div id="wrapper">
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="adjust-nav">
	   <div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  </button>
		  <span class="logout-spn" >
			<a href="#" style="color:#fff;">Seva Setu's Mother care tool</a>  
		  </span>
	   </div>
	   <span class="logout-spn" >
	   <a href="<?php echo url(); ?>/admin/logout" style="color:#fff;">Logout</a>  
	   </span>
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