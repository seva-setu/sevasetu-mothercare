<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use DB;

class Callchampions extends Eloquent {
	public $timestamps = false;
	protected $table = 'mct_call_champions';
	protected $primaryKey = 'bi_id';
	public function getlists(){
		$result= Callchampions::where('e_status',"!=" ,'Deleted')->orderBy('bi_id', 'DESC')->paginate(15);
		return $result;
	}
	public function getcallchampion($id){
		$result=Callchampions::leftJoin('mct_address', 'mct_call_champions.i_address_id', '=', 'mct_address.bi_id')
		->select('mct_call_champions.*', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		->where('mct_call_champions.bi_id',$id)
		->get();
		return $result;
	}
	
	public function updateProfileTable($inputData){
		/*$callchampions= $callchampions->find($userid);
		$callchampions->v_name =Input::get('txtUsername');
		$callchampions->v_phone_number=Input::get('txtPhoneNumber');
		$callchampions->v_language=Input::get('txtLanguage');
		$callchampions->v_email=Input::get('txtEmail');
		$callchampions->dt_birthdate=$bdate;
		$callchampions->v_address=Input::get('txtAddress');
		$callchampions->i_address_id=Input::get('hdnZipcode');
		if($filename!="")
			$callchampions->v_profile_pic=$filename;
		$callchampions->v_language=$language;
		$callchampions->v_profession=Input::get('txtProfession');
		$callchampions->dt_modify_date=$datetime;
		$callchampions->v_ip=$_SERVER['REMOTE_ADDR'];
		$result=$callchampions->save();*/
		
		$callchampions					= 	Callchampions::find($inputData['userid']);
		$callchampions->v_name 			=	$inputData['username'];
		$callchampions->v_phone_number	=	$inputData['phone_number'];
		$callchampions->v_language		=	$inputData['language'];
		$callchampions->v_email			=	$inputData['email'];
		$callchampions->dt_birthdate	=	$inputData['bdate'];
		$callchampions->v_address		=	$inputData['address'];
		$callchampions->i_address_id	=	$inputData['zipcode'];
		if($inputData['filename']!="")
			$callchampions->v_profile_pic=	$inputData['filename'];
		
		$callchampions->v_profession	=	$inputData['profession'];
		$callchampions->dt_modify_date	=	$inputData['datetime'];
		$callchampions->v_ip			=	$_SERVER['REMOTE_ADDR'];
		
		if(isset($inputData['marital_status'])){
			$callchampions->e_marital_status = $inputData['marital_status'];
		}
		
		if(isset($inputData['motherhood'])){
			$callchampions->e_motherhood_status = $inputData['motherhood'];
		}
			
		
		$result = $callchampions->save();
		
		//Update Login table
		$result2 = DB::table('mct_user_login')
		->where('bi_id', $inputData['login_userid'])
		->update(array(
				'v_email' => $inputData['email'],
				'v_name'  => $inputData['username']
		));
		
		return 	($result == false && $result2 == false) ?  false : true;
		
	}
	
	public function getAllCallChampions(){
		$result = DB::table('mct_call_champions')
		->leftJoin('mct_beneficiary', 'mct_beneficiary.bi_calls_champion_id', '=', 'mct_call_champions.bi_id')
		->distinct()->select('mct_beneficiary.bi_calls_champion_id','mct_call_champions.*')
		->where('mct_call_champions.e_status',"!=" ,'Deleted')->orderBy('mct_call_champions.bi_id', 'DESC')
		->groupBy('mct_call_champions.bi_id')->paginate(15);
		
		return $result;
	}
}