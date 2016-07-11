<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Session;
use Hash;
use DB;

class User extends Eloquent {
	public $timestamps = false;
	protected $table = 'mct_user';
	protected $primaryKey = 'user_id';
	
	/*
	 * Check user credentials from Database
	 */
	 
	public function mod_user($inputData, $role = 2){
		
		if(isset($inputData['v_name']))
			$this->v_name 			=	$inputData['v_name'];
		
		if(isset($inputData['v_email']))
			$this->v_email	=	$inputData['v_email'];
		
		
		if(isset($inputData['i_phonenum']))
			$this->i_phonenum	=	$inputData['i_phonenum'];
		
		if(isset($inputData['password']))
			$this->v_password	=	$inputData['password'];
		
		if(isset($inputData['ti_is_verified']))
			$this->ti_is_verified	=	$inputData['ti_is_verified'];
		
		if(isset($inputData['v_language']))
			$this->v_language		=	$inputData['v_language'];
						
		if(isset($inputData['e_gender']))
			$this->e_gender	=	$inputData['e_gender'];
		
		if(isset($inputData['e_status']))
			$this->e_status	=	$inputData['e_status'];
		
		if(isset($inputData['dt_create_date']))
			$this->dt_create_date	=	$inputData['dt_create_date'];
		
		if(isset($inputData['dt_last_login']))
			$this->dt_last_login	=	$inputData['dt_last_login'];
		
		$this->v_role 		= $role;
		
		$this->v_ip			=	$_SERVER['REMOTE_ADDR'];
		
				
		$result = $this->save();
		if($result)
			return $this->getKey();
		else
			return false;
		
	}
	 
	public function log_in_user($inputData){
		Session::put('user_logged',$inputData);
		return true;		
	} 
	public function validate_login($inputData){
		$userdata = User::where('v_email', '=', $inputData['email'])->where('e_status','Active')->first();
		
		if(!empty($userdata)){
			$checklogin=Hash::check($inputData['password'], $userdata->v_password);
			if($checklogin){
				$v_role = $userdata->v_role;
				if($v_role == 2){
					//CALL CHAMPION
					$join_table_name = 'mct_call_champions';
					$select = DB::table($this->table)
					->join($join_table_name,$join_table_name.'.fk_user_id','=',$this->table.'.user_id')
					->select($join_table_name.'.cc_id')
					->where($this->table.'.user_id','=',$userdata->user_id)
					->get();
					
					$userdata->id = $select[0]->cc_id;
				}
				
				$userdet=array(
						'role_id' => $userdata->id,
						'v_name' => $userdata->v_name,
						'v_user_name' => $userdata->v_email,
						'v_role' => $userdata->v_role,
						'user_id'=> $userdata->user_id
				);
				return $this->log_in_user($userdet);
			}else{
				return false;
			}
		}else {
			return false;
		}
	}

}
