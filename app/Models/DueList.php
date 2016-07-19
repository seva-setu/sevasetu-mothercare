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
	 
	public function get_beneficiary_ids_list($cc_id){
		$beneficiary_objects = DB::table($this->table)
								->select('fk_b_id')
								->distinct()
								->where('fk_cc_id','=',$cc_id)
								->get();
		$beneficiary_list = [];
		foreach($beneficiary_objects as $obj)
			$beneficiary_list []= $obj->fk_b_id;
		
		return $beneficiary_list;
	}
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
		$val = DB::select("select last_insert_id() as last_insert_id");
		$last_insert_id = $val[0]->last_insert_id;
		
		for($i=0; $i<count($due_list); $i++)
			$due_list_ids []= $last_insert_id+$i;
		
		return $due_list_ids;
	}
	
	public function get_previous_call_champions($beneficiary_id){
		$prev_call_champs = DB::table($this->table)
							->select(array(
											'fk_cc_id as cc_id',
											DB::raw('count(*) as count_cc')
										)
									)
							->groupby('cc_id')
							->orderby('count_cc','desc')
							->where('fk_b_id','=',$beneficiary_id)
							->wherenotnull('fk_cc_id')
							->get();
		return $prev_call_champs;
	}
	
	public function get_existing_assignments($prev_champ_ids){
	// This query has to ideally change and be a nested query.
		$select_bid_ccid = DB::table($this->table)
									->select(
											array(
												'fk_cc_id as cc_id',
												'fk_b_id as b_id'
												)
											)
									->distinct()
									->wherein('fk_cc_id',$prev_champ_ids)
									->get();
		
		$existing = array();
		foreach($select_bid_ccid as $cc_id){
				if(isset($existing[$cc_id->cc_id])){
					$existing[$cc_id->cc_id] = $existing[$cc_id->cc_id]+1;
				}
				else
					$existing[$cc_id->cc_id] = 1;
		}
		return $existing;
	}
	
	public function get_beneficiary_duelist_id($beneficiary_id){
		$selected = DB::table($this->table)
							->select('due_list_id')		
							->where('fk_b_id','=',$beneficiary_id)
							->get();
		$due_list_ids = array();
		foreach($selected as $selected_i)
			$due_list_id []= $selected_i->due_list_id;
		
		return $due_list_ids;
	}
		
	public function get_duelist($beneficiary_id){
		if(!is_array($beneficiary_id))
			$beneficiary_ids = array($beneficiary_id);
		else
			$beneficiary_ids = $beneficiary_id;
		
		$select = DB::table($this->table)
					->select('due_id')
					->wherein('fk_b_id','=',$beneficiary_ids)
					->get();
		$duelist_arr = array();
		foreach($select as $due)
			$duelist_arr []= $due->due_id;
		return $duelist_arr;
	}
	
	public function assign_call_champion_duelist_id($call_champ_id, $due_list_ids_arr){
		$select_bid_ccid = DB::table($this->table)
									->wherein('due_id',$due_list_ids_arr)
									->update(['fk_cc_id'=>$call_champ_id]);
	}
	
	public function get_due_list_callchamp($cc_id, $beneficiary_id = -1){
		//first get out the due list details
		$join_table_name1 = 'mct_callchampion_report';
		$join_table_name2 = 'mct_checklist_master';
		$join_table_name3 = 'mct_beneficiary';
		
		$select = DB::table($this->table)
					->join($join_table_name1, $join_table_name1.'.fk_due_id','=',$this->table.'.due_id')
					->join($join_table_name2, $join_table_name2.'.i_action_id', '=', $this->table.'.fk_action_id')
					->join($join_table_name3, $join_table_name3.'.b_id','=', $this->table.'.fk_b_id')
					->select(
								$this->table.'.due_id',
								$this->table.'.fk_b_id as b_id',
								$this->table.'.dt_intervention_date as action_date',
								$join_table_name1.'.e_call_status as status',
								$join_table_name2.'.i_action_id as action_id',
								$join_table_name2.'.i_reference_week as ref_week',
								$join_table_name3.'.v_name as name',
								$join_table_name3.'.v_village_name as village_name',
								$join_table_name3.'.v_phone_number as phone_number'
								
							)
					->distinct()
					->orderBy($this->table.'.dt_intervention_date','asc')
					->where($this->table.'.fk_cc_id','=',$cc_id);
		if($beneficiary_id > -1)
			$select = $select->where($this->table.'.fk_b_id','=',$beneficiary_id);
		
		
		$select_has_called_not  = clone $select;	
		$select_has_called_pending = clone $select;
		$select_has_called_thisweek = clone $select;
		$select_has_called_thismonth = clone $select;
		
		$select_has_called 		= $select->where($join_table_name1.'.e_call_status','=','Received');
		
		$select_has_called_not  = $select_has_called_not->where($join_table_name1.'.e_call_status','=','Not called');
		
		$select_has_called_pending = $select_has_called_pending->where($join_table_name1.'.e_call_status',array('Not received', 'Not reachable', 'Incorrect number'));
		
		$select_has_called_thisweek = $select_has_called_thisweek->whereRaw(DB::raw($this->table.'.dt_intervention_date'." BETWEEN SYSDATE() + INTERVAL -10 DAY AND SYSDATE() + INTERVAL 10 DAY"))->whereNotIn($join_table_name1.'.e_call_status',array('Received'));
		
		$select_has_called_thismonth = $select_has_called_thismonth->whereRaw(DB::raw($this->table.'.dt_intervention_date'." BETWEEN SYSDATE() + INTERVAL -30 DAY AND SYSDATE() + INTERVAL 30 DAY"))->whereNotIn($join_table_name1.'.e_call_status',array('Received'));;
		
		
		$selected['due_list_scheduled'] 		= $select_has_called_not->simplepaginate(15,['*'],'one');
		
		$selected['due_list_completed'] 		= $select_has_called->simplepaginate(5,['*'],'two');
		
		$selected['due_list_pending'] 			= $select_has_called_pending->simplepaginate(5,['*'],'three');
		
		$selected['due_list_thisweek'] 			= $select_has_called_thisweek->simplepaginate(5,['*'],'four');
		
		$selected['due_list_thismonth']			= $select_has_called_thismonth->simplepaginate(5,['*'],'five');
		
		return $selected;
	}
		
	public function get_checklist_items($action_item_id){
		$table_name = 'mct_checklist_master';
		$select = DB::table($table_name)
			->select('v_reference_descrip as reference_descrip',
					 'v_action_descrip as action_descrip'
					)
			->where('i_action_id','=',$action_item_id)
			->get();
		
		return $select;
	}
}
?>
