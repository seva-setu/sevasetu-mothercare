<?php 
$duedate=strtotime($result->dt_due_date) != 0?date('d/m/Y',strtotime($result->dt_due_date)):"";
$deliverydate=strtotime($result->dt_delivery_date) != 0?date('d/m/Y',strtotime($result->dt_delivery_date)):"";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
<div id="content">
  <div id="content-header">
    <h1><?php  echo trans('routes.assignben'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php  echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php  echo trans('routes.home'); ?></a><a href="<?php echo Config::get('app.url'); ?>admin/assignbeneficiary"><?php  echo trans('routes.assignben'); ?></a><a class="current"><?php  echo trans('routes.view'); ?></a></div>
  <div class="container-fluid"> 
  <span class="insertDelMultipleButton">
  </span>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5><?php  echo trans('routes.assignben'); ?></h5>
          </div>
          <div class="widget-content" >
         <table class="table table-bordered table-striped">
          <tbody>
           <tr>
              <td><?php  echo trans('routes.fieldworkername'); ?></td>
              <td><?php echo $result->field_worker_name; ?></td>
            </tr>
             <tr>
              <td><?php  echo trans('routes.fieldworkernumber'); ?></td>
              <td><?php echo $result->field_worker_number; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.name'); ?></td>
              <td><?php echo $result->v_name; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.husbandname'); ?></td>
              <td><?php echo $result->v_husband_name; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.phonenumber'); ?></td>
              <td><?php echo $result->v_phone_number; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.altphonenumber'); ?></td>
              <td><?php echo $result->v_alternate_phone_no; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.nopregnancies'); ?></td>
              <td><?php echo $result->i_number_pregnancies; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.duedate'); ?></td>
              <td><?php echo $duedate;?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.deliverydate'); ?></td>
              <td><?php echo $deliverydate;?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.awcvame'); ?></td>
              <td><?php echo $result->v_awc_name;?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.awcnumber'); ?></td>
              <td><?php echo $result->v_awc_number;?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.beforcall'); ?></td>
              <td><?php echo $result->v_befor_call; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.conversation'); ?></td>
              <td><?php echo nl2br($result->v_conversation); ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.callduration'); ?></td>
              <td><?php echo $result->i_call_duration; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.emergancynote'); ?></td>
              <td><?php echo nl2br($result->t_emergency_note); ?></td>
            </tr>
            <tr >
            <td colspan="2">
            <a  onclick="goBack();" href="javascript:void(0)" class="btn btn-primary"><?php  echo trans('routes.back'); ?></a></td>
            </tr>
          </tbody>
        </table>
          </div>
        </div>
      </div>
    </div>
    @include('template/admin_footer')
  </div>
</div>
@include('template/admin_jsscript') 
</body>
</html>
<script>
function goBack() {
    window.history.back();
}
</script>