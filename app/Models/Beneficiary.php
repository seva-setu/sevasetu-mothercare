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
							$this->table.'.v_phone_number as phone_number',
							$this->table.'.dt_due_date as due_date',
							$this->table.'.t_notes as mother_notes',
							
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
	
		
	public function update_notes($beneficiary_id, $notes){
		
	}

}
