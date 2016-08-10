<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
<p>Hi, Callchampion
 	<?php
		foreach($callchampion as $value){
			echo $value->v_name." (Contact: ".$value->i_phone_number.") ";
		}
		if($action == trans('routes.in'))
			echo trans('routes.incorrectstatus');
		elseif($action == trans('routes.action'))
			echo trans('routes.actionadded');
		elseif($action == trans('routes.incorrect'))
			echo trans('routes.incorrectduedate');
	?>
 </p>
<p><u>Details of Mother:</u></p>
<?php foreach($beneficiary as $value){ ?>
<ul>
	<li><b>{{ trans('routes.name') }}</b> : {{ $value->name }} </li>
	<li><b>{{ trans('routes.village') }}</b> : {{ $value->village_name}} </li>
	<li><b>{{ trans('routes.phonenumber') }}</b> : {{ $value->phone_number }} </li>
	<li><b>{{ trans('routes.fieldworkername') }}</b> : {{ $value->field_worker_name }} </li>
	<li><b>{{ trans('routes.fieldworkernumber') }}</b>: {{ $value->field_worker_number }} </li>
</ul>
<?php
	}
?>
<?php if($action == trans('routes.action')) {
	echo "Action item: ".$action_items;
}
elseif ($action == trans('routes.incorrect')) {
 	echo trans('routes.correctduedate').date("d M Y", strtotime($expected_date));
 } 
?>
<p><b>
<p>Thanks<br />
Mother Care Program<br/>
Seva Setu
</p>
</body>
</html>