<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Eloquent;


class Checklist extends Eloquent {
	public $timestamps = false;
	protected $table = 'mct_checklist_type';
	protected $primaryKey = 'bi_id';

	/*
	 *  Show Beneficiary to current user
	 */
	public function save_category($data){
		$this->v_type = $data['Category'];
		$this->save($data);
		
		return $this->bi_id ;
	}
	
	/*
	 * Get Category
	 */
	public function getCategories(){
		return  Checklist::all();
	}
	
	/*
	 * Get Checklist Data by Id
	 */
	public function getCategoryById($id){
		return DB::table('mct_checklist_master')
		->join('mct_checklist_type', 'mct_checklist_master.bi_type_id', '=', 'mct_checklist_type.bi_id')
		->select('mct_checklist_master.*','mct_checklist_type.v_type')
		->where('mct_checklist_master.bi_id','=',$id)
		->get();
	}
	
	
	/*
	 * Options Save
	*/
	public function saveChecklistMaster($data,$id){
		
		$response = explode(',',$data['Response']);
		$final_response = implode('*',$response);
		
		$table_data['v_description'] 		= $data['Description'];
		$table_data['v_recommended_time'] 	= $data['Recommended time'];
		$table_data['v_response_options'] 	= $final_response;
		$table_data['bi_type_id'] 			= $data['Category'];
		$table_data['ti_for']				= $data['Type'];
		
		if($id==0){
			return DB::table('mct_checklist_master')->insert($table_data);
		}else{
			
			return DB::table('mct_checklist_master')->where('bi_id',$id)->update($table_data);			
		}
	}	
	
	/*
	 * Get Checklist Master Data
	 */
	public function getChecklistMaster(){
		 $result=DB::table('mct_checklist_master')
		->join('mct_checklist_type', 'mct_checklist_master.bi_type_id', '=', 'mct_checklist_type.bi_id')
		->select('mct_checklist_master.*','mct_checklist_type.v_type')->where('mct_checklist_master.ti_for','=','0')
		->get();
 		 return $result;
	}

	public function getChecklistBaby(){
	 $result=DB::table('mct_checklist_master')
		->join('mct_checklist_type', 'mct_checklist_master.bi_type_id', '=', 'mct_checklist_type.bi_id')
		->select('mct_checklist_master.*','mct_checklist_type.v_type')->where('mct_checklist_master.ti_for','=','1')->get();
 		 return $result;
	}	
	
	public function getCategoriesMother(){
		return DB::table('mct_checklist_type')
		->join('mct_checklist_master','mct_checklist_master.bi_type_id','=','mct_checklist_type.bi_id')
		->select('mct_checklist_type.*','mct_checklist_master.ti_for','mct_checklist_master.v_description')
		->where('mct_checklist_master.ti_for','=','0')
		->where('mct_checklist_master.v_description','!=','""')
		->groupBy('mct_checklist_type.v_type')->orderBy('mct_checklist_type.bi_id','ASC')
		->get();
	}
	
	public function getCategoriesBaby(){
		return DB::table('mct_checklist_type')
		->join('mct_checklist_master','mct_checklist_master.bi_type_id','=','mct_checklist_type.bi_id')
		->select('mct_checklist_type.*','mct_checklist_master.ti_for','mct_checklist_master.v_description')
		->where('mct_checklist_master.ti_for','=','1')
		->where('mct_checklist_master.v_description','!=','""')
		->orderBy('mct_checklist_type.bi_id','DESC')
		->groupBy('mct_checklist_type.v_type')->orderBy('mct_checklist_type.bi_id','ASC')
		->get();
	}
	
	/*
	 * Get Data from User Data from Userchecklist
	 */
	public function getUserData($id){
		return DB::table('mct_user_checklist')
		->join('mct_checklist_master','mct_checklist_master.bi_id','=','mct_user_checklist.bi_checklist_id')
		->select('mct_user_checklist.*','mct_checklist_master.*')
		->where('mct_user_checklist.bi_beneficiary_id','=',$id)->get();
	}
	
	/*
	 * Insert / Update Data to Userchecklist
	*/
	/*public function insertUserData($final_array,$beneficiaryid){
		
		foreach($final_array as $final){
			$inputData = json_decode($final,true);
			
			if($inputData['respond']!="notselected"){
				$tableData['bi_beneficiary_id'] = $beneficiaryid;
				$tableData['bi_checklist_id'] = $inputData['chkid'];
				$tableData['v_comments'] = $inputData['comment'];
				$tableData['v_response'] = $inputData['respond'];
								
				$isexist  = DB::table('mct_user_checklist')->select('mct_user_checklist.bi_id')
				->where('mct_user_checklist.bi_beneficiary_id','=',$beneficiaryid)
				->where('mct_user_checklist.bi_checklist_id','=',$inputData['chkid'])
				->count();
				
				if($isexist==0){
					DB::table('mct_user_checklist')->insert($tableData);
				}else{
					DB::table('mct_user_checklist')
					->where('bi_beneficiary_id',$beneficiaryid)
					->where('bi_checklist_id',$inputData['chkid'])
					->update($tableData);
				}
			}
		}
		return true;
	}*/
	
	
	/*
	 * Get User Checklist  by Checklist Id and Beneficiary Id
	 */
	public function getUserDataById($checklist_id,$benid){
		$isexist = DB::table('mct_user_checklist')->join('mct_checklist_master','mct_user_checklist.bi_checklist_id','=','mct_checklist_master.bi_id')
		->select('mct_user_checklist.bi_checklist_id','mct_user_checklist.v_response','mct_user_checklist.v_comments','mct_checklist_master.v_response_options','mct_checklist_master.v_recommended_time','mct_checklist_master.v_description')
		->where('bi_checklist_id','=',$checklist_id)
		->where('bi_beneficiary_id','=',$benid)
		->count();
		
		if($isexist==0){
			$result = DB::table('mct_checklist_master')
			->select('bi_id as bi_checklist_id','v_response_options','v_recommended_time','v_description')
			->where('bi_id','=',$checklist_id)
			->get();
			$result['v_response'] = "";
			$result['v_comments'] = "";
			return $result;
		}else{
			return DB::table('mct_user_checklist')->join('mct_checklist_master','mct_user_checklist.bi_checklist_id','=','mct_checklist_master.bi_id')
			->select('mct_user_checklist.bi_checklist_id','mct_user_checklist.v_response','mct_user_checklist.v_comments','mct_checklist_master.v_response_options','mct_checklist_master.v_recommended_time','mct_checklist_master.v_description')
			->where('bi_checklist_id','=',$checklist_id)
			->where('bi_beneficiary_id','=',$benid)
			->get();
		}
	}
	
	public function saveUserDataById($checklist_id,$benid,$response,$comment){
		if($response!=""){
			$tableData['bi_beneficiary_id'] = $benid;
			$tableData['bi_checklist_id'] = $checklist_id;
			$tableData['v_comments'] = $comment;
			$tableData['v_response'] = $response;
		
			$isexist  = DB::table('mct_user_checklist')->select('mct_user_checklist.bi_id')
			->where('mct_user_checklist.bi_beneficiary_id','=',$benid)
			->where('mct_user_checklist.bi_checklist_id','=',$checklist_id)
			->count();
		
			if($isexist==0){
				return DB::table('mct_user_checklist')->insert($tableData);
			}else{
				return DB::table('mct_user_checklist')
				->where('bi_beneficiary_id',$benid)
				->where('bi_checklist_id',$checklist_id)
				->update($tableData);
			}
		}
	}
}