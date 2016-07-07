<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use DB;
class Fieldworkers extends Eloquent {
	public $timestamps = false;
	protected $table = 'mct_field_workers';
	protected $primaryKey = 'bi_id';
	
	public function updateProfileTable($inputData){
	
		$fieldworkers					= 	Fieldworkers::find($inputData['userid']);
		$fieldworkers->v_name 			=	$inputData['username'];
		$fieldworkers->v_phone_number	=	$inputData['phone_number'];
		$fieldworkers->v_email			=	$inputData['email'];
		$fieldworkers->dt_birthdate		=	$inputData['bdate'];
		$fieldworkers->v_address		=	$inputData['address'];
		$fieldworkers->i_address_id		=	$inputData['zipcode'];
		if($inputData['filename']!="")
			$fieldworkers->v_profile_pic=	$inputData['filename'];
		$fieldworkers->v_language		=	$inputData['language'];
		$fieldworkers->v_profession		=	$inputData['profession'];
		$fieldworkers->dt_modify_date	=	$inputData['datetime'];
		$fieldworkers->v_ip				=	$_SERVER['REMOTE_ADDR'];
		
		if(isset($inputData['marital_status'])){
			$fieldworkers->e_marital_status=$inputData['marital_status'];
		}
		
		if(isset($inputData['gender'])){
			$fieldworkers->e_gender=$inputData['gender'];
		}
		
		$result = $fieldworkers->save();
		
		//Update Login table
		$result2 = DB::table('mct_user_login')
		->where('bi_id', $inputData['login_userid'])
		->update(array(
				'v_email' => $inputData['email'],
				'v_name' => $inputData['username']
				)
		);
		
		return 	($result == false && $result2 == false) ?  false : true;
		
	}
}