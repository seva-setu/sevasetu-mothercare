<?php 
$userinfo=Session::get('user_logged');
//$username= DB::table('mct_users')->where('user_id', $userinfo['user_id'])->value('v_name');
?>
<div id="wrapper">
<div class="navbar navbar-inverse navbar-fixed-top">
	
	<div class="adjust-nav">
		<div class="navbar-header">
			
			<span class="logout-spn" style="margin:10px 25px 10px 10px;float:left;">
				<a href="#" style="color:#fff;font-size:18px;">Seva Setu's Mother care tool</a>  
		    </span>
		    
		</div>
	   <span class="logout-spn" style="margin-left:5%;margin-right:5%;">
	   <a href="<?php echo url(); ?>/admin/logout" style="color:#fff;font-size:15px;">Logout</a>  
	   </span>
	   
	   <div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse" style="margin-right:0%;float:left;">
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