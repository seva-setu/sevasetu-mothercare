<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Fieldworkers;
use App\Models\CallChampion;
use App\Models\Users;
use App\Models\DueList;
use App\Models\Checklist;

use Request;
use Mail;
use Auth;
use DB; 
use Hash;
use Validator;
use Excel;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\UserInterface;
use Illuminate\Http\Response;
use Hashids\Hashids;
use \Maatwebsite\Excel\Files\ImportHandler;
use App\Http\Helpers; 
use App\Models\App\Models;


class BeneficiaryController extends Controller{
	protected $model;
	public $title="Admin Login";
	
	public $user_id;
	public $role_id;
	public $role_type;
	
	protected $helper;
	protected $role_permissions;
	
	public function __construct(){
		if(!Session::has('user_logged')){
			Redirect::to('/')->send();
		}
		else{
			$userinfo=Session::get('user_logged');
			$this->user_id=$userinfo['user_id'];
			$this->role_id=$userinfo['role_id'];
			$this->role_type=$userinfo['v_role'];
		}
	}

	// make the input to this function an excel
	public function add_beneficiary_list(Request $request, $field_worker_id){
		$data = import_excel($input);
		foreach($data as $beneficiary_data){
			// add a beneficiary into the system
			$beneficiary_id = $this->add_new_beneficiary($beneficiary_data, $field_worker_id);
			
			// prepare the due list for registered beneficiary
			$due_list_ids = $this->add_due_list($beneficiary_id, $delivery_date);
			
			// assign a call champion	
			// This assigns a call champion to all the due-list IDs belonging to a beneficiary
			// Another way is call champions are assigned to due-list IDs individually 
			$result3 = $this->allocate_call_champion($due_list_ids, $beneficiary_id);
			if(!$result1 or !$result2 or !result3)
				die("exception");
		}
		return true;
	}
	public function add_new_beneficiary($beneficiary_data, $field_worker_id){
		// Model beneficiary::add_beneficiary
		$beneficiary_obj = new Beneficiary;
		$beneficiary_id = $beneficiary_obj->insert($beneficiary_data);
		if(!$beneficiary_id)
			die("exception");
		
		return $beneficiary_id;
	}
	
	public function tester_method(){
		 die("test done");
	}
	public function upload_mother(){
		$bene_obj = Beneficiary::all();
		foreach($bene_obj as $bene){
			// add a check - if mother doesnt exist in the DB, only then do the following -
			$due_list = $this->add_due_list($bene->b_id, $bene->dt_due_date);
			$this->allocate_call_champion($due_list, $bene->b_id);
			$this->update_call_champion_report($due_list);
			
		}
		
		 die("mother upload done");
	}
	
	public function add_due_list($beneficiary_id, $delivery_date){
		$due_list = $this->calculate_due_list($delivery_date);
		$duelist_obj = new DueList;
		return $duelist_obj->update_due_list($beneficiary_id, $due_list);
	}
	
	public function calculate_due_list($delivery_date){
		// LMP: Last Menstrual Period
		$LMP_DATE_CALC_WEEKS = 39;
		$begin_date  = date("Y-m-d", strtotime('-'.$LMP_DATE_CALC_WEEKS.' weeks',strtotime($delivery_date)));
		$weeks_to_add = array(0, 12, 16, 26, 36, 40, 46, 50, 54, 64);
		$due_list = array();
		for($i=0;$i<count($weeks_to_add);$i++){
			$due_list []= date("Y-m-d", strtotime('+'.$weeks_to_add[$i].' weeks',strtotime($begin_date)));
		}
		
		return $due_list;
	}
	
