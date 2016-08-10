<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>

<p>Dear <?php //echo $result[0]['v_name']; ?>,</p>
<p>This email was sent automatically by Mother Care Tool in response to your request to reset your password.</p>
<p>To reset your password and access your account, Click below to confirm your email address:</p>
<p> <a style="text-decoration: none;border: 1px solid transparent;color:#ffffff;padding: 6px 12px;border: 1px solid transparent;border-radius: 4px;cursor: pointer;background-color:#ff0000;" target="_blank" href="<?php //echo Config::get('app.url'); ?>admin/updatePassowrd/<?php //echo Hashids::encode($result[0]['bi_id']);?>">Reset My Password</a></p>
<p>If you have problems, please paste the below URL into your web browser.<br/>
<a href="<?php //echo Config::get('app.url'); ?>admin/updatePassowrd/<?php //echo Hashids::encode($result[0]['bi_id']);?>"><?php //echo Config::get('app.url'); ?>admin/updatePassowrd/<?php //echo Hashids::encode($result[0]['bi_id']);?></a></p>
<p>Thank you,<br />
Mother Care Tool
</p>
</body>
</html>