<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Users;
use Request;
use Mail;
use Auth;
use Hash;
use DB;
use Validator;
use Session;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\UserInterface;
use Hashids\Hashids;
use PDF;
use App\Http\Helpers;

class AssignBeneficiaryController extends Controller{
	protected $model;
	public $user_id;
	public $user_type;
	public $role_id;
	public $beneficiary_details;
	protected $helper;
	protected $role_permissions;
	
	public function __construct(){
		
		$userinfo = Session::get('user_logged');
		$this->beneficiary_details 	= Session::get('user');
		
		//check for valid use
		if(!isset($userinfo['role_id'])){
			Redirect::to('/admin/')->send();
		}
		$this->user_type = $userinfo['v_role'];
		$this->user_id = $userinfo['user_id'];
		$this->role_id = $userinfo['role_id'];
		
		$this->helper = new Helpers();
		$this->role_permissions = $this->helper->checkpermission(Session::get('user_logged')['v_role']);
	}
		
	
/////////////////////////////////////////////////////////////	
//main mothode	
public function  index($id=""){
	if($id!=""){
		$this->callchampsid=$this->decode($id);
		Session::put('callchampid', $this->decode($id));
	}
	$data['title']=$this->title;
	$data['result']=array();
	//get lists of beneficiary wich assign by program coordinetor
	$startdate = date('Y-m-d',strtotime('last sunday'));
	$enddate = date('Y-m-d',strtotime('this saturday'));
	//echo $date = date("Y-m-d",strtotime('last sunday this week')).'to'.date("Y-m-d",strtotime("saturday this week"));
	$data['result']=DB::table('mct_callchampion_report')
	->leftJoin('mct_beneficiary', 'mct_callchampion_report.bi_beneficiary_id', '=', 'mct_beneficiary.bi_id')
	->select('mct_callchampion_report.bi_id as report_id','mct_callchampion_report.i_is_edit', 'mct_beneficiary.*')
	->where('mct_callchampion_report.bi_calls_champion_id',$this->callchampsid)
	->where('mct_callchampion_report.dt_created_at','>=',"$startdate")
	->where('mct_callchampion_report.dt_created_at','<=',"$enddate")
	->paginate(15);
	$data['startdate']=$startdate;
	$data['enddate']=$enddate;
	return view('assignbeneficiary/manage',$data);
}
//auto complate search of benificiary
public function autocompletebeneficiary()
{
	$arr=array();
	$result=DB::table('mct_beneficiary')->where('v_unique_code', 'LIKE', '%'.$_GET['chars'].'%')
	->where('e_status', 'Active')->where('bi_calls_champion_id', $this->callchampsid)
	->orWhere('v_name', 'LIKE', '%'.$_GET['chars'].'%')->where('e_status', 'Active')->where('bi_calls_champion_id', $this->callchampsid)
	->orWhere('v_phone_number', 'LIKE', '%'.$_GET['chars'].'%')->where('e_status', 'Active')->where('bi_calls_champion_id', $this->callchampsid)->get();
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
//search base on id  or name
public function searchdatabeneficiary($id="",$search=""){
	
	if(isset($_GET['search']) && $_GET['search']!=""){
		$data['result']=DB::table('mct_callchampion_report')
		->leftJoin('mct_beneficiary', 'mct_callchampion_report.bi_beneficiary_id', '=', 'mct_beneficiary.bi_id')
		->select('mct_callchampion_report.bi_id as report_id','mct_callchampion_report.i_is_edit', 'mct_beneficiary.*')
		->where('e_status', 'Active')
		->where('mct_callchampion_report.bi_calls_champion_id', $this->callchampsid)
		->Where(function($query)
		{
			$query->orWhere('mct_beneficiary.v_name', 'LIKE', '%'.$_GET['search'].'%')
			->orWhere('mct_beneficiary.v_phone_number', 'LIKE', '%'.$_GET['search'].'%')
			->orWhere('mct_beneficiary.v_unique_code', 'LIKE', '%'.$_GET['search'].'%');
		})
		->paginate(15);
		
		$data['searchTag']=$_GET['search'];
	}else{
		$data['searchTag']=$search;
		$data['result']=DB::table('mct_callchampion_report')
		->leftJoin('mct_beneficiary', 'mct_callchampion_report.bi_beneficiary_id', '=', 'mct_beneficiary.bi_id')
		->select('mct_callchampion_report.bi_id as report_id','mct_callchampion_report.i_is_edit', 'mct_beneficiary.*')
		->where('mct_callchampion_report.bi_calls_champion_id',$this->callchampsid)->where('mct_beneficiary.bi_id', $id)
		->paginate(15);
	}
	return view('assignbeneficiary/manage',$data);
}
public function update(){
	$date=date('Y-m-d');
	$reportId = Input::get('hdnId');
	$fieldworkerId = Input::get('hdnFieldId');
	if(Input::get('txtEmergencyNote')!=""){
		$fieldworker=DB::table('mct_field_workers')->select('v_email','v_name')->where('bi_id',$fieldworkerId)->get();
		$callchampion=DB::table('mct_call_champions')->select('v_email','v_name')->where('bi_id',$this->callchampsid)->get();
		$this->email;
		$emrgdata=array(
			'fieldworker_name'=>$fieldworker[0]->v_name,
			'fieldworker_email'=>$fieldworker[0]->v_email,
			'callchampion_name'=>$callchampion[0]->v_name,
			'callchampion_email'=>$callchampion[0]->v_email,
			'emergency_note'=>Input::get('txtEmergencyNote')	
		);
		
		$send=Mail::send('emails.emergencynote',$emrgdata, function($message) use ($emrgdata)  
		{
			$message->to($emrgdata['fieldworker_email'])->cc($emrgdata['callchampion_email'],'admin@sevasetu@org')->subject('Emergency Note of Benificiary');
		});
	}
	$data = array(
    	'v_befor_call' => Input::get('txtBeforCall'),
		'v_conversation' =>Input::get('txtConversation'),
		'i_call_duration'=>Input::get('txtCallDuration'),
		't_emergency_note'=>Input::get('txtEmergencyNote'),
		'i_is_edit'=>1,
		'dt_updated_at'=>$date	
    );
    $result = DB::table('mct_callchampion_report')->where('bi_id', $reportId)->update($data);//delete beficiary 
    if($result){
    	Session::flash('message', '<div class="alert alert-success" style="clear:both;">
        <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.reportben").' '.trans("routes.updatemessage").'</div>');
    	return Redirect::to('/admin/assignbeneficiary/');
    }else{
    	Session::flash('message', '<div class="alert alert-success" style="clear:both;">
        <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.reportben").' '.trans("routes.updatemessage").'</div>');
    	return Redirect::to('/admin/assignbeneficiary/');
    }
}
public function searchbenificiarydata($startdate){
	$startdate=date("Y-m-d",strtotime($startdate));
	$custom_date = strtotime( date('d-m-Y', strtotime($startdate)) );
	$devdate = date('d-m-Y', strtotime('this saturday', $custom_date));
	//$devdate=str_replace("/", "-",Input::get('endDate'));
	$enddate=date("Y-m-d",strtotime($devdate));
	//echo $startdate."-".$enddate; exit;
	$data['startdate']=$startdate;
	$data['enddate']=$enddate;
	$data['result']=DB::table('mct_callchampion_report')
		->leftJoin('mct_beneficiary', 'mct_callchampion_report.bi_beneficiary_id', '=', 'mct_beneficiary.bi_id')
		->select('mct_callchampion_report.bi_id as report_id','mct_callchampion_report.i_is_edit', 'mct_beneficiary.*')
		->where('mct_callchampion_report.bi_calls_champion_id',$this->callchampsid)
		->where('mct_callchampion_report.dt_created_at','>=',"$startdate")
		->where('mct_callchampion_report.dt_created_at','<=',"$enddate")
		->paginate(15);
	return view('assignbeneficiary/manage',$data);
}

//edit report detail
public function edit($reportId){
	$reportId=$this->decode($reportId);
	//get detail of benificary
	$data['result']=DB::table('mct_callchampion_report')
	->leftJoin('mct_beneficiary', 'mct_callchampion_report.bi_beneficiary_id', '=', 'mct_beneficiary.bi_id')
	->leftJoin('mct_field_workers', 'mct_callchampion_report.bi_field_worker_id', '=', 'mct_field_workers.bi_id')
	->select('mct_callchampion_report.bi_id as report_id','mct_callchampion_report.v_befor_call','mct_callchampion_report.i_is_edit','mct_callchampion_report.v_conversation','mct_callchampion_report.i_call_duration', 'mct_beneficiary.*','mct_field_workers.v_name as field_worker_name','mct_field_workers.v_phone_number as field_worker_number')
	->where('mct_callchampion_report.bi_id',$reportId)
	->get();
	$data['result']=$data['result'][0];
	//print_r($data); exit;
	return view('assignbeneficiary/form',$data);
}
//view report detail
public function view($reportId){
	$reportId=$this->decode($reportId);
	//get detail of benificary
	$data['result']=DB::table('mct_callchampion_report')
	->leftJoin('mct_beneficiary', 'mct_callchampion_report.bi_beneficiary_id', '=', 'mct_beneficiary.bi_id')
	->leftJoin('mct_field_workers', 'mct_callchampion_report.bi_field_worker_id', '=', 'mct_field_workers.bi_id')
	->select('mct_callchampion_report.bi_id as report_id','mct_callchampion_report.dt_created_at as creat_date','mct_callchampion_report.t_emergency_note','mct_callchampion_report.v_befor_call','mct_callchampion_report.i_is_edit','mct_callchampion_report.v_conversation','mct_callchampion_report.i_call_duration', 'mct_beneficiary.*','mct_field_workers.v_name as field_worker_name','mct_field_workers.v_phone_number as field_worker_number')
	->where('mct_callchampion_report.bi_id',$reportId)
	->get();
	$data['result']=$data['result'][0];
	//print_r($data); exit;
	return view('assignbeneficiary/view',$data);
}
public function decode($id=0){
	if($id){
		$hashids = new Hashids();
		$arr = $hashids->decode($id);
		return $id=$arr[0];
	}else
		return 0;
}
public function searchdataaddress($state="",$city="",$taluka="",$startdate="",$enddate=""){
	$startdate = date('Y-m-d',strtotime($startdate));
	$enddate = date('Y-m-d',strtotime($enddate));
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
		$data['talukaarr']= DB::table('mct_address')->distinct()->select('v_taluka')->where('v_district', 'LIKE', '%'.$city.'%')->get();
		$where=array("mct_address.v_district",$city);
	}else{
		$where=array("mct_address.v_state",$state);
		$data['cityarr']= DB::table('mct_address')->distinct()->select('v_district')->where('v_state', 'LIKE', '%'.$state.'%')->get();
			
	}
	$data['city']=$city;
	$data['state']=$state;
	$data['taluka']=$taluka;
	$data['result'] = DB::table('mct_callchampion_report')
	->leftJoin('mct_beneficiary', 'mct_callchampion_report.bi_beneficiary_id', '=', 'mct_beneficiary.bi_id')
	->leftJoin('mct_address', 'mct_beneficiary.i_address_id', '=', 'mct_address.bi_id')
	->select('mct_callchampion_report.bi_id as report_id','mct_callchampion_report.i_is_edit', 'mct_beneficiary.*')
	->where('mct_callchampion_report.bi_calls_champion_id',$this->callchampsid)
	->where('mct_callchampion_report.dt_created_at','>=',"$startdate")
	->where('mct_callchampion_report.dt_created_at','<=',"$enddate")
	->where("$where[0]","=",$where[1])
	->paginate(15);
	$data['startdate'] = $startdate;
	$data['enddate'] = $enddate;
	return view('assignbeneficiary/manage',$data);
}
public function downlaodreport(){
	//print_r(Input::get('hdnRepId'));
	$checkedRowArray = explode(",", Input::get('hdnRepId'));
	$startdate=str_replace("/", "-",Input::get('hdnRepStart'));
	$enddate=str_replace("/", "-",Input::get('hdnRepEnd'));
	$idsArr = array();
	foreach($checkedRowArray as $value){
		$idsArr[] = $value;
	}
	$user = DB::table('mct_call_champions')->where('bi_id', $this->callchampsid)->get();
	$data['callchamps']=$user[0];
	$data['result']=DB::table('mct_callchampion_report')
	->leftJoin('mct_beneficiary', 'mct_callchampion_report.bi_beneficiary_id', '=', 'mct_beneficiary.bi_id')
	->leftJoin('mct_field_workers', 'mct_callchampion_report.bi_field_worker_id', '=', 'mct_field_workers.bi_id')
	->select('mct_callchampion_report.bi_id as report_id','mct_callchampion_report.v_befor_call','mct_callchampion_report.i_week','mct_callchampion_report.v_conversation','mct_callchampion_report.i_call_duration', 'mct_beneficiary.*','mct_field_workers.v_name as field_worker_name','mct_field_workers.v_phone_number as field_worker_number')
	->whereIn('mct_callchampion_report.bi_id',$idsArr)
	->get();
	$data['intervatiopoin']=DB::table('mct_intervention_point')->where('e_status','Active')->get();
	$pdf = PDF::setPaper('a4')->setOrientation('landscape')->loadView('emails.sendreport', $data);
	return $pdf->download('report_'.$startdate.'_to_'.$enddate.'.pdf');
	
}
}
?>