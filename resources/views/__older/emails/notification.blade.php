<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
<p>Dear <?php echo $userdata[0]->v_name; ?>,</p>
<!--  p>To prove you are human, please verify your account by clicking the following link:<br/>
<a href="<?php //echo Config::get('constant.SITE'); ?>ausers/activation/<?php //echo "1";?>"><?php //echo Config::get('constant.SITEURLADM'); ?>users/activation/<?php //echo "1"; ?></a>
</p-->
<p><?php echo $msg; ?></p>
<p>Thanks<br />
Mother Care Tool
</p>
</body>
</html>