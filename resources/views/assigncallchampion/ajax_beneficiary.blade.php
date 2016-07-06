<?php 
$languagedata= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
?>
<table class="table table-bordered table-striped table-hover with-check">
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" onclick="checkedAll('frmList');" /></th>
                  <th><?php echo trans('routes.name'); ?></th>
                  <th><?php echo trans('routes.husbandname'); ?></th>
				  <th><?php echo trans('routes.language'); ?></th>
				  <th><?php echo trans('routes.phonenumber'); ?></th>
				  <th><?php echo trans('routes.village'); ?></th>
				  <th><?php echo trans('routes.week'); ?></th>
                </tr>
              </thead>
              <tr id="loading-image" style="display: none;">
                    <td colspan="10"><center>
				<img src="<?php echo Config::get('app.url');?>external/images/loader.gif " >
			</td>
			</tr>
              <?php if(count($result) > 0) { ?>
              <tbody>
              	<?php foreach ($result as $value){
              		//$value->firstpoint."-".$value->secondpoint;
              		$noofday=intval($value->firstpoint)+intval($value->secondpoint);
              		$avgday=$noofday/2;
              		$week=round($avgday/7);
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
                      <td><input type="checkbox" name="chkCheckedBox[]" id="chkCheckedBox" value="<?php echo $value->bi_id; ?>_<?php echo $week; ?>" /></td>
                      <td><?php echo $value->v_name;?></td>
                      <td><?php echo $value->v_husband_name;?></td>
					  <td><?php echo ucwords(trim(trim($check),","));?></td>
					  <td><?php echo $value->v_phone_number;?></td>
					  <td><?php echo $value->v_address;?></td>
                      <td><?php echo $week;?> Week</td>
                    </tr>                	
                <?php } ?>
              </tbody>
              <?php } else { ?>
              <tbody>
              	  <tr id="flush-td">
                    <td colspan="10"><center><em><?php echo trans('routes.norecord'); ?></em></center></td>
                  </tr>
              </tbody>
              <?php } ?>
            </table>