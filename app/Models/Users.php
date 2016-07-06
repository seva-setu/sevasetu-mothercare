<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Session;
use Hash;
use DB;

class Users extends Eloquent {
	public $timestamps = false;
	protected $table = 'mct_user_login';
	protected $primaryKey = 'bi_id';
	
	/*
	 * Check user credentials from Database
	 */
	public function validate_login($inputData){
		
		
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
