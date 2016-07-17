<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;
use Session;
use Eloquent;


class Beneficiary extends Eloquent {
	public $timestamps = false;
	protected $table = 'mct_beneficiary';
	protected $primaryKey = 'b_id';
	
	/*
	 * Insert Beneficiary Report in Database.
	 */
	
	
	public function get_beneficiary_details($beneficiary_id){
		$join_table1 = 'mct_field_workers';
		$join_table2 = 'mct_user';
		$select = DB::table($this->table)
				->join($join_table1, $join_table1.'.f_id','=', $this->table.'.fk_f_id')
				->join($join_table2, $join_table1.'.fk_user_id','=', $join_table2.'.user_id')
				->select(
							$this->table.'.v_name as name',
							$this->table.'.v_village_name as village_name',
							$this->table.'.v_husband_name as husband_name',
							$this->table.'.v_phone_number as phone_number',
							$this->table.'.dt_due_date as due_date',
							$this->table.'.t_notes as mother_notes',
							
							$join_table1.'.f_id as fieldworker_id',
							$join_table2.'.v_name as field_worker_name',
							$join_table2.'.i_phone_number as field_worker_number'
						);
		if(is_array($beneficiary_id)){
				$select = $select->wherein($this->table.'.b_id',$beneficiary_id);
		}
		else
				$select = $select->where($this->table.'.b_id','=',$beneficiary_id);
		$select = $select->get();
				
		return $select;
	}
	
		
	public function update_notes($beneficiary_id, $notes){
		
	}
	
	
	
	/////////////////////////////////////////////////////////
	public function insert($beneficiary_data, $field_worker_id){
		$this->v_name 			= $beneficiary_data[''];
		$this->fk_f_id 			= $field_worker_id;
		$this->v_husband_name 	= $beneficiary_data[''];
		$this->v_phone_number 	= $beneficiary_data[''];
		$this->v_awc_number 	= $beneficiary_data[''];
		$this->v_village_name 	= $beneficiary_data[''];
		$this->dt_due_date 		= $beneficiary_data[''];
		$this->v_language 		= $beneficiary_data[''];
		
		$result = $this->save();
		if($result)
			return $this->getKey();
		else
			return false;
	} 
	
	
	
	public function insertReport($benId,$emergencynote){
		$res = DB::select("select  bi_field_worker_id,bi_calls_champion_id from mct_beneficiary where bi_id=$benId");
		
		$datetime = date("Y-m-d H:i:s");
		$insert=array(
				'bi_beneficiary_id'		=>$benId,
				'bi_field_worker_id'	=>$res[0]->bi_field_worker_id,
				'bi_calls_champion_id'	=>$res[0]->bi_calls_champion_id,
				'dt_created_at'			=>$datetime,
				't_emergency_note'		=>$emergencynote,
				//'dt_complated_at'=> $datetime,
				//'t_emergency_comment'=> "fdg",
				'dt_updated_at'			=>$datetime,
				'ti_iscomplete' 		=> 0
		);
		$res=DB::table('mct_emergency_note')->insert($insert);
	}
	
	/*
	 * Update Beneficiary Report in Database.
	 */
	public function updateReport($updateData,$reportid,$send_email){
		$res=DB::table('mct_emergency_note')->where('bi_id', $reportid)->update($updateData);
		
		$data = array();
		if($send_email){
			$data['result'] = DB::table('mct_emergency_note')->join('mct_call_champions', 'mct_emergency_note.bi_calls_champion_id', '=','mct_call_champions.bi_id')->select('mct_call_champions.*')->where('mct_emergency_note.bi_id','=',$reportid)->get();
			$data['admin_email'] = DB::table('mct_user_login')->select('v_email')->where('bi_id','=','1')->get();			
			
			$admin_email = "admin@sevasetu.org";
			
			if(isset($data['admin_email'][0]->v_email) &&  $data['admin_email'][0]->v_email!="") {  $admin_email = $data['admin_email'][0]->v_email; }
			
			if(!empty($data['result'])){
				$data['note'] = $updateData['t_emergency_note'];
				$data['comment'] = $updateData['t_emergency_comment'];
				$email =  $data['result'][0]->v_email;
				$sent=Mail::send('emails.actioncomplete',$data, function($message) use ($email,$admin_email){
					$message->to($email)->cc($admin_email)->subject('Action Item Completed in Mother Care Tool');
				});
			}
		}		
	}
	
	/*
	 *  Filter By Fieldworker ID
	 */
	public function filterByFW($id){
		 $result=DB::table('mct_beneficiary')
		->join('mct_field_workers', 'mct_beneficiary.bi_field_worker_id', '=', 'mct_field_workers.bi_id')
		->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
		->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		->where('mct_beneficiary.e_status',"!=",'Deleted')
		->where('mct_beneficiary.bi_field_worker_id','=',$id)
		->orderBy('bi_id', 'DESC')
		->paginate(15);
		 return $result;
	}
	

