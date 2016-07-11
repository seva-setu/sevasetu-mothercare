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
		$select = DB::select("select count(*) where cc_id = ".$cc_id);
		print_r($select);
		die();
	}
	
	public function get_assigned_beneficiaries($cc_id){
		
	}
	 
	public function get_next_scheduled_call($cc_id, $beneficiary_id){
		
	} 
}
