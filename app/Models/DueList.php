<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;
use Session;
use Eloquent;


class DueList extends Eloquent {
	public $timestamps = false;
	protected $table = 'mct_due_list';
	protected $primaryKey = 'due_id';
	
	/*
	 * Insert Beneficiary Report in Database.
	 */
	 
	 
	public function update_due_list($beneficiary_id, $due_list){
		$due_list_arr = array();
		for($i=0; $i<count($due_list); $i++){
			$due_list_i = array();
			$due_list_i['dt_intervention_date'] = $due_list[$i];
			$due_list_i['fk_b_id'] 		= $beneficiary_id;
			$due_list_i['fk_action_id']	= $i;
			$due_list_arr []= $due_list_i;
		}
		$this->insert($due_list_arr);
	}
}
?>