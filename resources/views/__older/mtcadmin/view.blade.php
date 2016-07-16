<?php 
$languagedata= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
$birthdata=strtotime($result->dt_birthdate) != 0?date('d/m/Y',strtotime($result->dt_birthdate)):"";
$check="";
foreach ($languagedata as $lang){
	if($result->v_language!=""){
		$lanarr=explode(",", $result->v_language);
		if(in_array($lang->bi_id,$lanarr))
			$check.=$lang->v_language.", ";
	}
}
?>
@include('template/admin_title')
@include('template/admin_cssscripta')
</head>
<body>
@include('template/admin_header')
@include('template/admin_sidebar')
<div id="content">
  <div id="content-header">
    <h1><?php echo trans('routes.adminuser'); ?></h1>
  </div>
  <div id="breadcrumb"> <a href="<?php echo Config::get('app.url'); ?>admin/dashboard" title="<?php echo trans('routes.homelabel'); ?>" class="tip-bottom"><i class="icon-home"></i> <?php echo trans('routes.home'); ?></a><a href="<?php echo Config::get('app.url'); ?>admin/adminusrs"><?php echo trans('routes.adminuser'); ?></a><a  class="current"><?php echo trans('routes.view'); ?></a></div>
  <div class="container-fluid"> 
  <span class="insertDelMultipleButton">
  </span>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5><?php echo trans('routes.view')." ".trans('routes.adminuser'); ?></h5>
          </div>
          <div class="widget-content ">
         <table class="table table-bordered table-striped">
          <tbody>
            <tr>
              <td align="center" style="font-weight: 600;" colspan="2"><?php echo trans('routes.adminuser')." ".trans('routes.detail'); ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.name'); ?></td>
              <td><?php echo $result->v_name; ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.phonenumber'); ?></td>
              <td><?php echo $result->v_phone_number; ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.language'); ?></td>
              <td><?php echo ucwords(trim(trim($check),","));?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.birthdate'); ?></td>
              <td><?php echo $birthdata; ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.profession'); ?></td>
              <td><?php echo $result->v_profession; ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.marital'); ?></td>
              <td><?php echo $result->e_marital_status; ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.gender'); ?></td>
              <td><?php echo $result->e_gender; ?></td>
            </tr>
            <tr>
              <td align="center" style="font-weight: 600;" colspan="2"><?php echo trans('routes.contactdetail'); ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.email'); ?></td>
              <td><?php echo $result->v_email; ?></td>
            </tr>
            <tr>
              <td><?php  echo trans('routes.village'); ?></td>
              <td><?php echo $result->v_address; ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.taluka'); ?></td>
              <td><?php echo $result->v_taluka; ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.city'); ?></td>
              <td><?php echo $result->v_district; ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.zipcode'); ?></td>
              <td><?php echo $result->v_pincode; ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.state'); ?></td>
              <td><?php echo $result->v_state; ?></td>
            </tr>
            <tr>
              <td><?php echo trans('routes.country'); ?></td>
              <td><?php echo $result->v_country; ?></td>
            </tr>
            <tr >
            <td colspan="2">
            <a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/adminusrs/edit/'.Hashids::encode($result->bi_id) ?>"><i class="icon-edit icon-white"></i><?php echo trans('routes.edit'); ?></a>
			<!--  a class="btn btn-danger" onclick="return singleCheckDel();" href="<?php echo Config::get('app.url').'admin/adminusrs/delete/'.Hashids::encode($result->bi_id)."/".Hashids::encode($result->bi_user_login_id); ?>"><i class="icon-remove icon-white"></i><?php echo trans('routes.delete'); ?></a>-->					  	
            <?php if($result->e_status=="Active"){?>
            	<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/adminusrs/delete/'.Hashids::encode($result->bi_id)."/".Hashids::encode($result->bi_user_login_id)."/".Hashids::encode(0); ?>"></i><?php echo trans('routes.inactive'); ?></a>					  	
            <?php }elseif($result->e_status=="Inactive"){?>
            	<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/adminusrs/delete/'.Hashids::encode($result->bi_id)."/".Hashids::encode($result->bi_user_login_id)."/".Hashids::encode(1); ?>"></i><?php echo trans('routes.active'); ?></a>					  	
            <?php }?>
            <a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/changeuserpassword/'.Hashids::encode($result->bi_user_login_id); ?>"><i class="icon-white icon-edit"></i><?php echo trans('routes.changepassword'); ?></a>					  	
            <a href="<?php echo Config::get('app.url');?>admin/adminusrs" class="btn btn-primary"><?php echo trans('routes.back'); ?></a>
            </td>
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
