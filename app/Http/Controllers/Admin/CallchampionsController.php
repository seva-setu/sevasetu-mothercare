<?php 
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CallChampion;
use App\Models\User;
use App\Models\DueList;
use App\Models\Beneficiary;

use Request;
use Mail;
use Auth;
use DB;
use Validator;
use Session;
use Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\UserInterface;
use Illuminate\Http\Response;
use Hashids\Hashids;
use Torann\Hashids\HashidsServiceProvider;
use Image;
use App\Http\Helpers;

class CallchampionsController extends Controller{
	protected $model;
	public $title="Call Champions";
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
			
			$user_stats = Session::has('user_stats');
			if(!$user_stats){
				if($this->role_type == 2){
					$callchamp = new CallChampion;
					$dashboard_data = $callchamp->get_dashboard_data($this->role_id);
				}			
				// If its a fieldworker
				elseif($this->role_type == 3){
					$fieldworker = new Fieldworkers;
					$dashboard_data = $fieldworker->get_dashboard_data($this->role_id);
				}
				elseif($this->role_type == 1){
					return Redirect::to('/admins')->send();
				}
				//ideally should be hashed
				$encoded_data 	= $dashboard_data;
				Session::put('user_stats', $encoded_data);
			}
		}
	}

	public function list_mothers($cc_id = -1){
		if($cc_id = -1){
			$cc_id = $this->role_id;
		}
		if($cc_id > 0){
			$due_list_obj = new DueList();
			$beneficiary_ids_list = $due_list_obj->get_beneficiary_ids_list($cc_id);						
			$b_obj = new Beneficiary();
			$data['data'] = $b_obj->get_beneficiary_details($beneficiary_ids_list);			
			return view('mothers/dashboard',$data);
		}
	}
  public function upload_data(){
    if(!Session::has('user_logged')){
      Redirect::to('/')->send();
    }
    $session_data=Session::get('user_logged');
    if($session_data['v_role']==1)
      return view('admin/upload_data');
    else
      return 'User is not admin';

  }


	
	public function update_call($due_id_encrypted){
		$helper_obj = new Helpers;
		$dueid = $helper_obj->decode($due_id_encrypted);
		$duelist = DueList::where('due_id',$dueid)->get()->toArray();
		$action_items = Input::get('action_item');
		$call_stats = Input::get('callstats');
		$general_note = Input::get('general_note');
		$duedate_stats = Input::get('duedatestat');
		$cc = new CallChampion;

		if(is_array($duelist) && !empty($duelist))
		{
			$b_id = $duelist[0]['fk_b_id'];
			$data['callchampion'] = User::where('user_id',$this->user_id)->get();
			$b_obj = new Beneficiary();
			$data['beneficiary'] = $b_obj->get_beneficiary_details($b_id);

			$email = $_ENV['MAIL_LOGIN'];
			if($call_stats == trans('routes.in'))
			{
				$data['action'] = $call_stats;
				Mail::send('emails.admin_notification',$data, 
							function($message) use($email){
								$message
								->to($email)
								->subject('Seva Setu: Admin Notifications');
							}
						  );

			}

			if($action_items != trans('routes.textareadefaulttext') && strlen($action_items)>0)
			{
				$data['action'] = trans('routes.action');
				$data['action_items'] = $action_items;
				//return view('emails.admin_action_item_notification',compact('data'));
				Mail::send('emails.admin_action_item_notification',compact('data'), 
							function($message) use($email){
								$message->to->to($email);
								$message->subject('Seva Setu: Admin Notifications');
							}
						  );
			}		

			if($duedate_stats == trans('routes.incorrect'))
			{
				$data['action'] = $duedate_stats;
				$data['expected_date'] = Input::get('duedate');

				Beneficiary::where('b_id', $b_id)
	            	->update(['reported _delivery_date' => $data['expected_date'] ]);

				Mail::send('emails.admin_notification',$data, 
							function($message) use($email){
								$message
								->to($email)
								->subject('Seva Setu: Admin Notifications');
							}
						  );

			}
		}

		$update_status = $cc->update_cc_report($dueid, $call_stats, $general_note, $action_items);		
		
		// ideally should be using session flashing to be doing this i think.
		return Redirect::back()->with('message',$update_status);
	}
	public function list_all_actions($id){
		$data=DB::table('mct_due_list')->where('fk_cc_id',$id)->get();
		$x=0;
		foreach($data as $i)
		{
			$cc_report=DB::table('mct_callchampion_report')->where('fk_due_id',$i->due_id)->first();
			if($cc_report->t_action_items!='')
			{
			$field_worker_id=DB::table('mct_beneficiary')->where('b_id',$i->fk_b_id)->first()->fk_f_id;
	        $field_worker_user_id=DB::table('mct_field_workers')->where('f_id',$field_worker_id)->first()->fk_user_id;
			$action_data[$x]['field_worker_name']=DB::table('mct_user')->where('user_id',$field_worker_user_id)->first()->v_name;
			$action_data[$x]['action_item']=$cc_report->t_action_items;
			$action_data[$x]['status']=$cc_report->status;
			$action_data[$x]['call_id']=$i->due_id;	
			$action_data[$x]['date_generated']=$i->dt_intervention_date;
			$x++;				
			}
		}
	 if($x!=0)
     {
      usort($action_data, function($a, $b)
      {
            $t1 = strtotime($a['date_generated']);
            $t2 = strtotime($b['date_generated']);
            return $t2 - $t1;
      });           
     }
     else
     {
     	$action_data[$x]['action_item']="NO ACTION ITEMS IN DATABASE";
        $action_data[$x]['field_worker_name']='';
        $action_data[$x]['date_generated']='';
        $action_data[$x]['call_id']='';
        $action_data[$x]['status']=1;
     }
	
		return view('mothers.action_items',compact('action_data'));
	}
	
////////////////////////////////////////////////

}
?>