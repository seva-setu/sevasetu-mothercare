<?php 
	$active = Request::segment(1);
	$string = "class=\"active-link\"";
	if($active=='actions'){ $actions=$string; } else { $actions=""; }	
?>



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
<?php 
	$userinfo = Session::get('user_logged');
?>


<nav class="navbar-default navbar-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="main-menu">
			<li <?php echo $dashboard; ?> >
			<a href="{{url()}}/mothers"><i class="fa fa-female "></i>Mothers  <span class="badge">{{ $user_stats['number_mothers_assigned'] }} assigned</span></a>
			</li>
			<li <?php echo $mycalls; ?> >
			<a href="{{url()}}/mycalls"><i class="fa fa-phone	 "></i>My calls  <span class="badge">{{$user_stats['number_of_calls']}} scheduled</span></a>
			</li>
			<li <?php echo $actions; ?> >
			<a href="{{url()}}/mothers_actions/{{$userinfo['user_id']}}"><i class="fa fa-list-ol "></i>Action items&nbsp;<span class="badge">{{Session::get('mothers_actions_left')}}</span></a>
			</li>

			<li <?php echo $checklist; ?> >
			<a href="{{url()}}/checklist"><i class="fa fa-list-ol "></i>Check list</a>
			</li>
			<li <?php echo $settings; ?> >
			<a href="{{url()}}/settings"><i class="fa fa-cogs "></i>Settings</a>
			</li>
			<li <?php echo $feedback; ?> >
			<a href="{{url()}}/feedback"><i class="fa fa-volume-up"></i>Feedback and Bugs</a>
			</li>
		</ul>
	</div>
</nav>