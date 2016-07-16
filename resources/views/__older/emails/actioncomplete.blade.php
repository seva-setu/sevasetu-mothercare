<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Action Item has been completed</title>
</head>
<body>

<p>Dear {{ $result[0]->v_name or '' }} ,</p>
<p>This email was sent automatically by Mother Care Tool.</p>
<p>The Action Item has been completed <?php $name=""; if(Session::get('user_logged')['v_name']) { $name = Session::get('user_logged')['v_name']; echo " by " . $name; } ?></p>
<br/>
<p>Action Description:</p><p>{{ $note or "No note to display." }}</p>

<p>Comments:</o>
<p>{{ $comment or "No comments to display." }}<p>
<br/>
<p>Thank you,<br />
Mother Care Tool
</p>
</body>
</html>