	/*
	 *  Filter By CallChampion ID
	 */
	public function filterByCC($id){
		return DB::table('mct_beneficiary')
		->join('mct_call_champions', 'mct_beneficiary.bi_calls_champion_id', '=', 'mct_call_champions.bi_id')
		->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
		->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		->where('mct_beneficiary.e_status',"!=",'Deleted')
		->where('mct_beneficiary.bi_calls_champion_id','=',$id)
		->orderBy('bi_id', 'DESC')
		->paginate(15);
	
	}
	
	public function filterByAssigned($param){
		if($param=="assigned"){	
			$result=DB::table('mct_beneficiary')
			->join('mct_call_champions', 'mct_beneficiary.bi_calls_champion_id', '=', 'mct_call_champions.bi_id')
			->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
			->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
			->where('mct_beneficiary.e_status',"!=",'Deleted')
			->where('mct_beneficiary.bi_calls_champion_id','<>',"")
			->orderBy('bi_id', 'DESC')
			->paginate(15);
		}else if($param=="unassigned"){
			$result=DB::table('mct_beneficiary')
			->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
			->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
			->where('mct_beneficiary.e_status',"!=",'Deleted')
			->where('mct_beneficiary.bi_calls_champion_id')
			->orderBy('bi_id', 'DESC')
			->paginate(15);
		}
		//dd($result);
		return $result;
	}	
	
	/*
	 *  Show Beneficiary to current user
	 */
	public function myAssignedBeneficiary($id){
		$v_role = Session::get('user_logged')['v_role']; 
		$user_table ="";
		$primaryid = "";
		
		if($v_role==2 || $v_role==3){
			
			switch($v_role){
				case 2: $user_table = "mct_call_champions";
						$primaryid 	= "bi_calls_champion_id";
						break;
				case 3: $user_table = "mct_field_workers";
						$primaryid 	= "bi_field_worker_id";
						break;
			}
			
			$result=DB::table('mct_beneficiary')
			->join($user_table, 'mct_beneficiary.'.$primaryid, '=', $user_table.'.bi_id')
			->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
			->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
			->where('mct_beneficiary.e_status',"!=",'Deleted')
			->where('mct_beneficiary.'.$primaryid,'=',$id)
			->orderBy('bi_id', 'DESC')
			->paginate(15);
			
			return $result;
		}
	}

	/*
	 * Get all beneficiary
	 */
	public function getAllBeneficiary(){
		$result=DB::table('mct_beneficiary')
		->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
		->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		->where('e_status',"!=",'Deleted')
		->orderBy('bi_id', 'DESC')
		->paginate(15);
		return $result;
	}
	
	
	
	/*
	 * Get Intervention Points
	 */
	public function getInterventionPoints(){
		return DB::select("Select distinct i_week from mct_intervention_point where e_status='Active' order by i_week ASC");
	}
	
