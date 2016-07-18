<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use Auth;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Helpers;
use Request;
use App\Models\Checklist;
use App\Models\DueList;
use Input;
use Validator;
use App\Models\App\Models;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View;
use Hashids\Hashids;
use PDF;
use Excel;
use Cache;
use DB;

class WeeklyCalllistController extends Controller{
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

	/*
	 * Weekly Call List
	 */
	 
	 
	public function index(){
		if($this->role_permissions['canweeklyreport']){
			return $this->list_all_calls();
		}else{
			return Redirect::to('/admin/');
		}
	}
	
	public function get_master_checklist(){
		$table_name = 'mct_checklist_master';
		$select['checklist_master'] = DB::table($table_name)
			->select('*')
			->get();
		return view('checklist/list',$select);
	}
	
	public function list_all_calls(){
		$due_list_obj = new DueList;
		$return_info = $due_list_obj->get_due_list_callchamp($this->role_id);				
		return view('mycalls.dashboard',$return_info);
	}
	
	public function list_specific_call_details($due_id_encoded){
		$due_id = $this->decode($due_id_encoded);
		$due_list_obj = new DueList;
		$due_id_obj = $due_list_obj->find($due_id);
		
		// ACTION_ITEM: Need exception handling here
		$beneficiary_id = $due_id_obj['attributes']['fk_b_id'];
		
		$b_obj = new Beneficiary;
		$beneficiary_details = $b_obj->get_beneficiary_details($beneficiary_id);
		$beneficiary_details = $beneficiary_details[0];
		$beneficiary_details->due_id = $due_id;
		
		$call_details = $due_list_obj->get_due_list_callchamp($due_id_obj['attributes']['fk_cc_id'], $beneficiary_id);
		
		$action_item_id = $due_id_obj['attributes']['fk_action_id'];
		$action_items = $due_list_obj->get_checklist_items($action_item_id);
		
		
		$data['personal_details'] = $beneficiary_details;
		$data['due_list_completed'] = $call_details['due_list_completed'];
		$data['due_list_scheduled'] = $call_details['due_list_scheduled'];
		$data['action_items'] = $action_items;
		
		if(false)
			return(view('mycalls.details',$data));
		else
			return(view('mycalls.details',$data));
	}
	
	public function submit_data(){
		print_r(Input::all());
	}
	
	public function decode($id=0){
  		if($id){
  			$hashids = new Hashids();
  			$arr = $hashids->decode($id);
  			return (!empty($arr)) ? $id=$arr[0] : 0;
  			//return $id=$arr[0];
  		}else
  			return 0;
  	}
	
	
	public function showMoreCallList()
	{
		$currentPage =  intval(Input::get('currentPage'));
		$count = Cache::get('count');
		if($currentPage <= $count - 1){
			return view('weeklyreport.filterajax')->with('result', Cache::get('ben_data')[$currentPage]);
		}else{
			echo "no records";
		}
	}
	
	
	/* Download Report */
	public function DownloadReport(){
		$dataArray = array();
		$checkedRowArray = Input::get('chkCheckedBox');
		
		if($checkedRowArray!=""){
			$ftype = trim(Input::get('ftype'));
		
	
			foreach(Cache::get('ben_data') as $dt){
			 	foreach($dt as $d){
				 	   if(in_array($d['bi_id'],$checkedRowArray)){
					   		
					   		array_push($dataArray,$d);
					   }
				}
			}
			
			if($ftype=="pdf"){	
				/* PDf */
				$pdf = PDF::setPaper('a4')->setOrientation('landscape')->loadView('weeklyreport.download_template', ['result' => $dataArray]);
				return $pdf->download('Weekly_Call_List_'.date('d/m/Y').'.pdf');
			}else{
				/* Excel */
				Excel::create('WeeklyCallList_'.date('d/m/Y'), function($excel) use($dataArray) {
					$excel->sheet('Beneficiary', function($sheet) use($dataArray) {
							$sheet->loadView('weeklyreport.exceldownload_template', ['result' => $dataArray]);
					});
	
				})->download('xls');
			
			}
		}
	}
}