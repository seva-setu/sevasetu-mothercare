<?php 
$userinfo=Session::get('user_logged');
$usertype=$userinfo['v_role'];
$userid=$userinfo['b_id'];
?>
<table class="table table-bordered table-striped table-hover with-check">
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" onclick="checkedAll('frmList');" /></th>
                  <th><?php  echo trans('routes.uniqueid'); ?></th>
                  <th><?php echo trans('routes.name'); ?></th>
                  <th><?php echo trans('routes.husbandname'); ?></th>
				  <th><?php  echo trans('routes.location'); ?></th>
				  <th><?php  echo trans('routes.phonenumber'); ?></th>
				  <th><?php  echo trans('routes.action'); ?></th>
                </tr>
              </thead>
              <?php if(count($result) > 0) { ?>
              <tbody>
              	<?php foreach ($result as $value){?>
			        <tr>
                      <td><input type="checkbox" name="chkCheckedBox[]" id="chkCheckedBox" value="<?php echo $value->bi_id; ?>" /></td>
                      <td><a href="<?php echo Config::get('app.url').'admin/beneficiary/view/'.Hashids::encode($value->bi_id) ?>"><?php echo $value->v_unique_code;?></a></td>
                      <td><a href="<?php echo Config::get('app.url').'admin/beneficiary/view/'.Hashids::encode($value->bi_id) ?>"><?php echo $value->v_name;?></a></td>
                      <td><?php echo $value->v_husband_name;?></td>
					  <td>@if(isset($value['v_village']) && $value['v_village']!=""){{{  $value['v_village'] or '' }}}, {{{ $value['v_taluka'] or '' }}}, {{{  $value['v_district'] or '' }}}@endif</td>
					  <td><?php echo $value->v_phone_number;?></td>
					  <td style="width: 230px">
					 <?php if($usertype!=2 && $usertype!=3){?>
					  <?php if($value->bi_calls_champion_id==""){ 
					  	?>
					  	<a class="btn btn-info" href="javascript:void(0);" id="CallChamption-<?php echo $value->bi_id; ?>" onclick="showCallChamption('<?php echo $value->i_address_id; ?>','<?php echo $value->bi_id; ?>','<?php echo $value->v_name; ?>')" >Assign Callchampion</a>
					  <?php }else{
					  	$callchampid = DB::select('select v_name from mct_call_champions where e_status!="Deleted" and bi_id='.$value->bi_calls_champion_id.'');
					  	if(count($callchampid)>0){
					  	$callchamname=$callchampid[0]->v_name;
					  	?>
					  	<a href="javascript:void(0);" id="CallChamption-<?php echo $value->bi_id; ?>" onclick="showCallChamption('<?php echo $value->i_address_id; ?>','<?php echo $value->bi_id; ?>','<?php echo $value->v_name; ?>')" ><?php echo $callchamname; ?></a>
					  <?php }else{?>
					  	<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/edit/'.Hashids::encode($value->bi_id) ?>"><i class="icon-edit icon-white"></i><?php echo trans('routes.edit'); ?></a>
					  <?php }}
        				}else {?>
        					<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/edit/'.Hashids::encode($value->bi_id) ?>"><i class="icon-edit icon-white"></i><?php echo trans('routes.edit'); ?></a>
							<!-- a class="btn btn-danger" onclick="return singleCheckDel();" href="<?php //echo Config::get('app.url').'admin/callchampions/delete/'.Hashids::encode($value->bi_id)."/".Hashids::encode($value->bi_user_login_id); ?>"><i class="icon-remove icon-white"></i><?php echo trans('routes.delete'); ?></a> -->
							<?php 
							if($value->e_status=="Active"){?>
                      			<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/delete/'.Hashids::encode($value->bi_id)."/".Hashids::encode(0); ?>"></i><?php echo trans('routes.inactive'); ?></a>					  	
                      		<?php }elseif($value->e_status=="Inactive"){?>
                      			<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/beneficiary/delete/'.Hashids::encode($value->bi_id)."/".Hashids::encode(1); ?>"></i><?php echo trans('routes.active'); ?></a>					  	
                      		<?php }?>
        				<?php }?>
					  </td>
                    </tr>                	
                <?php } ?>
              </tbody>
              <?php } ?>
            </table>
            <div class="pagination" style="float:right;clear:both;"><?php echo $result->render(); ?></div>