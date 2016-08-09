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
	
	public function update_call($due_id_encrypted){
		$helper_obj = new Helpers;
		$dueid = $helper_obj->decode($due_id_encrypted);
		$duelist = DueList::where('due_id',$dueid)->get();
		$action_items = Input::get('action_item');
		$call_stats = Input::get('callstats');
		$general_note = Input::get('general_note');
		$duedate_stats = Input::get('duedatestat');
		$cc = new CallChampion;

		if(sizeof($duelist))
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
				Mail::send('emails.admin_notification',$data, 
							function($message) use($email){
								$message
								->to($email)
								->subject('Seva Setu: Admin Notifications');
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
	
////////////////////////////////////////////////

}
?>