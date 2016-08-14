<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fieldworkers;
use App\Models\Admin;

//use App\Models\Callchampions;
//use App\Models\Users;

use App\Models\User;
use App\Models\CallChampion;
use App\Services\Registrar;
//use Request;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\UserInterface;
use Illuminate\Http\Response;

use Mail;
use Hash;
use Auth;
use DB;
use Validator;
use Session;
use Hashids\Hashids;
use Image;
use App\Http\Helpers;



class AdminDashboardController extends Controller {
	
	
	
	
	public function __construct(){
	}
	
	public function get_callchampions_not_called() {
		
		
		$cc_id_not_called = DB::table('mct_callchampion_report')
					   ->join('mct_due_list','mct_callchampion_report.fk_due_id','=','mct_due_list.due_id')
					   ->select('fk_cc_id')
					   ->where('e_call_status','=','not called')
					   ->distinct()
					   ->get();
		$count_scheduled_calls = DB::table('mct_callchampion_report')
					   ->join('mct_due_list','mct_callchampion_report.fk_due_id','=','mct_due_list.due_id')
					   ->select('fk_cc_id')
					   ->where('e_call_status','=','not called')
					   ->count();
		 $select = array(); //will contain names of call champions who did make the scheduled calls
		foreach ($cc_id_not_called as $val){
		$select[] = DB::table('mct_user')
		          ->join('mct_call_champions','mct_user.user_id','=','mct_call_champions.fk_user_id')
		          ->select('cc_id as ID','v_name as name')
		          ->where('cc_id','=',$val->fk_cc_id)
		          ->get();
		         
		}
		$data = Array();
		$data['total_calls'] = $count_scheduled_calls;
		$data['cc_not_called'] = $select;
		

		return $data;
		
		
		
	}
	
	public function calls_lastweek(){
		
		//'$select' contains details of calls scheduled in the past week
		$select = DB::select('SELECT * FROM `mct_due_list` 
				WHERE dt_intervention_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY 
				AND dt_intervention_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY');
		
		$select1 = Array(); //'$select1' will contain details of 'Received' calls in the past week
		
		foreach($select as $val)
		{
			$select1[] = DB::table('mct_callchampion_report')
			->join('mct_due_list','mct_callchampion_report.fk_due_id','=','mct_due_list.due_id')
			->select('*')
			->where('due_id','=',$val->due_id)
			->where('e_call_status','=','Received')
			->get();
		}
		
		$select1 = array_filter($select1);
		
		
		$data = Array();
		$data['call_details'] = $select;
		$data['received_calls'] = $select1;
		return $data;
				  
	}
	
	public function actionitems_lastweek(){
		
		$select = DB::select('SELECT * FROM `mct_callchampion_report`
				WHERE dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY
				AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY');
		
		$select1 = Array(); //will contain details of action items scheduled in the past week
		
		 foreach($select as $val)
		{
			$select1[] = DB::table('mct_due_list')
						 ->select('*')
						 ->where('due_id','=',$val->fk_due_id)
						 ->get();
		} 
		$data = Array();
		$data['action_items'] = $select;
		
		return ($data);
		
	}
	public function get_data(){
		$data1 = $this->get_callchampions_not_called();
		$data2 = $this->calls_lastweek();
		$data3 = $this->actionitems_lastweek();
		$data = array_merge($data1,$data2,$data3);
		
		return view('analysis/dashboard',$data);
	}
	
	
	
	
			
	
	
}
?>