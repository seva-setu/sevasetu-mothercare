<?php 
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CallChampion;
use App\Models\Users;
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
					$dashboard_data = "";
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
	
	public function list_admins($cc_id = -1){		
		return view('admin/admin_dashboard');
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
		$action_items = Input::get('action_item');
		$call_stats = Input::get('callstats');
		$general_note = Input::get('general_note');
		$cc = new CallChampion;
		$update_status = $cc->update_cc_report($dueid, $call_stats, $general_note, $action_items);
		
		// ideally should be using session flashing to be doing this i think.
		return Redirect::back()->with('message',$update_status);
	}
	
////////////////////////////////////////////////

}
?>