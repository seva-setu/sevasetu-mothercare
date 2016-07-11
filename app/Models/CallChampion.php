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
		$number_of_calls = $this->get_number_of_calls_done($cc_id);
		$assigned_beneficiaries = $this->get_assigned_beneficiaries($cc_id);
		$next_scheduled_call = $this->get_next_scheduled_call($cc_id);
	}
	
	public function get_number_of_calls_done($cc_id){
		echo($cc_id);
		die();
		$join_table_name = 'mct_callchampion_report';
		$select = DB::table('mct_due_list')
					->join($join_table_name,$join_table_name.'.due_id','=',$this->table.'.fk_due_id')
					->select($join_table_name.'.due_id')
					->where($this->table.'.fk_cc_id','=',$cc_id)
					->get();
		
		print_r($select);
		die();
		
		$data['result']=DB::table('mct_beneficiary')
		->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
		->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_village_pincode', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		->where('mct_beneficiary.bi_id',$id)
		->get();
	}
	
	public function get_assigned_beneficiaries($cc_id){
		
	}
	 
	public function get_next_scheduled_call($cc_id, $beneficiary_id){
		
	} 
}
