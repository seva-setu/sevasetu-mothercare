@include('template/admin_title')
		<link rel="stylesheet" href="{{ url() }}/assets//css_admin/bootstrap.min.css" />
		<link rel="stylesheet" href="{{ url() }}/assets//css_admin/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="{{ url() }}/assets//css_admin/unicorn.login.css" />
        <link rel="stylesheet" href="{{ url() }}/assets//css_admin/style.css" />
        <link rel="stylesheet" href="{{ url() }}/assets//css_admin/custom.css" />
        <link rel="stylesheet" id="tyler-icons-css" href="{{ url() }}/assets/landing/icon.css" type="text/css" media="all">
        <script src="{{ url() }}/assets//js_admin/jquery.min.js"></script>  
        <script src="{{ url() }}/assets//js_admin/unicorn.login.js"></script> 
        <script src="{{ url() }}/assets//js_admin/jquery.validate.js"></script>
        <script src="{{ url() }}/assets//js/function.js"></script> 
        <script type="application/javascript" language="javascript">
		</script>
    </head>
    <body>
	    <form style="float: right;padding:5px;margin:0px;" class="form-horizontal" accept-charset="utf-8" method="POST" id="frmchangeLanguage" name="frmchangeLanguage" action="{{ url() }}/language/chooser">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">		
		</form>
        <!-- <div id="logo">
            <img src="<?php //echo SITEURL; ?>assets//img/logo.png" alt="" />
        </div> -->
        	@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
		
	    <div class="navbar">
	      	<div class="navbar-inner">
	        	<a class="brand" href="{{ url() }}"><span><i class="icon-large icon-home"></i> Home</span></a>
	      	</div>
	    </div>
        
        <div id="loginbox">
        
         	<div style="color:#468847;text-align:center">{{ Session::get('sucmessage') }}</div>
         	<div class="error" style="color:#b94a48;text-align:center">{{ Session::get('message') }}</div>
			<form class="form-horizontal" role="form" method="POST" action="{{ url() }}/login" accept-charset="utf-8" name="loginform" id="loginform">
			<p><?php echo trans('routes.loginlabel'); ?></p>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
        		<div>
                <div class="control-group">
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-large icon-user"></i></span><input type="text" name="txtUserName" id="txtUserName" placeholder="<?php echo trans('routes.email'); ?>" class="required" />
							</div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-large icon-lock" ></i></span><input type="password" name="txtPassword" id="txtPassword" placeholder="<?php echo trans('routes.password'); ?>" class="required" />
                        </div>
                       		<div class="error" style="color:#FF0000;text-align:center">{{ $errors->first('v_password') }}</div> 
							
                    </div>
                </div>
                <div class="form-actions loginbuttondiv">
                    <span class="pull-left"><a href="#" class="flip-link" id="to-recover"><?php echo trans('routes.lostpassword'); ?></a></span>
					<span class="pull-right"><input name="action" type="submit" class="btn btn-inverse" value="<?php echo trans('routes.login'); ?>" /></span>
					<span class="pull-right"><a href="{{ url() }}/auth/register" class="flip-link" id="to-recover" style="
    margin-right: 10px;"><?php echo trans('routes.new_user'); ?></a></span>
					
                </div>
                </div>
             <!--</form>-->
             </form>  
            <form class="form-horizontal" role="form" method="POST" action="{{ url() }}/forgotPassword" id="recoverform" name="recoverform" >
						<input type="hidden" name="_token" id="_token"  value="{{ csrf_token() }}">
               <p><?php echo trans('routes.lostpass_label'); ?></p>
               <div class="control-group">
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-large icon-envelope"></i></span><input type="text" name="txtForgotEmailId" id="txtForgotEmailId" placeholder="<?php echo trans('routes.email'); ?>" class="required" />
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link" id="to-login">&lt; <?php echo trans('routes.loginback')?></a></span>
                    <span class="pull-right"><input type="submit" class="btn btn-inverse" value="<?php echo trans('routes.recover'); ?>" /></span>
                </div>
            </form>
        </div>
    </body>
</html>
<script src="{{ url() }}/assets//js_admin/customevalidation.js"></script>
<script type="application/javascript">
$(document).ready(function(){
		$("#loginform").validate({
		ignore: ":hidden",
		rules: {
			txtUserName: {
				required: true,
				email: true
			},
			txtPassword: "required"
		}
		
		});
});
var token=$("#_token").val();
$(document).ready(function(){
	$("#recoverform").validate({
	ignore: ":hidden",
	rules: {
		txtForgotEmailId: {
			required: true,
			validEmail: true,
			remote: {
				data: {'_token' : token,'action':'add'},
				url : '{{ url() }}/checkEmailLogin',
				type : 'post'
				}
			}
		},
		messages: 
		{
			txtForgotEmailId: {
				required: "",
				validEmail: js_allow_email,
				remote: jQuery.format(js_email_exist)
			}
		}
	});
});
$(document).ready(function() {
    $('form:first *:input[type!=hidden]:first').focus();
});
</script>
