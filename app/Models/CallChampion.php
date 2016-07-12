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
					->get();
		$list_cc = array();
		foreach($select_cc as $cc)
			$list_cc []= $cc->cc_id;
		return $list_cc;	
	}
	
	public function get_dashboard_data($cc_id){
		$number_of_calls 	= $this->get_number_of_calls_done($cc_id);
		$next_scheduled_call = $this->get_next_scheduled_call($cc_id);
		$assigned_beneficiaries = $this->get_assigned_beneficiaries($cc_id);
		
		$dashboard_data['number_of_calls'] = $number_of_calls;
		$dashboard_data['next_scheduled_call'] = $next_scheduled_call;
		$dashboard_data['assigned_beneficiaries'] = $assigned_beneficiaries;
		
		return $dashboard_data;		
	}
	
	public function get_number_of_calls_done($cc_id){
		$join_table_name = 'mct_callchampion_report';
		$base_table_name = 'mct_due_list';
		$select = DB::table($base_table_name)
					->join($join_table_name,$join_table_name.'.fk_due_id','=',$base_table_name.'.due_id')
					->select($join_table_name.'.fk_due_id')
					->where($base_table_name.'.fk_cc_id','=',$cc_id)
					->where($join_table_name.'.has_called','>',0)
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
	
	public function get_assigned_beneficiaries($cc_id){
		//Right join needs to be done here; the ""distinct" way of doing things seem hacky.
		$join_table_name = 'mct_due_list';
		$join_table_name2 = 'mct_field_workers';
		$join_table_name3 = 'mct_user';
		$base_table_name = 'mct_beneficiary';
		
		$select = DB::table($base_table_name)
					->join($join_table_name,$join_table_name.'.fk_b_id','=',$base_table_name.'.b_id')
					->join($join_table_name2, $join_table_name2.'.f_id','=',$base_table_name.'.fk_f_id')
					->select(	$base_table_name.'.v_name as name', 
								$base_table_name.'.v_village_name as village_name',
								$base_table_name.'.v_phone_number as phone_number',
								$base_table_name.'.v_husband_name as husband_name',
								$base_table_name.'.dt_due_date as due_date',
								$base_table_name.'.fk_f_id as fieldworker_id'
							)
					->distinct()
					->where($join_table_name.'.fk_cc_id','=',$cc_id)
					->get();
		
		$field_worker_ids = array();
		foreach($select as $details){
			$field_worker_ids []= $details->fieldworker_id;
		}
		$select2 = DB::table($join_table_name2)
					->join($join_table_name3, $join_table_name3.'.user_id', '=', $join_table_name2.'.fk_user_id')
					->select(	$join_table_name2.'.f_id',
								$join_table_name3.'.v_name as field_worker_name', 
								$join_table_name3.'.i_phone_number as field_worker_phone_number'
							)
					->distinct()
					->wherein($join_table_name2.'.f_id',$field_worker_ids)
					->get();
		
		$village_details = array();
		foreach($select2 as $entry){
			$village_details[$entry->f_id] = array($entry->field_worker_name, $entry->field_worker_phone_number, $entry->f_id);
		}
		
		$list_beneficiaries = [];
		foreach($select as $entry){
			$entry->field_worker_name = $village_details[$entry->fieldworker_id][0];
			$entry->field_worker_number = $village_details[$entry->fieldworker_id][1];
			$entry->field_worker_id = $village_details[$entry->fieldworker_id][2];
			
		}
		return $list_beneficiaries;
	}
	 
}
