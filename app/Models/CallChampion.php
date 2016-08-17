<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Hash;
use DB;

class CallChampion extends Eloquent {
	public $timestamps = false;
	protected $table = 'mct_call_champions';
	protected $primaryKey = 'cc_id';
	
	/*
	 * Check user credentials from Database
	 */
	 
	public function add_champion($fk_id){
		$this->fk_user_id = $fk_id;
		$result = $this->save();
		if($result)
			return $this->getKey();
		else
			return false;
	}
	
	public function get_active_call_champions(){
		// needs to be joined with active records
		$select_cc = DB::table($this->table)
					->select('cc_id')
					->where('activation_status','=', 2)
					->get();
		$list_cc = array();
		foreach($select_cc as $cc)
			$list_cc []= $cc->cc_id;
		return $list_cc;	
	}
	
	public function get_dashboard_data($cc_id){
		$number_mothers_assigned = $this->get_number_mothers_assigned($cc_id);
		$number_calls_scheduled = $this->get_number_calls_assigned($cc_id);
		
		//$next_scheduled_call = $this->get_next_scheduled_call($cc_id);
		//$assigned_beneficiaries = $this->get_assigned_beneficiaries($cc_id);
		
		$dashboard_data['number_of_calls'] = $number_calls_scheduled;
		$dashboard_data['number_mothers_assigned'] = $number_mothers_assigned;
		
		return $dashboard_data;
	}
	
	public function get_number_calls_assigned($cc_id){
		$join_table_name = 'mct_callchampion_report';
		$base_table_name = 'mct_due_list';
		$select = DB::table($base_table_name)
					->join($join_table_name,$join_table_name.'.fk_due_id','=',$base_table_name.'.due_id')
					->select($join_table_name.'.fk_due_id')
					->where($base_table_name.'.fk_cc_id','=',$cc_id)
					->get();
		
		return count($select);
	}
	
	public function get_number_mothers_assigned($cc_id){
		$base_table_name = 'mct_due_list';
		$select = DB::table($base_table_name)
					->select('fk_b_id')
					->distinct()
					->where('fk_cc_id','=',$cc_id)
					->get();
					
		return count($select);
	}
	
	public function get_next_scheduled_call($cc_id){
		$join_table_name = 'mct_callchampion_report';
		$base_table_name = 'mct_due_list';
		$select = DB::table($base_table_name)
					->join($join_table_name, $join_table_name.'.fk_due_id','=',$base_table_name.'.due_id')
					->select(DB::raw('min(dt_intervention_date) as next_date'))
					->where($join_table_name.'.has_called','=',0)
					->where($base_table_name.'.fk_cc_id','=',$cc_id)
					->get();
					
		if(isset($select[0]))
			return $select[0]->next_date;
		else
			die("pop");
	} 
	
	public function update_cc_report($dueid, $call_stats, $general_note, $action_items){
		$table_name = 'mct_callchampion_report';
		$current_date = date("Y/m/d");
		$values_to_update = [
		'fk_due_id' => $dueid,
		'e_call_status' =>$call_stats,
		'dt_modify_date' => $current_date,
		't_conversation' => $general_note,
		't_action_items' => $action_items
		];
		
		$result = DB::table($table_name)->insert($values_to_update);
		// todo: add a check here: if integrity constraints or such some fails
		
		return true;
		
	}
	 
}
