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
		$select = DB::table($this->table)
					->select('due_id')
					->where('fk_b_id','=',$beneficiary_id)
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
	
	public function get_due_list_callchamp($cc_id){
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
								$this->table.'.fk_b_id',
								$this->table.'.dt_intervention_date',
								$join_table_name1.'.has_called',
								$join_table_name1.'.e_call_status',
								$join_table_name2.'.i_action_id',
								$join_table_name2.'.i_reference_week',
								$join_table_name3.'.v_name',
								$join_table_name3.'.v_village_name'
							)
					->distinct()
					->orderBy($join_table_name1.'.has_called','asc')
					->orderBy($this->table.'.dt_intervention_date','asc')
					->where($this->table.'.fk_cc_id','=',$cc_id)
					->get();
		
		return $sample;
	}
}
?>