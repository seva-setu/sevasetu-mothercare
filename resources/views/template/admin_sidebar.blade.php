<?php 
	$active = Request::segment(2);
	if($active=='dashboard'){ $dashboard="class='active'"; } else { $dashboard=""; }
	if($active=='adminusrs'){ $users="class='active'"; } else { $users=""; }
	if($active=='callchampions'){ $deals="class='active'"; } else { $deals=""; }
	if($active=='fieldworkers'){ $fieldworker="class='active'"; } else { $fieldworker=""; }
	if($active=='beneficiary'){ $mothers="class='active'"; } else { $mothers=""; }
?>

<nav class="navbar-default navbar-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="main-menu">
			<li class="active-link">
			<a href="{{url()}}/admin/mycalls"><i class="fa fa-phone	 "></i>My calls  <span class="badge">X scheduled</span></a>
			</li>
			
			<li>
			<a href="{{url()}}/admin/mothers"><i class="fa fa-female "></i>Mothers  <span class="badge">Y assigned</span></a>
			</li>
			<li>
			<a href="{{url()}}/admin/settings"><i class="fa fa-list-ol "></i>Check list</a>
			</li>
			<li>
			<a href="{{url()}}/admin/settings"><i class="fa fa-cogs "></i>Settings</a>
			</li>
			
		</ul>
	</div>
</nav>