<?php 
$languagedata= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
?>
<table class="table table-bordered table-striped table-hover with-check">
              <thead>
                <tr>
                  <th><?php echo trans('routes.name'); ?></th>
                  <th><?php echo trans('routes.husbandname'); ?></th>
				  <th><?php echo trans('routes.language'); ?></th>
				  <th><?php echo trans('routes.phonenumber'); ?></th>
				  <th><?php echo trans('routes.village'); ?></th>
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
                      <td><?php echo $value->v_name;?></td>
                      <td><?php echo $value->v_husband_name;?></td>
					  <td><?php echo ucwords(trim(trim($check),","));?></td>
					  <td><?php echo $value->v_phone_number;?></td>
					  <td><?php echo $value->v_address;?></td>
                    </tr>                	
                <?php } ?>
              </tbody>
              <?php }?>
              <tbody>
            </table>