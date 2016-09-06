<?php 
	$active = Request::segment(2);
	//dd($active);
	$string = "class=\"active-link\"";
	if($active=='overall_stat' ){ $dashboard=$string; } else { $dashboard="";}
	if($active=='call_champion'){ $callChampions=$string; } else { $callChampions=""; }
	if($active=='field_worker'){ $actions=$string; } else { $actions=""; }
	if($active=='mother'){ $analysis=$string; } else { $analysis=""; }
	
	$user_stats = Session::get('user_stats');
?>


<nav class="navbar-default navbar-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="main-menu">
			<li <?php echo $dashboard; ?> >
			<a href="{{url()}}/analysis/overall_stat"><i class="fa fa-upload"></i>Overall Statistics  <span class="badge"></span></a>
			</li>
			<li <?php echo $callChampions; ?> >
			<a href="{{url()}}/analysis/call_champion"><i class="fa fa-phone"></i>Call Champions<span class="badge"></span></a>
			</li>
			<li <?php echo $actions; ?> >
			<a href="{{url()}}/analysis/field_worker"><i class="fa fa-list-ol"></i>Field Workers</a>
			</li>
			<li <?php echo $analysis; ?> >
			<a href="{{url()}}/analysis/mother"><i class="fa fa-cogs "></i>Mother</a>
			</li>
			<li>
			<a href="{{url()}}/admins"><i class="fa fa-cogs "></i>Go back To Admin Panel</a>
			</li>

		</ul>
	</div>
</nav>
