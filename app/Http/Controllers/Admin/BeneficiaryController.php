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
		$userinfo=Session::get('user_logged');
		//check for valid use
		if(!isset($userinfo['role_id'])){
			Redirect::to('/admin/')->send();
		}
		$this->role_type=$userinfo['v_role'];
		$this->user_id=$userinfo['user_id'];
		$this->role_id = $userinfo['role_id'];
		
		$this->helper = new Helpers();
		$this->role_permissions = $this->helper->checkpermission(Session::get('user_logged')['v_role']);
		
		$this->helper->clearBen_Data();
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
		$bene_obj = Beneficiary::all();
		foreach($bene_obj as $bene){
			for($i=0;$i<10;$i++){
				$duelist_obj = new DueList();
				$due_list = $duelist_obj->get_duelist($bene->b_id);
				$this->allocate_call_champion($due_list, $bene->b_id);
			}
		}
		
		die("adone");
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
    			return Redirect::to('/admin/beneficiary/');
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
    		return Redirect::to('/admin/beneficiary/');
    	}
 	}
 	
	
	
	
	
	
	///////////////////////////////////////////
	
	
	
	
	
	
//defualt method for beneficiary	
public function  index(){
	$data = array();
	$data['title']="Beneficiary" . SITENAME;
	//$beneficiary =new Beneficiary;
	
	/*$data['result']=DB::table('mct_beneficiary')
	->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
	->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
	->where('e_status',"!=",'Deleted')
	->orderBy('bi_id', 'DESC')
	->paginate(15);
	if (Request::ajax()) {
		return view('beneficiary/manageajax',$data);
	}*/
	//$data['fieldworker']= DB::table('mct_field_workers')->where('e_status',"!=",'Deleted')->orderBy('bi_id', 'DESC')->get();
	$beneficiary 	= new Beneficiary;
	$id = Session::get('user_logged')['user_id'];
	$v_role = Session::get('user_logged')['v_role'];
		
	if($v_role==2 || $v_role==3){
		if($id!=0){
			$data['result'] = $beneficiary->myAssignedBeneficiary($id);
		}
	}else{
		$data['result'] = $beneficiary->getAllBeneficiary();
	}
	
	return view('beneficiary/manage',$data);
}