	public function allocate_call_champion($due_list_ids_arr = array(), $beneficiary_id = -1, $MAX_ALLOWED_PER_CC = 5){
		if($beneficiary_id == -1)
			$beneficiary_id = $this->get_beneficiary_id($due_list_ids_arr);
		
		// the input to the method below may have to be due_list_ids_arr
		$prev_champ_ids = $this->get_previous_call_champions_for_beneficiary($beneficiary_id);
		if(!$prev_champ_ids){
			$call_champ_id = $this->randomly_select_call_champ();
			return $this->assign_call_champion_duelist_id($call_champ_id, $due_list_ids_arr);
		}
		else{
			$existing_assign_counts = $this->check_existing_assignments($prev_champ_ids);
			for($i=0; $i<count($prev_champ_ids); $i++){
				$call_champ_id = $prev_champ_ids[$i]->cc_id;
				if($existing_assign_counts[$call_champ_id] < $MAX_ALLOWED_PER_CC){
					return $this->assign_call_champion_duelist_id($call_champ_id, $due_list_ids_arr);
				}
			}
			// This means all past users are aboove their threshold
			// In which case randomly assign one
			$call_champ_id = $this->randomly_select_call_champ();
			return $this->assign_call_champion_duelist_id($call_champ_id, $due_list_ids_arr);
		}
	}
	
	public function randomly_select_call_champ(){
		$call_champ_obj = new CallChampion;
		$list_champ = $call_champ_obj->get_active_call_champions();
		$rand_ind = rand(0,count($list_champ)-1);
		return $list_champ[$rand_ind];
	}
	
	public function check_existing_assignments($prev_champ_ids){
		$list = array();
		for($i = 0; $i<count($prev_champ_ids); $i++)
			$list []= $prev_champ_ids[$i]->cc_id;
		

		$due_list_obj = new DueList;
		$existing_counts = $due_list_obj->get_existing_assignments($list);
		
		//prepare output as a key-value pair
		return $existing_counts;
	}
	
	public function get_previous_call_champions_for_beneficiary($beneficiary_id){
		// the input to the method below may have to be due_list_ids_arr
		$due_list_obj = new DueList;
		$list = $due_list_obj->get_previous_call_champions($beneficiary_id);
		return $list;
	}
	
	public function assign_call_champion_beneficiary_id($beneficiary_id, $call_champ_id){
		$due_list_obj = new DueList;
		$due_list_ids_arr = $due_list_obj->get_beneficiary_duelist_id($beneficiary_id);
		return $due_list_obj->assign_call_champion_duelist_id($call_champ_id, $due_list_ids_arr);
	}
	
	public function assign_call_champion_duelist_id($call_champ_id, $due_list_ids_arr){
		$due_list_obj = new DueList;
		return $due_list_obj->assign_call_champion_duelist_id($call_champ_id, $due_list_ids_arr);
	}
	
	public function update_call_champion_report($due_list){
		$table_name = 'mct_callchampion_report';
		$insert_array = [];
		foreach($due_list as $val)
			$insert_arr []= array('fk_due_id'=>$val);
		
		DB::table($table_name)
			->insert($insert_arr);
		
		// Needs exception handling here
		return true;
	}
	
	public function list_all_beneficiaries(){
		
	}
	
