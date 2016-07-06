<?php 
$languagedata= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
?>
<table class="table table-bordered table-striped table-hover with-check">
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" onclick="checkedAll('frmList');" /></th>
                  <th><?php echo trans('routes.uniqueid'); ?></th>
                  <th><?php echo trans('routes.username'); ?></th>
				  <th><?php echo trans('routes.email'); ?></th>
				  <th><?php echo trans('routes.language'); ?></th>
				  <th><?php echo trans('routes.phonenumber'); ?></th>
				  <th><?php echo trans('routes.profession'); ?></th>
				  <th><?php echo trans('routes.action'); ?></th>
                </tr>
              </thead>
              <?php if(count($result) > 0) { ?>
              <tbody>
              	<?php foreach ($result as $value){
              		$check="";
              		foreach ($languagedata as $lang){
              			if($value->v_language!=""){
              				$lanarr=explode(",", $value->v_language);
              				if(in_array($lang->bi_id,$lanarr))
              					$check.=$lang->v_language.", ";
              			}
              		}
              	?>
			        <tr>
                      <td><input type="checkbox" name="chkCheckedBox[]" id="chkCheckedBox" value="<?php echo $value->bi_id; ?>_<?php echo $value->bi_user_login_id; ?>" /></td>
                        <td><a href="<?php echo Config::get('app.url').'admin/adminusrs/view/'.Hashids::encode($value->bi_id) ?>"><?php echo $value->v_unique_code;?></a></td>
                        <td><a href="<?php echo Config::get('app.url').'admin/adminusrs/view/'.Hashids::encode($value->bi_id) ?>"><?php echo $value->v_name;?></a></td>
                      	<td><?php echo $value->v_email;?></td>
						<td><?php echo ucwords(trim(trim($check),","));?></td>
						<td><?php echo $value->v_phone_number;?></td>
					  	<td><?php echo $value->v_profession;?></td>
					  	<td style="width: 230px" >
							<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/adminusrs/edit/'.Hashids::encode($value->bi_id) ?>"><i class="icon-edit icon-white"></i><?php echo trans('routes.edit'); ?></a>
							<!--  a class="btn btn-danger" onclick="return singleCheckDel();" href="<?php echo Config::get('app.url').'admin/adminusrs/delete/'.Hashids::encode($value->bi_id)."/".Hashids::encode($value->bi_user_login_id); ?>"><i class="icon-remove icon-white"></i><?php echo trans('routes.delete'); ?></a>-->					  	
                      		<?php if($value->e_status=="Active"){?>
                      		<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/adminusrs/delete/'.Hashids::encode($value->bi_id)."/".Hashids::encode($value->bi_user_login_id)."/".Hashids::encode(0); ?>"></i><?php echo trans('routes.inactive'); ?></a>					  	
                      		<?php }elseif($value->e_status=="Inactive"){?>
                      		<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/adminusrs/delete/'.Hashids::encode($value->bi_id)."/".Hashids::encode($value->bi_user_login_id)."/".Hashids::encode(1); ?>"></i><?php echo trans('routes.active'); ?></a>					  	
                      		<?php }?>
                      		</td>
                        </td>
                        
                    </tr>                	
                <?php } ?>
              </tbody>
              <?php }?>
              </table>
             <div class="pagination" style="float:right;clear:both;"><?php echo $result->render(); ?></div>