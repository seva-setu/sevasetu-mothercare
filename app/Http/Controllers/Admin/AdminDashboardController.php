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
					   ->select('*')
					   ->where('e_call_status','=','not called')
					   ->join('mct_call_champions','mct_call_champions.cc_id','=','mct_due_list.fk_cc_id')
					   ->join('mct_user','mct_user.user_id','=','mct_call_champions.fk_user_id')
					   ->select('cc_id as ID','v_name as name','i_phone_number as phno','reminder_status','due_id')
					   ->get();
		//var_dump($cc_id_not_called);
		$count_scheduled_calls = DB::table('mct_callchampion_report')
					   ->join('mct_due_list','mct_callchampion_report.fk_due_id','=','mct_due_list.due_id')
					   ->select('fk_cc_id')
					   ->where('e_call_status','=','not called')
					   ->count();
		/*  $select = array(); //will contain names of call champions who did make the scheduled calls
		foreach ($cc_id_not_called as $val){
		$select[] = DB::table('mct_user')
		          ->join('mct_call_champions','mct_user.user_id','=','mct_call_champions.fk_user_id')
		          ->select('cc_id as ID','v_name as name','i_phone_number as phno')
		          ->where('cc_id','=',$val->fk_cc_id)
		          ->get();
		         
		}*/
		
		$data = Array();
		$data['total_calls'] = $count_scheduled_calls;
		$data['cc_not_called'] = $cc_id_not_called;
		//var_dump($data['cc_not_called']); 

		return $data;
		
		
		
	}
	
	public function calls_lastweek(){
		
		//'$select' contains details of calls scheduled in the past week
		$date1 = DB::select('SELECT CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY as start');
		$date2 = DB::select('SELECT CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY as end');
		//var_dump($date1[0]->start);
		//var_dump($date2[0]->end);
		$datestart = strtotime($date1[0]->start);
		$dateend = strtotime($date2[0]->end);
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
		
		//var_dump($select);
		
		
		/* $select2 = Array();
		foreach($select as $val)
		{
			$select2[] = DB::table('mct_beneficiary')
			->join('mct_due_list','mct_beneficiary.b_id','=','mct_due_list.fk_b_id')
			->select('v_name')
			->where('b_id','=',$val->fk_b_id)
			->get();
		} */
		
	   $select2 = DB::select('SELECT *,mct_beneficiary.v_name as b_name,mct_user.v_name as c_name from (Select * from mct_due_list 
	   		WHERE dt_intervention_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY 
				AND dt_intervention_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY)t1
	   		join mct_beneficiary on mct_beneficiary.b_id=t1.fk_b_id
	   		join mct_call_champions on mct_call_champions.cc_id=t1.fk_cc_id
	   		join mct_user on mct_call_champions.fk_user_id=mct_user.user_id');
		
		//var_dump($select2);
		
	   /* $averagePerMother = DB::select('SELECT (select count(*) FROM mct_callchampion_report 
	   		                           WHERE dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY 
	   		                           AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY) / 
	   		                           (select count(distinct fk_due_id) FROM mct_callchampion_report
	   		                           WHERE dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY 
	   		                           AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY) as Average'); */
	   $averagePerMother = DB::select('SELECT (select count(*) FROM mct_callchampion_report
	   		                           WHERE dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY
	   		                           AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY) /
	   		                           (select count(distinct fk_b_id)as count from mct_due_list
				                     join (SELECT distinct(fk_due_id) as id
				                     FROM mct_callchampion_report
				                     where dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY
				                     AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY) t1
				                      on t1.id = mct_due_list.due_id) as Average');
		$totalCalls = DB::select('select count(*) as count FROM mct_callchampion_report 
				                  WHERE dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY 
				                  AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY');
		$callsAttemptEqual1 = DB::select('select count(*) as count from 
				                          ( SELECT COUNT(fk_due_id) as c from mct_callchampion_report
				                           WHERE dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY
				                           AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY 
				                           group by fk_due_id) t1 
				                           where t1.c = 1');
		$callsAttemptEqual2 = DB::select('select count(*) as count from
				                          ( SELECT COUNT(fk_due_id) as c from mct_callchampion_report
				                           WHERE dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY
				                           AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY
				                           group by fk_due_id) t1
				                           where t1.c = 2');
		$callsAttemptGT2 = DB::select('select count(*) as count from
				                          ( SELECT COUNT(fk_due_id) as c from mct_callchampion_report
				                           WHERE dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY
				                           AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY
				                           group by fk_due_id) t1
				                           where t1.c > 2');
		$incorrectphno = DB::select('select count(distinct fk_b_id) as count from mct_due_list 
				                     join (SELECT distinct(fk_due_id) as id 
				                     FROM mct_callchampion_report 
				                     where e_call_status = "Incorrect number" 
				                     and dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY
				                     AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY) t1 
				                      on t1.id = mct_due_list.due_id');
		$notReachable = DB::select('select count(distinct fk_b_id) as count from mct_due_list
				                     join (SELECT distinct(fk_due_id) as id
				                     FROM mct_callchampion_report
				                     where e_call_status = "Not reachable"
				                     and dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY
				                     AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY) t1
				                      on t1.id = mct_due_list.due_id');
		$mothersAssigned = DB::select('select count(distinct fk_b_id)as count from mct_due_list
				                     join (SELECT distinct(fk_due_id) as id
				                     FROM mct_callchampion_report
				                     where dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY
				                     AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY) t1
				                      on t1.id = mct_due_list.due_id');
		$actionItems = DB::select('SELECT count(distinct fk_action_id) as count 
				                   FROM mct_callchampion_report join mct_due_list on due_id = fk_due_id 
				                   WHERE dt_modify_date >= CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)+6 DAY 
				                   AND dt_modify_date < CURRENT_DATE - INTERVAL DAYOFWEEK(CURRENT_DATE)-1 DAY');
		$incorrectDeliveryDate = DB::select('SELECT count(*)as count FROM `mct_beneficiary` WHERE dt_due_date <> `reported _delivery_date`');
		/*  var_dump($totalCalls);
		var_dump($callsAttemptEqual1);
		var_dump($callsAttemptEqual2);
		var_dump($callsAttemptGT2);
		var_dump($averagePerMother); 
		var_dump($incorrectphno);
		var_dump($mothersAssigned); */
		
		$data = Array();
		$data['datestart'] = $datestart;
		$data['dateend'] = $dateend;
		$data['call_details'] = $select2;
		$data['received_calls'] = $select1;
		$data['totalcalls'] = $totalCalls;
		$data['callsattemptequal1'] = $callsAttemptEqual1;
		$data['callsattemptequal2'] = $callsAttemptEqual2;
		$data['callsattemptgt2'] = $callsAttemptGT2;
		$data['averagepermother'] = $averagePerMother;
		$data['incorrectphno'] = $incorrectphno;
		$data['notreachable'] = $notReachable;
		$data['mothersassigned'] = $mothersAssigned;
		$data['incorrectdeliverydate'] = $incorrectDeliveryDate;
		$data['actionitems'] = $actionItems;
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