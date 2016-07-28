@include('template/admin_title')
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
@include('template/script_multilanguage')
<div id="content">
  <div id="content-header">
    <h1><?php echo trans('routes.changepassword'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i><?php echo trans('routes.home'); ?></a><a class="current"><?php echo trans('routes.changepassword'); ?></a></div>
  <div class="container-fluid">
  <div id="errorInsertion"></div>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5><?php echo trans('routes.changepassword'); ?></h5>
          </div>
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
            <form class="form-horizontal" role="form" method="POST" id="frmChangePassword" name="frmChangePassword" action="<?php echo Config::get('app.url').'admin/dochangeuserpassword'; ?>">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="hdnUserId" id="hdnUserId" value="<?php echo $user_id; ?>">
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
                  <div class="splitFormSubmitButton">
                    <button type="submit" class="btn btn-primary"><?php echo trans('routes.submit'); ?></button>
                    <a href="<?php echo Config::get('app.url'); ?>admin<?php echo "/".$url;?>" onClick="btnCancel();" class="btn btn-danger"><?php echo trans('routes.cancel'); ?></a>
                  </div>
                </div>
           </form>
        </div>
      </div>
    </div>
     @include('template/admin_footer')
  </div>
</div>
@include('template/admin_jsscript')
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