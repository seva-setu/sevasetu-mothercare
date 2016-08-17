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
					->join(DB::Raw('(select r.* from (select * from mct_callchampion_report order by report_id desc) as r 
    								 group by r.fk_due_id) as mct_callchampion_report', 'mct_callchampion_report'), function ($join) {
        								$join->on('mct_due_list.due_id', '=', 'mct_callchampion_report.fk_due_id');
   									 }, null, null, 'left')
					->join($join_table_name2, $join_table_name2.'.i_action_id', '=', $this->table.'.fk_action_id')
					->join($join_table_name3, $join_table_name3.'.b_id','=', $this->table.'.fk_b_id')
					->select(
								$this->table.'.due_id',
								$this->table.'.fk_b_id as b_id',
								$this->table.'.dt_intervention_date as action_date',
								$join_table_name1.'.dt_modify_date as last_call_date',
								$join_table_name1.'.e_call_status as status',
								$join_table_name2.'.i_action_id as action_id',
								$join_table_name2.'.i_reference_week as ref_week',
								$join_table_name3.'.v_name as name',
								$join_table_name3.'.v_village_name as village_name',
								$join_table_name3.'.v_phone_number as phone_number',
								$join_table_name3.'.i_age as age'
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
		
		$string_query = $this->table.'.dt_intervention_date'." >= SYSDATE()";
		$select_has_called_not  = $select_has_called_not->where(function ($query) use($join_table_name1, $string_query){
																	$query->where($join_table_name1.'.e_call_status','=','Not called')
																		->whereRaw(DB::raw($string_query));
																	}
																);
		
		
		
		$string_query = $this->table.'.dt_intervention_date'." < SYSDATE()";
		$select_has_called_pending = $select_has_called_pending
									->where(function ($query) use($join_table_name1, $string_query){
											$query->wherein($join_table_name1.'.e_call_status',array('Not received', 'Not reachable', 'Incorrect number'))
													->orWhere(function ($query1) use($join_table_name1, $string_query){
																$query1->where($join_table_name1.'.e_call_status','=','Not called')
																->whereRaw(DB::raw($string_query));
															}
													);
										}
									);
		
		
		$select_has_called_thisweek = $select_has_called_thisweek->whereRaw(DB::raw($this->table.'.dt_intervention_date'." BETWEEN SYSDATE() + INTERVAL -7 DAY AND SYSDATE() + INTERVAL 7 DAY"))->whereNotIn($join_table_name1.'.e_call_status',array('Received','Incorrect number'));
		
		$select_has_called_thismonth = $select_has_called_thismonth->whereRaw(DB::raw($this->table.'.dt_intervention_date'." BETWEEN SYSDATE() + INTERVAL -30 DAY AND SYSDATE() + INTERVAL 30 DAY"))->whereNotIn($join_table_name1.'.e_call_status',array('Received', 'Incorrect number'));
		
		
		$selected['due_list_scheduled'] 		= $select_has_called_not->simplepaginate(5,['*'],'one');
		
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
	
	public function get_reminder_list(){
		$join_table_name1 = 'mct_call_champions';
		$join_table_name2 = 'mct_user';
		$join_table_name3 = 'mct_beneficiary';
		$join_table_name4 = 'mct_callchampion_report';
		$join_table_name5 = 'mct_checklist_master';
		
		// $select1 = DB::table($this->table)
						// ->update(array('reminder_status'=>0));
		
		$select = DB::table($this->table)
					->join($join_table_name1, $join_table_name1.'.cc_id','=',$this->table.'.fk_cc_id')
					->join($join_table_name2, $join_table_name2.'.user_id', '=', $join_table_name1.'.fk_user_id')
					->join($join_table_name3, $join_table_name3.'.b_id','=', $this->table.'.fk_b_id')
					->join($join_table_name4, $join_table_name4.'.fk_due_id','=', $this->table.'.due_id')
					->join($join_table_name5, $join_table_name5.'.i_action_id','=', $this->table.'.fk_action_id')
					->select(
								$this->table.'.due_id',
								$this->table.'.fk_cc_id as cc_id',
								$this->table.'.fk_b_id as b_id',
								$this->table.'.dt_intervention_date as action_date',
								$join_table_name2.'.v_name as cc_name',
								$join_table_name2.'.v_email as cc_email',
								$join_table_name2.'.i_phone_number as cc_phonenumber',
								$join_table_name3.'.v_name as mother_name',
								$join_table_name3.'.v_village_name as mother_village',
								$join_table_name3.'.v_phone_number as mother_phonenumber',
								$join_table_name5.'.v_reference_descrip as agenda'
							)
					->where($join_table_name1.'.activation_status','=',2)
					->distinct();
					
					
		$select_midweek = clone $select;
		$select_endweek = clone $select;
		$select_postweek = clone $select;
		
		$select_beginweek = $select->where($this->table.'.reminder_status','=',0)
							->whereNotIn($join_table_name4.'.e_call_status',array('Received','Incorrect number'))
							->whereRaw(DB::raw($this->table.'.dt_intervention_date'." BETWEEN SYSDATE() + INTERVAL 6 DAY AND SYSDATE() + INTERVAL 7 DAY"))
							->get();
							
		$select_midweek = $select_midweek->where($this->table.'.reminder_status','=',1)
							->whereNotIn($join_table_name4.'.e_call_status',array('Received','Incorrect number'))
							->whereRaw(DB::raw($this->table.'.dt_intervention_date'." BETWEEN SYSDATE() + INTERVAL 3 DAY AND SYSDATE() + INTERVAL 4 DAY"))
							->get();
		
		$select_endweek = $select_endweek->where($this->table.'.reminder_status','=',2)
							->whereNotIn($join_table_name4.'.e_call_status',array('Received','Incorrect number'))
							->whereRaw(DB::raw($this->table.'.dt_intervention_date'." BETWEEN SYSDATE() + INTERVAL 1 DAY AND SYSDATE() + INTERVAL 0 DAY"))
							->get();
		
		$select_postweek = $select_postweek->where($this->table.'.reminder_status','=',3)
							->whereNotIn($join_table_name4.'.e_call_status',array('Received','Incorrect number'))
							->whereRaw(DB::raw($this->table.'.dt_intervention_date'." BETWEEN SYSDATE() + INTERVAL -2 DAY AND SYSDATE() + INTERVAL -3 DAY"))
							->get();
		
		$this->update_reminder_status($select_beginweek, 1);
		$this->update_reminder_status($select_midweek, 2);
		$this->update_reminder_status($select_endweek, 3);
		$this->update_reminder_status($select_postweek, 4);
		
		$call_details['beginweek'] = $select_beginweek;
		$call_details['midweek'] = $select_midweek;
		$call_details['endweek'] = $select_endweek;
		$call_details['postweek'] = $select_postweek;
		
		return $call_details;
		
	}
	
	public function update_reminder_status($due_id_obj, $status){
		if(empty($due_id_obj)){
			return;
		}
		
		$due_id_arr = [];
		foreach($due_id_obj as $val)
			$due_id_arr []= $val->due_id;
				
		DB::table($this->table)
			->wherein('due_id', $due_id_arr)
			->update(['reminder_status' => $status]);
	}
}
?>
