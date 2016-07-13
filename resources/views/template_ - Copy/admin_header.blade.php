<?php 
$userinfo=Session::get('user_logged');
?>
<style>
.navbar {
  margin-bottom: 34px;
  margin-top: 10px;
}
.navbar-inverse .navbar-inner {
    background-color: #444444;
    background-image: none;
    border-color: #444444;
}
.navbar-inverse .brand, .navbar-inverse .nav > li > a {
    color: #ffffff;
}
.navbar .pull-right > li > .dropdown-menu, .navbar .nav > li > .dropdown-menu.pull-right {
    right: 0px;
    left: auto;
    background: #fff;
}
.dropdown-menu li a:hover, .dropdown-menu .active a, .dropdown-menu .active a:hover {
    color: #08C;
    background-color: transparent;
    background-image: none;
}
.dropdown-menu li a {
  color: #333;
}

.dropdown.open a.dropdown-toggle .caret { background-color:#333;color:#333; }
.navbar-inverse .nav li.dropdown.open > .dropdown-toggle, .navbar-inverse .nav li.dropdown.active > .dropdown-toggle, .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle {
    color: #FFF;
    background-color: #444;
}


/* Auto drop down logout button */
.dropdown .dropdown-menu {
  display: block;
  visibility: hidden;
  opacity: 0;
  transition:         all 0.2s  ase;
  -moz-transition:    all 0.2s  ease;
  -webkit-transition: all 0.2s  ease;
  -o-transition:      all 0.2s  ease;
  -ms-transition:     all 0.2s  ease;
}
.open .dropdown-menu {
  visibility: visible;
  opacity: 1;
}
.dropdown {
  display: inline-block;
}


</style>
<header>
<div class="navbar navbar-static-top navbar-inverse">
  <div class="navbar-inner">
    <a class="brand" href="{{ url() }}/admin/dashboard/">Mother<strong>Care</strong></a>
    <ul class="nav pull-right ">
    
      <li class="active">
      <form  accept-charset="utf-8" method="POST" id="frmchangeLanguage" name="frmchangeLanguage" action="{{ url() }}/language/chooser">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<select class="language_dropdown" name="locale" onchange="changeLanguage()">
	    	<option	value="en">English</option>
	    	<option	value="hi" {{ Lang::locale()==='hi' ? 'selected' :'' }}>Hindi (हिंदी)</option>
	    	</select>    	
		</form>
	</li>
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
    ?>
    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome  <?php echo ucwords($userinfo['v_name'])." ".$role; ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <li><a href="{{ url() }}/admin/logout"><i class="dashicon fa fa-sign-out"></i> {{ trans('routes.logout') }}</a></li>
                        </ul>
                    
   </li>	
	<?php }?>
    </ul>
    
   
  </div>
</div>