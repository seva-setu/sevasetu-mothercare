<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;
use Session;
use Eloquent;


class Beneficiary extends Eloquent {
	public $timestamps = false;
	protected $table = 'mct_beneficiary';
	protected $primaryKey = 'b_id';
	

	protected $date=['dt_upload_date'];
	/*
	 * Insert Beneficiary Report in Database.
	 */
	
	
	public function get_beneficiary_details($beneficiary_id){
		$join_table1 = 'mct_field_workers';
		$join_table2 = 'mct_user';
		$select = DB::table($this->table)
				->join($join_table1, $join_table1.'.f_id','=', $this->table.'.fk_f_id')
				->join($join_table2, $join_table1.'.fk_user_id','=', $join_table2.'.user_id')
				->select(
							$this->table.'.v_name as name',
							$this->table.'.v_village_name as village_name',
							$this->table.'.v_husband_name as husband_name',
							$this->table.'.i_age as age',
							$this->table.'.v_phone_number as phone_number',
							$this->table.'.dt_due_date as due_date',
							$this->table.'.t_notes as mother_notes',
							$this->table.'.b_id',
							$join_table1.'.f_id as fieldworker_id',
							$join_table2.'.v_name as field_worker_name',
							$join_table2.'.i_phone_number as field_worker_number'
						);
		if(is_array($beneficiary_id)){
				$select = $select->wherein($this->table.'.b_id',$beneficiary_id);
		}
		else
				$select = $select->where($this->table.'.b_id','=',$beneficiary_id);
		$select = $select->get();
				
		return $select;
	}
	
		
	public function get_previous_notes($beneficiary_id){
		$table_name = 'mct_callchampion_report';
		$join_table = 'mct_due_list';
		$select  	= DB::table($table_name)
						->join($join_table, $join_table.'.due_id', '=', $table_name.'.fk_due_id')
						->select(
							$table_name.'.fk_due_id as call_id',
							$table_name.'.e_call_status as status',
							$table_name.'.dt_modify_date as modify_date',
							$table_name.'.t_conversation as general_notes',
							$table_name.'.t_action_items as action_items'
						)
						->where($join_table.'.fk_b_id', '=' , $beneficiary_id)
						->orderBy($table_name.'.dt_modify_date','desc')
						->simplepaginate(15);
		return $select;
	}
	
	public function get_current_notes($due_id){
		$table_name = 'mct_callchampion_report';
		$join_table = 'mct_due_list';
		$select  	= DB::table($table_name)
						->join($join_table, $join_table.'.due_id', '=', $table_name.'.fk_due_id')
						->select(
							$table_name.'.t_conversation as conversation_notes',
							$table_name.'.t_action_items as action_items',
							$table_name.'.e_call_status as status'
						)
						->where($join_table.'.due_id', '=' , $due_id)
						->orderBy($table_name.'.report_id','desc')
						->get();
		//ACTION ITEM: Needs proper exception handling
		return $select[0];
	}

}
