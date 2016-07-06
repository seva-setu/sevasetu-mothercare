<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use DB;
class Admin extends Eloquent {
	public $timestamps = false;
	protected $table = 'mct_admin';
	protected $primaryKey = 'bi_id';
	
	public function updateProfileTable($inputData){
		/*$admin 					= 	$admin->find($userid);
		$admin->v_name 			=	Input::get('txtUsername');
		$admin->v_phone_number	=	Input::get('txtPhoneNumber');
		$admin->v_language		=	Input::get('txtLanguage');
		$admin->v_email			=	Input::get('txtEmail');
		$admin->dt_birthdate	=	$bdate;
		$admin->v_address		=	Input::get('txtAddress');
		$admin->i_address_id	=	Input::get('hdnZipcode');
		if($filename!="")
			$admin->v_profile_pic=$filename;
		$admin->v_language=$language;
		$admin->v_profession=Input::get('txtProfession');
		$admin->dt_modify_date=$datetime;
		//$admin->v_ip=$_SERVER['REMOTE_ADDR'];
		$admin->v_ip = $request->ip;
		return $admin->save();*/
		
		$admin					= 	Admin::find($inputData['userid']);
		$admin->v_name 			=	$inputData['username'];
		$admin->v_phone_number	=	$inputData['phone_number'];
		$admin->v_language		=	$inputData['language'];
		$admin->v_email			=	$inputData['email'];
		$admin->dt_birthdate	=	$inputData['bdate'];
		$admin->v_address		=	$inputData['address'];
		$admin->i_address_id	=	$inputData['zipcode'];
		if($inputData['filename']!="")
			$admin->v_profile_pic=	$inputData['filename'];
		
		$admin->v_profession	=	$inputData['profession'];
		$admin->dt_modify_date	=	$inputData['datetime'];
		$admin->v_ip			=	$_SERVER['REMOTE_ADDR'];
		
		if(isset($inputData['marital_status'])){
			$admin->e_marital_status = $inputData['marital_status'];
		}
		
		if(isset($inputData['gender'])){
			$admin->e_gender=$inputData['gender'];
		}
			
		
		$result = $admin->save();
		
		//Update Login table
		$result2 = DB::table('mct_user_login')
		->where('bi_id', $inputData['login_userid'])
		->update(array(
				'v_email' => $inputData['email'],
				'v_name'  => $inputData['username']
		));
		
		return 	($result == false && $result2 == false) ?  false : true;
		
	}
}