	public function importExcel(){
    	$beneficiary= new Beneficiary;
    	$mess=array();
    	$destinationPath = 'external/uploads'; // upload path
    	$extension = Input::file('txtExcel')->getClientOriginalExtension(); // getting image extension
    	$fileName = rand(11111,99999).'.'.$extension; // renameing image
    	Input::file('txtExcel')->move($destinationPath, $fileName); // uploading file to given path
    	
  	// Applying validation rules.
    $filedworkerid="";
    if($_POST['fieldwrokerid']!="")
    	$filedworkerid=$this->decode($_POST['fieldwrokerid']);
  	$rules = array(
  			'name' => 'required|min:3|max:20|Regex:/^[A-Za-z]+[A-Za-z0-9.\' ]*$/',
  			'husband_name'=>'required|min:3|max:20',
  			'phone_number'=>'required|numeric|digits_between:10,20',
  			'village'=>'required|min:3|max:20',
  			'language'=>'required',
  			'pincode'=>'required|numeric|digits_between:4,6',
  			'due_date'=>'required_without:delivery_date|date_format:d/m/Y',
  			'delivery_date'=>'required_without:due_date|date_format:d/m/Y'
  			
  	);
  		$arr = Excel::load($destinationPath.'/'.$fileName)->get();
    	$i=0;
    	if(!empty($arr)){
    		if(isset($arr[0]->name) && isset($arr[0]->language) && isset($arr[0]->number_pregnancies) && isset($arr[0]->husband_name) && isset($arr[0]->phone_number) && isset($arr[0]->alternate_phone_no)  && isset($arr[0]->village) && isset($arr[0]->pincode)   && isset($arr[0]->taluka) && isset($arr[0]->awc_name) && isset($arr[0]->awc_number) && isset($arr[0]->due_date) && isset($arr[0]->delivery_date)){
    		
    		}else{
    			Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.excelvalid").'</div>');
    			return Redirect::to('/beneficiary/');
    		}
    		foreach($arr as $key=>$val){
    			$datetime = date("Y-m-d H:i:s");
    			$i++;
    			$due_date=str_replace("-", "/", $val->due_date);
    			$delivery_date=str_replace("-", "/", $val->delivery_date);
    			$userdata = array(
    					'name' => $val->name,
    					'husband_name'=>$val->husband_name,
    					'phone_number'=>$val->phone_number,
    					'village'=>$val->village,
    					'language'=>$val->language,
    					'pincode'=>$val->pincode,
    					'due_date'=>date('d/m/Y',strtotime($due_date)),
    					'delivery_date'=>date('d/m/Y',strtotime($delivery_date))
    			);
    			$validator = Validator::make($userdata, $rules);
    			$address_id="";
    			$address_id= DB::table('mct_address')->where('v_pincode', 'LIKE', '%'.$val->pincode.'%')->take(1)->get();
    			$language=explode(",", strtolower($val->language));
    			$langId= DB::table('mct_language')->select('bi_id')->whereIn('v_language',$language)->get();
    			$result = array();
    			if(count($langId)>0){
	    			foreach ($langId as $key => $value) {
	    				$result[] = $value->bi_id;
	    			}
    			}
    			$langid=implode(",",$result);
    			if ($validator->fails() || empty($address_id)){ //check validation
    				$messages = $validator->messages()->toArray();
    				if(empty($address_id))
    					$messages["zipcode"]="Address does not Match.";
    				$mess[$i]=$messages;
    			}else{
    				$lastrow=$beneficiary::orderBy('bi_id', 'DESC')->first();
	
					if(count($lastrow)>0)
						$code='BF'.($lastrow->bi_id+1);
					else
						$code='BF1';
					
    				$data=array(
    						'v_name' =>$val->name,
    						'v_language'=>$langid,
    						'i_number_pregnancies'=>$val->number_pregnancies,
    						'v_husband_name'=>$val->husband_name,
    						'v_phone_number'=>$val->phone_number,
    						'v_alternate_phone_no'=>$val->alternate_phone_no,
    						'v_address'=>$val->village,
    						'i_address_id' =>$address_id[0]->bi_id,
    						'v_awc_name'=>$val->awc_name,
    						'v_awc_number'=>$val->awc_number,
    						'v_unique_code'=>$code,
    						'dt_due_date'=>date("Y-m-d H:i:s",strtotime($val->due_date)),
    						'dt_delivery_date'=>date("Y-m-d H:i:s",strtotime($val->delivery_date)),
    						'dt_lmp_date'=>date('Y-m-d H:i:s',strtotime('-36 week', strtotime($val->due_date))),
    						'dt_create_date'=>$datetime,
    						'dt_modify_date'=>$datetime,
    						'e_status'=>"Active",
    						'v_ip'=>$_SERVER['REMOTE_ADDR']);
    				$data=$beneficiary->insert($data);//insert excel data into database
    				$lastid=DB::getPdo()->lastInsertId();
    				if($lastid){
    					if($this->usertype==3){
    						$user = Fieldworkers::query()->where('bi_user_login_id', $this->userid)->get(array('bi_id'));
    						$data=$beneficiary::where('bi_id', $lastid)->update(['bi_field_worker_id' => $user[0]->bi_id]);
    					}elseif($filedworkerid!=""){
    						$data=$beneficiary::where('bi_id', $lastid)->update(['bi_field_worker_id' => $filedworkerid]);
    					}
    				}
    				//Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              //<button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.beneficiary").' '.trans("routes.addmessage").'</div>');
    			}
    		}
    		Session::flash('errormessage',$mess);
    		return Redirect::to('/beneficiary/');
    	}
 	}
 	
	///////////////////////////////////////////	

}