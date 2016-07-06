<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use Auth;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Helpers;
use Request;
use App\Models\Checklist;
use Input;
use Validator;
use App\Models\App\Models;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View;
use PDF;
use Excel;
use Cache;

class WeeklyCalllistController extends Controller{
	public $userid;
	public $usertype;
	protected $helper;
	protected $role_permissions;
	
	public function __construct(){
		$userinfo=Session::get('user_logged');
		//check for valid use
		if(!isset($userinfo['b_id'])){
			Redirect::to('/admin/')->send();
		}
		$this->usertype=$userinfo['v_role'];
		$this->userid=$userinfo['b_id'];
		
		$this->helper = new Helpers();
		$this->role_permissions = $this->helper->checkpermission(Session::get('user_logged')['v_role']);
	}

	/*
	 * Weekly Call List
	 */
	public function index(){
		if($this->role_permissions['canweeklyreport']){
			
			
			$data['morebutton'] = "none";
			$startdate = date('d-m-Y',strtotime('last sunday', time()));
			
			return $this->searchbenificiarydata($startdate);
		}else{
			return Redirect::to('/admin/');
		}
	}
	
	public function searchbenificiarydata($startdate){
		$data['title'] = "Weekly Call List" . SITENAME;
		//Check permission of user
		if($this->role_permissions['canweeklyreport']){
		
			$userinfo=Session::get('user_logged');
			$callchampion_id = $userinfo['v_role'];
				
			if($userinfo['v_role']==2){
				$callchampion_id =  $userinfo['user_id'];
			}else{
				$callchampion_id = 0;
			}
			
			$startdate=date("Y-m-d",strtotime($startdate));
			$custom_date = strtotime( date('d-m-Y', strtotime($startdate)) );
			$devdate = date('d-m-Y', strtotime('this saturday', $custom_date));
			$enddate=date("Y-m-d",strtotime($devdate));
	
			$data['startdate']	=	$startdate;
			$data['enddate']	=	$enddate;
			
			$beneficiary = new Beneficiary;
			
			
			//Get Min Max intervation numbers
			$data['intervations'] = $beneficiary->getInterventionPoints();
			$data['assigned_beneficiary'] = $beneficiary->getAssignedBeneficiary($callchampion_id);

			$ben_data =  $beneficiary->checkBetween($data['assigned_beneficiary'],$data['intervations'],$startdate,$enddate);
			$data['count'] = count($ben_data);
			$data['perPage'] = 15; //Change this for number of records retrived at one time
		
			Cache::forever('ben_data', array_chunk($ben_data , $data['perPage']));
			Cache::forever('count',$data['count']);

			if($data['count']>$data['perPage']){
				$data['morebutton'] = "block";
			}else{
				$data['morebutton'] = "none";
			}
	
			if(!empty($ben_data)){
				return view('weeklyreport.manage',$data)->with('result', Cache::get('ben_data')[0]);
			}else{
				return view('weeklyreport.manage',$data);
			}
		}else{
			return Redirect::to('/admin/');
		}
		
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