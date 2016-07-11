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
	
	public function get_number_of_calls_done($user_id){
	
	}
	
	public function get_assigned_beneficiaries($user_id){
		
	}
	 
}