	public function getAssignedBeneficiary($callchampion_id){
		$result = array();
		
		if($callchampion_id!=0){
	  	 	$result = DB::table('mct_beneficiary as ben')
	  	 	->leftJoin('mct_address as ad','ad.bi_id','=','ben.i_address_id')
	 	
			->select('ben.v_name','ben.bi_id','ben.v_unique_code','ben.v_husband_name','ben.v_phone_number','ben.dt_due_date','ben.dt_delivery_date','ad.v_village','ad.v_taluka','ad.v_district','ben.v_alternate_phone_no')
			->where('ben.bi_calls_champion_id','=',$callchampion_id)
			->where('ben.e_status','=','Active')
			->orderBy('ben.dt_due_date','DESC')
			->get();
		}else{
			$result = DB::table('mct_beneficiary as ben')
			->leftJoin('mct_call_champions as champ','champ.bi_id','=','ben.bi_calls_champion_id')
			->leftJoin('mct_address as ad','ad.bi_id','=','ben.i_address_id')
			->select('ben.v_name','ben.bi_id','ben.v_unique_code','ben.v_husband_name','ben.v_phone_number','ben.dt_due_date','ben.dt_delivery_date','ad.v_village','ad.v_taluka','ad.v_district','champ.v_name as champ_name','ben.v_alternate_phone_no')
			->where('ben.e_status','=','Active')
			->orderBy('ben.dt_due_date','DESC')
			->get();
		}
	//dd($result);
		return $result;
	}	
	
	
	
	
	/*
	 * backup
	 */
	public function checkBetween($beneficiaries,$interventions,$startDate,$endDate){
		$final_array = array();
		$beneficiary_details = array();
		
	
		foreach($beneficiaries as $ben){
			
			if($ben->dt_due_date == '1970-01-01 00:00:00')
			break;
			
			$duedate = strtotime($ben->dt_due_date) > 0?date('d/m/Y',strtotime($ben->dt_due_date)):"";
			$deliverydate	= strtotime($ben->dt_delivery_date) > 0?date('d/m/Y',strtotime($ben->dt_delivery_date)):"";
			
			$datetocalc = "";
			if($duedate!=""){
				$datetocalc = $duedate;		
			}elseif($deliverydate!=""){
				$datetocalc = $deliverydate;
			}
			
		
			
			$now = new \DateTime();
			$dt = $now->createFromFormat('d/m/Y', $datetocalc);
			$intecaldate = $dt->format('Y-m-d');
		
			$isBetween = false;
		
			foreach($interventions as $intervention){
				$intervation_date = $this->addWeeks($intervention->i_week,$intecaldate);
		
				//	$intervation_date = strtotime($intervation_date);
				//	$startDate = strtotime($startDate);
				//	$endDate = strtotime($endDate);
				/*echo "Beneficiary Id : " . $ben->bi_id;
				echo "<br>Beneficiary Name : " . $ben->v_name;
				echo "<br>Start Date " . $startDate;
				echo "<br>End  Date " . $endDate;
				echo "<br>Raw Date " . $datetocalc;
				echo "<br>Calc Date " . $intecaldate;
				echo "<br>Intervation Date " . $intervation_date;
				exit;*/
				
				//Check if date falls in filter week
				if($intervation_date>=$startDate && $intervation_date<=$endDate){
					// if date falls in filter push to array
					$isBetween = true;
					break;
				}else{
					$isBetween = false;
				}				
				
			}
		
			if($isBetween){
				$beneficiary_details['v_unique_code'] 		= $ben->v_unique_code;
				$beneficiary_details['bi_id'] 			 	= $ben->bi_id;
				$beneficiary_details['v_name'] 			 	= $ben->v_name;
				$beneficiary_details['dt_due_date'] 	 	= $ben->dt_due_date;
				$beneficiary_details['dt_delivery_date'] 	= $ben->dt_delivery_date;
				$beneficiary_details['v_husband_name'] 		= $ben->v_husband_name;
				$beneficiary_details['v_phone_number'] 		= $ben->v_phone_number;
				$beneficiary_details['v_alternate_phone_no'] 		= $ben->v_alternate_phone_no;
				$beneficiary_details['intervention_date'] 	= date('d/m/Y',strtotime($intervation_date));
				

///			 	if($ben->v_village!="") { $beneficiary_details['v_village'] =  $ben->v_village; }else{  $beneficiary_details['v_village'] = "" };
	///		 	if($ben->v_taluka!="") { $beneficiary_details['v_taluka'] =  $ben->v_taluka; }else{  $beneficiary_details['v_taluka'] = "" };
	///			if($ben->v_district!="") { $beneficiary_details['v_district'] =  $ben->v_district; }else{  $beneficiary_details['v_district'] = "" };
			//	if($ben->champ_name!="") { $beneficiary_details['champ_name'] =  $ben->champ_name; }
				
				$beneficiary_details['v_village'] = (isset($ben->v_village) && $ben->v_village!="") ? $ben->v_village : "";
				$beneficiary_details['v_taluka'] = (isset($ben->v_taluka) && $ben->v_taluka!="") ? $ben->v_taluka : "";
				$beneficiary_details['v_district'] = (isset($ben->v_district) && $ben->v_district!="") ? $ben->v_district : "";
				
				$beneficiary_details['champ_name'] = (isset($ben->champ_name) && $ben->champ_name!="") ? $ben->champ_name : "";
				
				$beneficiary_details['intervention_date'] 	= date('d/m/Y',strtotime($intervation_date));
				
				array_push($final_array,$beneficiary_details);
			}
			
		}
		
		//dd($final_array);
		return $final_array;
	}
	
	private function addWeeks($numweeks,$date){
		return date('Y-m-d',strtotime("+$numweeks week".$date));
	}
	
	
	/* DB Pagination */
	
	public function getbeneficiary($startdate,$enddate){
		$ddate=str_replace("/", "-",$startdate);
		$startdate=date("Y-m-d H:i:s",strtotime($ddate));
		$devdate=str_replace("/", "-",$enddate);
		$enddate=date("Y-m-d H:i:s",strtotime($devdate));
		$diff=date_diff(date_create($startdate),date_create($enddate));
		$day=$diff->format("%a");
		$interventions=DB::table('mct_intervention_point')->select('i_week')->where('e_status','Active')->orderBy('i_week','ASC')->get();
		$extr="";
		$i=0;
		
		foreach ($interventions as $k=>$v){
			$i++;
			$point1=($v->i_week*7);
			$point2=($v->i_week*7);
			$extr.="".$point1." >=( DATEDIFF('".$startdate."',dt_due_date) && DATEDIFF('".$enddate."',dt_due_date) <=".$point2." )";
			if($i < count($interventions))
				$extr.=" or ";
		}
		
		$data['result'] = DB::select('elect mct_beneficiary.*,DATEDIFF("'.$startdate.'",dt_due_date) as firstpoint,DATEDIFF("'.$enddate.'",dt_due_date) as secondpoint from mct_beneficiary where mct_beneficiary.bi_calls_champion_id IS NULL and mct_beneficiary.e_status!="Deleted" and ('.$extr.')');
		dd($data['result']); exit;
		
	}
	


}
