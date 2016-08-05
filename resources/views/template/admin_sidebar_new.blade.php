<?php 
	$active = Request::segment(2);
	$string = "class=\"active-link\"";
	if($active=='dashboard' or $active=='upload'){ $dashboard=$string; } else { $dashboard=""; }
	if($active=='callChampions'){ $callChampions=$string; } else { $callChampions=""; }
	if($active=='actions'){ $actions=$string; } else { $actions=""; }
	if($active=='analysis'){ $analysis=$string; } else { $analysis=""; }
	
	$user_stats = Session::get('user_stats');
?>

<nav class="navbar-default navbar-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="main-menu">
			<li <?php echo $dashboard; ?> >
			<a href="{{url()}}/admin/data/upload"><i class="fa fa-upload "></i>Upload data  <span class="badge"></span></a>
			</li>
			<li <?php echo $callChampions; ?> >
			<a href="{{url()}}/admin/callChampions"><i class="fa fa-phone	 "></i>Call Champions  <span class="badge"></span></a>
			</li>
			<li <?php echo $actions; ?> >
			<a href="{{url()}}/admin/actions"><i class="fa fa-list-ol "></i>Action items</a>
			</li>
			<li <?php echo $analysis; ?> >
			<a href="{{url()}}/admin/analysis"><i class="fa fa-cogs "></i>Analysis</a>
			</li>
		</ul>
	</div>
</nav>