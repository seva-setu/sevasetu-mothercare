<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>

<p>Hi <?php echo $v_name; ?>,</p>
<p>Welcome to Seva Setu&#39;s Mother Care program. We&#39;re so happy to have you onboard!</p>
<!--  p>To prove you are human, please verify your account by clicking the following link:<br/>
<a href="<?php //echo Config::get('constant.SITE'); ?>ausers/activation/<?php //echo "1";?>"><?php //echo Config::get('constant.SITEURLADM'); ?>users/activation/<?php //echo "1"; ?></a>
</p-->
<p>Your credentials on our <a href="http://sevasetu.org/mother_care"> web app</a> are:</p>
<p><b>EMail</b>: <?php echo $v_email; ?><br/>
<b>Password</b> : <?php echo $v_password_unenc; ?></p>

<p><p>For any queries, please free to get in touch with us. Write to us on mothercare@sevasetu.org. We&#39;ll make sure to get back in touch with you!</p></p>
<p>Thanks<br />
Program coordinators<br/>
Mother Care Program<br/>
Seva Setu
</p>
</body>
</html>