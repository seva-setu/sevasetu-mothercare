<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Hash;
use DB;

class CallChampion extends Eloquent {
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
	 
	public function validate_login1($inputData){
		$userdata = Users::where('v_email', '=', $inputData['email'])->where('v_status','Active')->first();
		if(!empty($userdata)){
			$checklogin=Hash::check($inputData['password'], $userdata->v_password);
			if($checklogin){
				
				$v_role = $userdata->v_role;
				$user_table ="";
				//dd(Session::get(null));exit;
				$user_id=0;
				if($v_role!=0){
					switch($v_role){
						case 1: $user_table = "mct_admin";
						break;
						case 2: $user_table = "mct_call_champions";
						break;
						case 3: $user_table = "mct_field_workers";
						break;
					}
					
					$res = DB::table('mct_user_login')
					->join($user_table,'mct_user_login.bi_id','=',$user_table.'.bi_user_login_id')
					->select($user_table.'.bi_id')
					->where('mct_user_login.bi_id','=',$userdata->bi_id)
					->get();
					$user_id=$res[0]->bi_id;
				}
				
				$userdet=array(
						'b_id' => $userdata->bi_id,
						'v_name' => $userdata->v_name,
						'v_user_name' => $userdata->v_email,
						'v_role' => $userdata->v_role,
						'user_id'=>$user_id 
				);
				Session::put('user_logged',$userdet);
				return true;
			}else{
				return false;
			}
		}else {
			return false;
		}
	}

}
