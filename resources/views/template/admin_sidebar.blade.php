<?php 
	$active = Request::segment(2);
	$string = "class=\"active-link\"";
	if($active=='dashboard' or $active=='mothers'){ $dashboard=$string; } else { $dashboard=""; }
	if($active=='mycalls'){ $mycalls=$string; } else { $mycalls=""; }
	if($active=='checklist'){ $checklist=$string; } else { $checklist=""; }
	if($active=='settings'){ $settings=$string; } else { $settings=""; }
	if($active=='feedback'){ $feedback=$string; } else { $feedback="";}
	
	$user_stats = Session::get('user_stats');
?>

<nav class="navbar-default navbar-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="main-menu">
			<li <?php echo $dashboard; ?> >
			<a href="{{url()}}/admin/mothers"><i class="fa fa-female "></i>Mothers  <span class="badge">{{ $user_stats['number_mothers_assigned'] }} assigned</span></a>
			</li>
			<li <?php echo $mycalls; ?> >
			<a href="{{url()}}/admin/mycalls"><i class="fa fa-phone	 "></i>My calls  <span class="badge">{{$user_stats['number_of_calls']}} scheduled</span></a>
			</li>
			<li <?php echo $checklist; ?> >
			<a href="{{url()}}/admin/checklist"><i class="fa fa-list-ol "></i>Check list</a>
			</li>
			<li <?php echo $settings; ?> >
			<a href="{{url()}}/admin/settings"><i class="fa fa-cogs "></i>Settings</a>
			</li>
			<li <?php echo $feedback; ?> >
			<a href="{{url()}}/admin/feedback"><i class="fa fa-volume-up"></i>Feedback</a>
			</li>
		</ul>
	</div>
</nav>