//insert or edit for beneficiary
public function edit($id=0){
	$id=$this->decode($id);
	$data['title']="Beneficiary" . SITENAME;
	
	//langauge list
	$data['languagedata']= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
	
	$beneficiary= new Beneficiary;
	
	if($id==0){
		//fetch id for particuler user
		$data['action']="add";
		$data['result']['v_name'] ="";
		$data['result']['i_number_pregnancies']="";
		$data['result']['v_husband_name']="";
		$data['result']['v_alternate_phone_no']="";
		$data['result']['v_address']="";
		$data['result']['v_pincode']="";
		$data['result']['v_taluka']="";
		$data['result']['v_district']="";
		$data['result']['v_state']="";
		$data['result']['v_country']="";
		$data['result']['v_awc_name']="";
		$data['result']['v_awc_number']="";
		$data['result']['v_phone_number']="";
		$data['result']['v_language']="";
		$data['result']['dt_due_date']=0;
		$data['result']['i_address_id']=0;
		$data['result']['dt_delivery_date']=0;
		$data['result']['bi_id']=0;
		$data['result']=(Object)$data['result'];
	}else{
		//fetch data from database for edit 
		$data['result']=DB::table('mct_beneficiary')
		->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
		->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_village_pincode', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		->where('mct_beneficiary.bi_id',$id)
		->get();
		$data['result']=$data['result'][0];
		$data['action']="update";
	}
	return view('beneficiary/form',$data);
}
public function add(){// add record
	$beneficiary= new Beneficiary;
	$callchampions= new Callchampions;
	$users= new Users;
	$datetime = date("Y-m-d H:i:s");
	$language=implode(Input::get('txtLanguage'), ",");
	$lastrow=$beneficiary::orderBy('bi_id', 'DESC')->first();
	
	if(count($lastrow)>0)
		$code='BF'.($lastrow->bi_id+1);
	else
		$code='BF1';
		$ddate=str_replace("/", "-",Input::get('txtDueDate'));
		$duedate=Input::get('txtDueDate')!="" ?date("Y-m-d",strtotime($ddate)):"";
		$devdate=str_replace("/", "-",Input::get('txtDeliveryDate'));
		$deliverydate=Input::get('txtDeliveryDate')!="" ?date("Y-m-d",strtotime($devdate)):"";
		$lmpddate=date('Y-m-d',strtotime('-36 week'.$duedate));
			$data=array(
				'v_name' =>Input::get('txtUsername'),
				'v_language'=>$language,
				'i_number_pregnancies'=>Input::get('txtNumberPregnancies'),
				'v_husband_name'=>Input::get('txtHusbname'),
				'v_phone_number'=>Input::get('txtPhoneNumber'),
				'v_address'=>Input::get('txtAddress'),	
				'v_alternate_phone_no'=>Input::get('txtAltPhoneNumber'),
				'i_address_id'=>Input::get('hdnZipcode'),
				'v_awc_name'=>Input::get('txtAwcName'),
				'v_awc_number'=>Input::get('txtAwcNumber'),
				'v_unique_code'=>$code,
				'dt_due_date'=>$duedate,
				'dt_lmp_date'=>$lmpddate,	
				'dt_delivery_date'=>$deliverydate,
				'dt_create_date'=>$datetime,
	  			'dt_modify_date'=>$datetime,
	  			'e_status'=>"Active",
	  			'v_ip'=>$_SERVER['REMOTE_ADDR']);
	  			//print_r($product); exit;
	  			//insert beneficiary
			$res=$beneficiary->insert($data);
	  		$lastid=DB::getPdo()->lastInsertId();
			if($lastid){
	  			if($this->usertype==3){
	  				$user = Fieldworkers::query()->where('bi_user_login_id', $this->userid)->get(array('bi_id'));
	  				$data=$beneficiary::where('bi_id', $lastid)->update(['bi_field_worker_id' => $user[0]->bi_id]);
	  			}elseif($this->decode($_POST['hdnUserId'])!=""){
	  				$data=$beneficiary::where('bi_id', $lastid)->update(['bi_field_worker_id' => $this->decode($_POST['hdnUserId'])]);
	  			}
	  		}
	  		if($res){
	  				Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.beneficiary").' '.trans("routes.addmessage").'</div>');
					return Redirect::to('/admin/beneficiary/');
				}else{
					Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.beneficiary").' '.trans("routes.notaddmessage").'</div>');
					return Redirect::to('/admin/beneficiary/');
				}
	}
	//update  record
	public function update(){
      	$beneficiary= new Beneficiary;
		$datetime = date("Y-m-d H:i:s");
		$productid=Input::get('hdnId');
		$language=implode(Input::get('txtLanguage'), ",");
		$ddate=str_replace("/", "-",Input::get('txtDueDate'));
		$duedate=Input::get('txtDueDate')!="" ?date("Y-m-d",strtotime($ddate)):"";
		$devdate=str_replace("/", "-",Input::get('txtDeliveryDate'));
		$deliverydate=Input::get('txtDeliveryDate')!="" ?date("Y-m-d",strtotime($devdate)):"";
		$beneficiary= $beneficiary->find($productid);
      	$beneficiary->v_language=$language;
		$beneficiary->i_number_pregnancies=Input::get('txtNumberPregnancies');
		$beneficiary->v_husband_name=Input::get('txtHusbname');
		$beneficiary->v_phone_number=Input::get('txtPhoneNumber');
		$beneficiary->v_address=Input::get('txtAddress');
		$beneficiary->v_alternate_phone_no=Input::get('txtAltPhoneNumber');
		$beneficiary->i_address_id=Input::get('hdnZipcode');
		$beneficiary->v_awc_name=Input::get('txtAwcName');
		$beneficiary->v_awc_number=Input::get('txtAwcNumber');
		$beneficiary->dt_due_date=$duedate;
		$beneficiary->dt_delivery_date=$deliverydate;
		$beneficiary->dt_modify_date=$datetime;
      	$beneficiary->v_ip=$_SERVER['REMOTE_ADDR'];
      	//update beneficiary
      	$result=$beneficiary->save();
      	
      	if($result){
      		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.beneficiary").' '.trans("routes.updatemessage").'</div>');
      		return Redirect::to('/admin/beneficiary/');
		}else{
			Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.beneficiary").' '.trans("routes.notupdatemessage").'</div>');
			return Redirect::to('/admin/beneficiary/');
      	}
    }
    //delete record 
    public function delete($id,$flag){
    $id=$this->decode($id);
    $flag=$this->decode($flag);
    $beneficiary= new Beneficiary;
    $id= $beneficiary->find($id);
    if($flag==0)
    	$id->e_status="Inactive";
    else
    	$id->e_status="Active";
    //delete beneficiary
    if($flag==0){
    	$msg=trans("routes.deactivemsg");
    }else{
    	$msg=trans("routes.activemsg");
    }
    $result=$id->save();
		if($result){
    		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.beneficiary").' '.$msg.'</div>');
    		return Redirect::to('/admin/beneficiary/');
    	}else{
    		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.beneficiary").' '.trans("routes.notupdatemessage").'</div>');
    		return Redirect::to('/admin/beneficiary/');
    	}
    }
    //view record
    public function view($id=0){
    	$beneficiary = new Beneficiary;
    	$data['title']=" View Beneficiary" . SITENAME;
    	
		$data['permission'] = $this->role_permissions;
    	$id=$this->decode($id);
    	$data['fieldresult']=array();
    	
    	if($id!=0){
    	//get data from database
    	$data['result']=DB::table('mct_beneficiary')
		->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
		->select('mct_beneficiary.*', 'mct_address.v_taluka','mct_address.v_village', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		->where('mct_beneficiary.bi_id',$id)
		->get();
    	
   
    	   
    	$data['result']=$data['result'][0];
	    	//print_r($data['result']->bi_field_worker_id); exit;
	    	if($data['result']->bi_field_worker_id!=""){
		    	$fieldresult=DB::table('mct_field_workers')
		    	->leftJoin('mct_address', 'mct_field_workers.i_address_id', '=', 'mct_address.bi_id')
		    	->select('mct_field_workers.*', 'mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		    	->where('mct_field_workers.bi_id',$data['result']->bi_field_worker_id)
		    	->get();
		    	$data['fieldresult']=$fieldresult[0];
	    	}
	    	
	    	if($data['result']->bi_calls_champion_id!=""){
	    		$cchampions=DB::table('mct_call_champions')
	    		->leftJoin('mct_address', 'mct_call_champions.i_address_id', '=', 'mct_address.bi_id')
	    		->select('mct_call_champions.*', 'mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
	    		->where('mct_call_champions.bi_id',$data['result']->bi_calls_champion_id)
	    		->get();
	    		$data['callchampions']=$cchampions[0];
	    	}
    	
    	
    	
    	$data['intervention']=DB::table('mct_intervention_point')->where('e_status','Active')->orderBy('i_week', 'ASC')->get();
    	
    	$data['languagedata']	= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
    	$data['duedate']		= strtotime($data['result']->dt_due_date) > 0?date('d/m/Y',strtotime($data['result']->dt_due_date)):"";
    	$data['deliverydate']	= strtotime($data['result']->dt_delivery_date) > 0?date('d/m/Y',strtotime($data['result']->dt_delivery_date)):"";
    	$data['callsummary'] 	= DB::table('mct_callchampion_report')->where('bi_beneficiary_id',$data['result']->bi_id)->orderBy('bi_id', 'DESC')->get();
    	$data['actionitem'] 	= DB::table('mct_emergency_note')->where('bi_beneficiary_id',$data['result']->bi_id)->orderBy('bi_id', 'DESC')->get();
    	$data['check']="";
    	if($data['duedate']!="")
    		$data['intecaldate']=date("Y-m-d",strtotime($data['result']->dt_due_date));
    	elseif($data['deliverydate']!="")
    		$data['intecaldate']=date("Y-m-d",strtotime($data['result']->dt_delivery_date));
    	
    	//Should be moved to the view!!
    	foreach ($data['languagedata'] as $lang){
    		if($data['result']->v_language!=""){
    			$lanarr=explode(",", $data['result']->v_language);
    			if(in_array($lang->bi_id,$lanarr))
    				$data['check'].=$lang->v_language.", ";
    		}
    	}
    	
    	/*
    	 * Get Checklist data
    	 */
    	$checklist = new Checklist;
		$data['descr'] = $checklist->getChecklistMaster();
		$data['descrBaby'] = $checklist->getChecklistBaby();
		$data['categoriesMother'] = $checklist->getCategoriesMother();
		$data['categoriesBaby'] = $checklist->getCategoriesBaby();
		$data['userChecklist'] = $checklist->getUserData($id);
		return view('beneficiary/view',$data);
    	}else{
    		return redirect('/admin/beneficiary/');
    	}
    	
	   	
  }
    
    
    //delete multiple record 
    public function deleteSelected(){
    	$checkedRowArray = Input::get('chkCheckedBox');
    	if(count($checkedRowArray)>0){
	    	$beneficiary= new Beneficiary;
	    	$idsArr = array();
	  		$data = array(
	    			'e_status' => 'Deleted'
	    	);
	    	foreach($checkedRowArray as $value){
	    		$idsArr[] = $value;
	  		}
	    	$checkedRow = implode(",",$idsArr);
	    	$result = $beneficiary->whereIn('bi_id', $idsArr)->update($data);//delete beficiary 
	    	
	    	if($result){
	    		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.beneficiary").' '.trans("routes.deletemessage").'</div>');
	    		return Redirect::to('/admin/beneficiary/');
	    	}else{
	    		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.beneficiary").' '.trans("routes.notdeletemessage").'</div>');
	    		return Redirect::to('/admin/beneficiary/');
	    	}
    	}else{
    		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.beneficiary").' '.trans("routes.notdeletemessage").'</div>');
    	}
    	return Redirect::to('/admin/beneficiary/');
    
    }
    public function decode($id=0){
    	if($id){
    		$hashids = new Hashids();
    		$arr = $hashids->decode($id);
    		if(!empty($arr)){
    			return $id=$arr[0];
    		}else{
    			return 0;
    		}
    	}else
    		return 0;
    }
    //search functin for beneficiary autocomplate
    public function autocompletebeneficiary()
    {
    	$arr=array();
    	$beneficiary= new Beneficiary;
    	$result=$beneficiary::where('v_unique_code', 'LIKE', '%'.$_GET['chars'].'%')
    	->where('e_status',"!=",'Deleted')
		->orWhere('v_name', 'LIKE', '%'.$_GET['chars'].'%')->where('e_status',"!=",'Deleted')
    	->orWhere('v_phone_number', 'LIKE', '%'.$_GET['chars'].'%')->where('e_status',"!=",'Deleted')->take(10)->get();
		//print_r($result); exit;
    	//echo $this->db->last_query();
    	if(count($result)>0){
    		foreach ($result as $val){
    			// Store data in array
    			//print_r($data);
    			if(strpos(strtolower($val->v_unique_code),strtolower($_GET['chars'])) !== false)
    			{
    				$arr[]=array("id" => $val->bi_id, "data" => $val->v_unique_code);
    			}
    			else if(strpos(strtolower($val->v_name),strtolower($_GET['chars'])) !== false)
    			{
    				$arr[]=array("id" => $val->bi_id, "data" => $val->v_name);
    			}else if(strpos(strtolower($val->v_phone_number),strtolower($_GET['chars'])) !== false)
				{
					$arr[]=array("id" => $val->bi_id, "data" => $val->v_phone_number);
				}
    		}
    	}
    
    	// Encode it with JSON format
    	echo json_encode($arr);
    }
    //get data for address autocomplate
    public function autocompleteaddress()
    {
    	$arr=array();
    	$beneficiary= new Beneficiary;
    	$result= DB::table('mct_address')->where('v_pincode', 'LIKE', '%'.$_GET['chars'].'%')->groupBy('v_pincode')->take(10)->get();
    	//print_r($result); exit;
    	//echo $this->db->last_query();
    	if(count($result)>0){
    		foreach ($result as $val){
    			// Store data in array
    			//print_r($data);
    			if(strpos(strtolower($val->v_pincode),strtolower($_GET['chars'])) !== false)
    			{
    				$arr[]=array("id" => $val->bi_id, "data" => ucwords($val->v_pincode), 'taluka'=>ucwords($val->v_taluka), 'district'=>ucwords($val->v_district), 'state'=>ucwords($val->v_state), 'country'=>ucwords($val->v_country));
    			}
    		}
    	}
    
    	// Encode it with JSON format
    	echo json_encode($arr);
    }
    //get data of beneficiary and make autocomplate
    public function autocompletebenaddress()
    {
    	$arr=array();
    	$beneficiary= new Beneficiary;
    	$result= DB::table('mct_address')->where($_GET['flag'], 'LIKE', '%'.$_GET['chars'].'%')->take(10)->groupBy($_GET['flag'])->get();
    	//print_r($result); exit;
    	//echo $this->db->last_query();
    	if(count($result)>0){
    		foreach ($result as $val){
    			// Store data in array
    			//print_r($data);
    			if(strpos(strtolower($val->$_GET['flag']),strtolower($_GET['chars'])) !== false)
    			{
    				$arr[]=array("id" => $val->bi_id, "data" => ucwords($val->$_GET['flag']));
    			}
    		}
    	}
    
    	// Encode it with JSON format
    	echo json_encode($arr);
    }
    //find date of city state taluka
    public function searchdataaddress($state="",$city="",$taluka=""){
    	$data['title']="Filter by Address" . SITENAME;
    	
    	$extr="";
    	$data['state']="";
    	$data['city']="";
    	$data['taluka']="";
    	$where=array();
    	$data['talukaarr']=array();
    	$data['cityarr']=array();
    	if($taluka!="all"){
    		$where=array("mct_address.v_taluka",$taluka);
    	}else if($city!="all"){
    		$where=array("mct_address.v_district",$city);
    	}else{
    		$where=array("mct_address.v_state",$state);
    	
    	}
    	$data['talukaarr']= DB::table('mct_address')->distinct()->select('v_taluka')->where('v_district', 'LIKE', '%'.$city.'%')->get();
    	$data['cityarr']= DB::table('mct_address')->distinct()->select('v_district')->where('v_state', 'LIKE', '%'.$state.'%')->get();
    	$data['city']=$city;
    	$data['state']=$state;
    	$data['taluka']=$taluka;
    		$data['result'] = DB::table('mct_beneficiary')->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')->where("$where[0]","=",$where[1])->where('e_status',"!=",'Deleted')->paginate(15);
    		return view('beneficiary/manage',$data);
    		
    }
    //search base on id  or name 
    public function searchdatabeneficiary($id="",$search=""){
    	$data['title']="Benificiary Search" . SITENAME;
    	$beneficiary= new Beneficiary;
    	if(isset($_GET['search']) && $_GET['search']!=""){
    		$data['result']=DB::table('mct_beneficiary')
    		->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
    		->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
    		->where('v_unique_code', 'LIKE', '%'.$_GET['search'].'%')
    		->where('e_status',"!=",'Deleted')
    		->orWhere('v_name', 'LIKE', '%'.$_GET['search'].'%')
    		->where('e_status',"!=",'Deleted')
    		->orWhere('v_phone_number', 'LIKE', '%'.$_GET['search'].'%')
    		->where('e_status',"!=",'Deleted')
    		->orderBy('bi_id', 'DESC')
    		->paginate(15);
    		$data['searchTag']=$_GET['search'];
    	}else{
    		$data['searchTag']=$search;
    		$data['result']=DB::table('mct_beneficiary')
    		->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
    		->select('mct_beneficiary.*','mct_address.v_village', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
    		->where('e_status',"!=",'Deleted')
    		->where('mct_beneficiary.bi_id',$id)
    		->orderBy('bi_id', 'DESC')
    		->paginate(15);
    	}
    	return view('beneficiary/manage',$data);
    }
    //import excel and save into database
    //get city list base on state
 	function getcitylists(){
 		$state=Input::get('state');
 		$citylist= DB::table('mct_address')->distinct()->select('v_district')->where('v_state', 'LIKE', '%'.$state.'%')->get();
 		echo json_encode($citylist,TRUE);
 	}
 	//get taluka lists base on city
 	function gettalukalists(){
 		$city=Input::get('city');
 		$citylist= DB::table('mct_address')->distinct()->select('v_taluka')->where('v_district', 'LIKE', '%'.$city.'%')->get();
 		echo json_encode($citylist,TRUE);
 	}
 	//export excel sample of benificiary
 	function downlaodsample(){
 		Excel::create('sample', function($excel) {
 			$excel->sheet('Beneficiary', function($sheet) {
 				$sheet->row(1, array(
 					'name', 
 					'language',
 					'number_pregnancies',
 					'husband_name',
 					'phone_number',
 					'alternate_phone_no',
 					'village',
 					'taluka',
 					'pincode',
 					'awc_name',
 					'awc_number',
 					'due_date',
 					'delivery_date'
 			));
 			});
 		})->download('xls');
 	}
 	//search beneficiary view
 	public function searchbenificiary(){
 		$data['title']="Benificiary Search" . SITENAME;
 		$data['result']=array();
 		return view('mtcadmin/searchbenificiary',$data);
 	}
 	//search beneficiary base on start date and end date
 	public function searchbenificiarydata(){
 		$data['title']="Benificiary Search" . SITENAME;
 		$ddate=str_replace("/", "-",Input::get('searchStartDate'));
 		$startdate=date("Y-m-d H:i:s",strtotime($ddate));
 		$devdate=str_replace("/", "-",Input::get('searchEndDate'));
 		$enddate=date("Y-m-d H:i:s",strtotime($devdate));
 		$diff=date_diff(date_create($startdate),date_create($enddate));
 		$day=$diff->format("%a");
 		$result=DB::table('mct_intervention_point')->where('e_status','Active')->get();
 		$extr="";
 		$i=0;
 		foreach ($result as $k=>$v){
 			$i++;
 			$point1=($v->i_week*7)-$day;
 			$point2=($v->i_week*7)+$day;
 			$extr.=" (( DATEDIFF('".$startdate."',dt_due_date) >".$point1." && DATEDIFF('".$startdate."',dt_due_date ) <".$point2." ) || ( DATEDIFF( '".$enddate."' ,dt_due_date) >".$point1." && DATEDIFF('".$enddate."' ,dt_due_date ) <".$point2." ))";
 			if($i < count($result))
 				$extr.=" or ";
 		}
 		//echo 'select * from mct_beneficiary where mct_beneficiary.e_status="Active" and '.$extr.'';
 		$data['result'] = DB::select('select * from mct_beneficiary where mct_beneficiary.e_status="Active" and '.$extr.'');
 		$data['title']="Benificiary Search";
 		return view('mtcadmin/searchbenificiary',$data);
 	}
 	//get address by id
 	public function getAddressById(){
 		$id= Input::get('id');
 		$zipcode= Input::get('zipcode');
 		$result=array();
 		if($id!="" && $id!=0){ 
 			$result= DB::table('mct_address')->where('bi_id', $id)->get();
 		// Encode it with JSON format
 			echo json_encode($result[0]);
 		}elseif(strlen($zipcode)==6){
 			$result= DB::table('mct_address')->where('v_pincode', $zipcode)->first();
 			echo json_encode($result);
 		}else{
 			echo json_encode($result);
 		}
 	}
 	public function checkZipcode(){
 		$zipcode= Input::get('txtZipcode');
 		$result=array();
 		$result= DB::table('mct_address')->where('v_pincode', $zipcode)->get();
 		if(count($result)>0)
 			echo "true";
 		else 
 			echo "false";
 	}
 	public function getVillageByZipcode(){
 		$zipcode= Input::get('zipcode');
 		$result=array();
 		if($zipcode!="" && strlen($zipcode)==6){
 			$result= DB::table('mct_address')->distinct()->select('v_village','v_village_pincode','bi_id')->where('v_pincode', $zipcode)->get();
 			// Encode it with JSON format
 			echo json_encode($result);
 		}else{
 			echo json_encode($result);
 		}
 	}
	public function getCallChamption(){
		$addressid= Input::get('addressid');
		$data['addressId']= $addressid;
		$data['beneficiaryId']= Input::get('beneficiaryId');
		$data['name']= Input::get('name');
		$result=array();
		$callchampion=array();
		if($addressid!=""){
			$result = DB::select('select * from mct_address where bi_id='.$addressid.'');
		}
		if($data['beneficiaryId']!=""){
			$callchampion = DB::select('select bi_calls_champion_id from mct_beneficiary where bi_id='.$data['beneficiaryId'].'');
		}
		if(count($result)>0 && count($callchampion)>0)
			$extr="IF(`mct_address`.`v_district`='".$result[0]->v_district."',2,(IF(mct_call_champions.bi_id='".$callchampion[0]->bi_calls_champion_id."',1,3)))";
		elseif(count($result)>0) 
			$extr="IF(`mct_address`.`v_district`='".$result[0]->v_district."',1,2)";
		elseif(count($callchampion)>0) 
			$extr="IF(mct_call_champions.bi_id='".$callchampion[0]->bi_calls_champion_id."',1,2)";
		else 
			$extr="mct_call_champions.bi_id desc";
		
		$data['result']=DB::select("select `mct_call_champions`.*, `mct_address`.`v_village`, `mct_address`.`v_village_pincode`,
		`mct_address`.`v_taluka`, `mct_address`.`v_pincode`, `mct_address`.`v_district`, `mct_address`.`v_state`, 
		`mct_address`.`v_country` from `mct_call_champions` left join `mct_address` on `mct_call_champions`.`i_address_id` = `mct_address`.`bi_id` where 
		`mct_call_champions`.`e_status` = 'Active' order by $extr "); 
		//$data['result']=array_unique($res);
		return view('beneficiary/callchamptionLists',$data);
	}
	public function selCallChamption(){
		$beneficiary= new Beneficiary;
		$callchampionId= Input::get('callchampionId');
		$beneficiaryId= Input::get('beneficiaryId');
		$data['name']= Input::get('name');
		$data = array(
				'bi_calls_champion_id' => $callchampionId
		);
		$result = $beneficiary->where('bi_id', $beneficiaryId)->update($data);
	} 
	public function searchCallchampion(){
		$Id= Input::get('id');
		$seach= Input::get('data');
		$data['beneficiaryId']= Input::get('beneficiaryId');
		$data['addressId']= Input::get('addressId');
		$data['name']= Input::get('name');
		if(isset($seach) && $seach!=""){
			$data['result']=DB::table('mct_call_champions')
			->leftJoin('mct_address', 'mct_call_champions.i_address_id', '=', 'mct_address.bi_id')
			->select('mct_call_champions.*','mct_address.v_village', 'mct_address.v_village_pincode', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
			->where('mct_address.v_district', 'LIKE', '%'.$seach.'%')
			->orWhere('mct_call_champions.v_name', 'LIKE', '%'.$seach.'%')
			->where('mct_call_champions.e_status', 'Active')
			->take(10)->get();
			$data['searchTag']=$seach;
		}else{
			$data['searchTag']=$search;
			$data['result']=DB::table('mct_call_champions')
			->leftJoin('mct_address', 'mct_call_champions.i_address_id', '=', 'mct_address.bi_id')
			->select('mct_call_champions.*','mct_address.v_village', 'mct_address.v_village_pincode', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
			->where('mct_call_champions.bi_id',$Id)
			->where('mct_call_champions.e_status', 'Active')
			->get();
		}
		return view('beneficiary/searchCallchampionlist',$data);
	}
	public function autoCallchampion()
	{
		$arr=array();
		$result=DB::table('mct_call_champions')
		->leftJoin('mct_address', 'mct_call_champions.i_address_id', '=', 'mct_address.bi_id')
		->select('mct_call_champions.*','mct_address.v_village', 'mct_address.v_village_pincode', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		->where('mct_address.v_district', 'LIKE', '%'.$_GET['chars'].'%')
		->orWhere('mct_call_champions.v_name', 'LIKE', '%'.$_GET['chars'].'%')
		->where('mct_call_champions.e_status', 'Active')
		->groupBy('mct_address.v_district')
		->take(10)->get();
		
		if(count($result)>0){
			foreach ($result as $val){
				// Store data in array
				//print_r($data);
				if(strpos(strtolower($val->v_name),strtolower($_GET['chars'])) !== false)
				{
					$arr[]=array("id" => $val->bi_id, "data" => $val->v_name);
				}else if(strpos(strtolower($val->v_district),strtolower($_GET['chars'])) !== false)
				{
					$arr[]=array("id" => $val->bi_id, "data" => $val->v_district);
				}
			}
		}
		echo json_encode($arr);
	}
	public function getBeneficiaryReport(){
		$reportid= Input::get('reportid');
		$res=array();
		if($reportid!="" && $reportid!=0){
			$result= DB::table('mct_emergency_note')->where('bi_id',$reportid)->orderBy('bi_id', 'DESC')->get();
			echo json_encode($result[0]);
		}else {
			echo json_encode($res);
		}
	}
	public function getCallShummery(){
		$reportid= Input::get('sumreportid');
		$res=array();
		if($reportid!="" && $reportid!=0){
			$result=DB::table('mct_callchampion_report')
			->leftJoin('mct_emergency_note', 'mct_callchampion_report.bi_id', '=','mct_emergency_note.bi_report_id')
			->select('mct_callchampion_report.*','mct_emergency_note.t_emergency_note')
			->where('mct_callchampion_report.bi_id',"=",$reportid)
			->orderBy('mct_callchampion_report.bi_id', 'DESC')
			->get();
			echo json_encode($result[0]);
		}else {
			echo json_encode($res);
		}
	}



public function updateBeneficiaryReport(){
		// Create object of Beneficiary model class
		$beneficiary = new Beneficiary;
		
		// Manage Input from Ajax request
		$reportid= Input::get('hndrepoId');
		$benId= Input::get('hndBeneficiaryId');
		$emergencynote = Input::get('txtEmergancyNote');
		$gdate=str_replace("/", "-",Input::get('txtCompletionDate'));
		$ggdate=Input::get('txtCompletionDate')!="" ?date("Y-m-d",strtotime($gdate)):"";
		$datetime = date("Y-m-d H:i:s");
		$send_email = false;
		$update=array(
			//'dt_created_at'=>$datetime,
			't_emergency_note'=>Input::get('txtEmergancyNote'),
			'dt_complated_at'=>$ggdate,
			't_emergency_comment'=>Input::get('txtComment'),
			//'ti_iscomplete' => 1
		);
		
		//Check If user can complete action
		if($this->role_permissions['cancompleteaction']){
			$update['ti_iscomplete'] = 1;
			$send_email = true;
		}
				
		if($reportid!="" && $reportid!=0){
			$beneficiary->updateReport($update,$reportid,$send_email);
		}else{
			$beneficiary->insertReport($benId,$emergencynote);
		}
	}
	
public function updateBeneficiaryCall(){
		$reportid= Input::get('hndreposumId');
		$benId= Input::get('hndBeneficiarycallId');
		$ddate=str_replace("/", "-",Input::get('txtCallDate'));
		$bdate=Input::get('txtCallDate')!="" ?date("Y-m-d",strtotime($ddate)):"";
		$datetime = date("Y-m-d H:i:s");
		$emrgnote=Input::get('txtEmergancyNoteCall');
		$update=array(
				'dt_created_at'=>$bdate,
				'e_call_status'=>Input::get('selCallStatus'),
				'v_conversation'=>Input::get('txtrepoComment'),
				'i_call_duration'=>Input::get('txtCallDuration')
		);
		if($reportid!="" && $reportid!=0){
			$res=DB::table('mct_callchampion_report')->where('bi_id', $reportid)->update($update);
			if($emrgnote!=""){
				$resemgnote = DB::select("select bi_id from mct_emergency_note where bi_report_id=$reportid");
				$updatenote=array(
						't_emergency_note'=>Input::get('txtEmergancyNoteCall'),
						'dt_updated_at'=>$datetime
				);
				if(count($resemgnote)>0){
					$res=DB::table('mct_emergency_note')->where('bi_id', $resemgnote[0]->bi_id)->update($updatenote);
				}else{
					$res = DB::select("select bi_beneficiary_id,bi_field_worker_id,bi_calls_champion_id from mct_callchampion_report where bi_id=$reportid");
					$insert=array(
							'bi_report_id'=>$reportid,
							'bi_beneficiary_id'=>$res[0]->bi_beneficiary_id,
							'bi_field_worker_id'=>$res[0]->bi_field_worker_id,
							'bi_calls_champion_id'=>$res[0]->bi_calls_champion_id,
							'dt_created_at'=>$datetime,
							't_emergency_note'=>Input::get('txtEmergancyNoteCall'),
							'dt_updated_at'=>$datetime
					);
					$ress=DB::table('mct_emergency_note')->insert($insert);
					$this->sendmailforemegency($res[0]->bi_field_worker_id,$res[0]->bi_calls_champion_id,Input::get('txtEmergancyNoteCall'));
				}
			}
		}else{
			$res = DB::select("select  bi_field_worker_id,bi_calls_champion_id from mct_beneficiary where bi_id=$benId");
			$insert=array(
					'bi_beneficiary_id'=>$benId,
					'bi_field_worker_id'=>$res[0]->bi_field_worker_id,
					'bi_calls_champion_id'=>$res[0]->bi_calls_champion_id,
					'dt_created_at'=>$bdate,
					'e_call_status'=>Input::get('selCallStatus'),
					'v_conversation'=>Input::get('txtrepoComment'),
					'i_call_duration'=>Input::get('txtCallDuration'),
					'dt_updated_at'=>$datetime
			);
			$ress=DB::table('mct_callchampion_report')->insert($insert);
			$lastid=DB::getPdo()->lastInsertId();
			if($emrgnote!=""){
				$insertemg=array(
						'bi_report_id'=>$lastid,
						'bi_beneficiary_id'=>$benId,
						'bi_field_worker_id'=>$res[0]->bi_field_worker_id,
						'bi_calls_champion_id'=>$res[0]->bi_calls_champion_id,
						'dt_created_at'=>$datetime,
						't_emergency_note'=>Input::get('txtEmergancyNoteCall'),
						'dt_updated_at'=>$datetime
				);
				$ress=DB::table('mct_emergency_note')->insert($insertemg);
				$this->sendmailforemegency($res[0]->bi_field_worker_id,$res[0]->bi_calls_champion_id,Input::get('txtEmergancyNoteCall'));
					
			}
		}
	}
	
	function sendmailforemegency($fieldworkerId,$callchampsid,$note){
		$fieldworker=DB::table('mct_field_workers')->select('v_email','v_name')->where('bi_id',$fieldworkerId)->get();
		$callchampion=DB::table('mct_call_champions')->select('v_email','v_name')->where('bi_id',$callchampsid)->get();
		$emrgdata=array(
				'fieldworker_name'=>$fieldworker[0]->v_name,
				'fieldworker_email'=>$fieldworker[0]->v_email,
				'callchampion_name'=>$callchampion[0]->v_name,
				'callchampion_email'=>$callchampion[0]->v_email,
				'emergency_note'=>$note
		);
		
		$send=Mail::send('emails.emergencynote',$emrgdata, function($message) use ($emrgdata)
		{
			$message->to($emrgdata['fieldworker_email'])->cc($emrgdata['callchampion_email'],'admin@sevasetu@org')->subject('Emergency Note of Benificiary');
		});
	}	
	
	/*
	 * Filter By Field Worker
	 */
	function filterByFW($id){
		$id = $this->decode($id);
		$data['title']="Filter By Field Worker" . SITENAME;
		
		if($id!=0){
			$beneficiary 	= new Beneficiary;
			$data['result'] = $beneficiary->filterByFW($id);
			$data['filterfwid'] = $id;
			return view('beneficiary/manage',$data);
		}else{
			return redirect('/admin/beneficiary');
		}
	}
	
	/*
	 * Filter By Call Champion
	 */
	function filterbyCC($id){
		$id = $this->decode($id);
		$data['title']="Filter By Call Champion" . SITENAME;
		if($id!=0){
			$beneficiary 	= new Beneficiary;
			$data['result'] = $beneficiary->filterbyCC($id);
			$data['filterccid'] = $id;
			return view('beneficiary/manage',$data);
		}else{
			return redirect('/admin/beneficiary');
		}
	}
	
	/*
	 * Filter by Assigned
	 */
	function filterByAssigned($param){
		if($param!=""){
		
			$beneficiary 	= new Beneficiary;
			switch($param){
				case "assigned":   $data['title']="Filter by Assgined" . SITENAME;
								   $data['result'] = $beneficiary->filterbyAssigned($param);
								   break;
				case "unassigned": $data['title']="Filter by Unassgined" . SITENAME;
							  	   $data['result'] = $beneficiary->filterbyAssigned($param);
								   break;
			}
				$data['filterassgned'] = $param;
				return view('beneficiary/manage',$data);
		}else{
			return redirect('/admin/beneficiary');
		}
	}
	
	
	/*
	 * All Beneficiary
	 */
	function all(){
		$beneficiary = new Beneficiary;
		$data['result'] = $beneficiary->getAllBeneficiary();
		return view('beneficiary/manage',$data);
	}
	
	/*
	 * Display beneficiary assigned to Current user
	 */
	function assignedBeneficiary(){
		$id = Session::get('user_logged')['user_id'];
		
		if($id!=0){
			$beneficiary 	= new Beneficiary;
			$data['result'] = $beneficiary->myAssignedBeneficiary($id);
			return view('beneficiary/manage',$data);
		}
	}
	
	/*
	 * Update Checklist of Beneficiary
	 */
	public function checklistEdit($id){
		$data['title']="Beneficiary" . SITENAME;
		
		$id=$this->decode($id);
		$data['title'] = "User Checklist";
		$data['action'] = "userchecklist";
		
		$checklist = new Checklist;
		$data['descr'] = $checklist->getChecklistMaster();
		$data['descrBaby'] = $checklist->getChecklistBaby();
		$data['categoriesMother'] = $checklist->getCategoriesMother();
		$data['categoriesBaby'] = $checklist->getCategoriesBaby();
		$data['beneficiaryid'] = $id;
		
		$data['userChecklist'] = $checklist->getUserData($id);
		
		return view('beneficiary/userchecklist',$data);
		
	}
	
	public function userchecklist(){
		if (Request::ajax()) {
			$json_array = Input::get('final');
			$beneficiaryid = Input::get('benid');
			$insert_final = array();
			$tableData = array();
			
			
			if(!empty($json_array)){
				$checklist = new Checklist;
				$result = $checklist->insertUserData($json_array,$beneficiaryid);
				if($result){
					echo "done";exit;
				}
			}
		}
	}
	
	/*  Get Data by Ajax Request */
	public function getUserCheckById(){
		if(Request::ajax()){
			$benid = Input::get('beneficiaryid');
			$checklist_id =  Input::get('chklist_id');
			$res = array();
			if($benid!="" && $benid>0){
				$checklist = new Checklist;
				$result = $checklist->getUserDataById($checklist_id,$benid);
				if(!empty($result)){
					echo json_encode($result[0]);
				}
			}else{
				echo json_encode($res);	
			}
		}
	}
	
	public function saveUserCheckById(){
		if(Request::ajax()){
			$benid 			= Input::get('beneficiary_id');
			$checklist_id 	= Input::get('checklist_id');
			$response 		= Input::get('response');
			$comment 		= Input::get('comments');
			$res = array();
			
			if($benid!="" && $benid>0){
				$checklist = new Checklist;
				$result = $checklist->saveUserDataById($checklist_id,$benid,$response,$comment);
				if($result){
					echo "success";exit;
				}else{
					echo "fail";exit;
				}
			}else{
				echo "fail";exit;
			}
		}	
	}
	

}