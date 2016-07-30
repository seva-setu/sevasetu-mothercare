<html lang="en">
    <head>
        <title></title>
		<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="{{ url() }}/external/css_admin/bootstrap.min.css" />
		<link rel="stylesheet" href="{{ url() }}/external/css_admin/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="{{ url() }}/external/css_admin/unicorn.login.css" />
        <link rel="stylesheet" href="{{ url() }}/external/css_admin/style.css" />
        <link rel="stylesheet" href="{{ url() }}/external/css_admin/custom.css" />
        <script src="{{ url() }}/external/js_admin/jquery.min.js"></script>  
        <script src="{{ url() }}/external/js_admin/unicorn.login.js"></script> 
        <script src="{{ url() }}/external/js_admin/jquery.validate.js"></script>
        <script src="{{ url() }}/external/js/function.js"></script> 
        <script type="application/javascript" language="javascript">
		</script>
	@include('template/script_multilanguage')	
    </head>
<body>
<form style="float: right;padding:5px;margin:0px;" class="form-horizontal" accept-charset="utf-8" method="POST" id="frmchangeLanguage" name="frmchangeLanguage" action="{{ url() }}/language/chooser">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<select name="locale" onchange="changeLanguage()">
    	<option	value="en">English</option>
    	<option	value="hi" {{ Lang::locale()==='hi' ? 'selected' :'' }}>Hindi</option>
    	</select>
    	
</form>
<div id="logo"></div>
<div id="loginbox">
          <?php echo Session::get('message') ?>
          @if (count($errors) > 0)
		  	<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		 @endif
            <form class="form-horizontal" role="form" method="POST" id="frmChangePassword" name="frmChangePassword" action="{{ url() }}/admin/changeforgotpassword">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="hdnUserId" id="hdnUserId" value="<?php echo $user_id; ?>">
				<p><?php echo trans('routes.forgotpass'); ?></p>
			        <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.newpassword'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                        <input type="password" maxlength="32" name="txtNewPassword" id="txtNewPassword">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo trans('routes.confpassword'); ?><font color="#FF0000"> *</font></label>
                      <div class="controls">
                        <input type="password" maxlength="32" name="txtConfirmPassword" id="txtConfirmPassword">
                      </div>
                    </div>
                <div class="form-actions">
                  <div class="pull-right">
                    <button type="submit" class="btn btn-inverse"><?php echo trans('routes.submit'); ?></button>
                  </div>
                </div>
           </form>
           </div>
</body>
</html>
<script src="{{ url() }}/external/js_admin/jquery.validate.js"></script> 
<script>
jQuery.validator.addMethod("notEqual", function(value, element, param) {
	 return this.optional(element) || value != $(param).val();
	});
$(function(){
	$("#frmChangePassword").validate({
		rules:
		{
			txtNewPassword:{
				required:true,
				notEqual: "#txtCurrentPassword",
				minlength: 6
			},
			txtConfirmPassword:{
				required:true,
				equalTo: "#txtNewPassword",
				minlength: 6
			}
		},
		messages:
		{
			txtNewPassword:{
				required:'',
				notEqual: js_password_compar,
				minlength : js_password_limit 
			},
			txtConfirmPassword:{
				required:'',
				equalTo: js_newpassword_compar,
				minlength: js_password_limit
			}
		},
	});
});
$(document).ready(function() {
    $('form:first *:input[type!=hidden]:first').focus();
});
</script>
</body>
</html>