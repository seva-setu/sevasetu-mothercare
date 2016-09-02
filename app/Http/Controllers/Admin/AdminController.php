<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BeneficiaryController;

use App\Models\Fieldworkers;
use App\Models\Admin;

//use App\Models\Callchampions;
//use App\Models\Users;
use Carbon\Carbon;
use App\Models\User;
use App\Models\DueList;

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

class AdminController extends Controller{
	protected $model;
	public $title="Admin Login";
	public $user_id;
	public $role_id;
	public $user_role_type;
	protected $helper;
	protected $role_permissions;
	
	public function __construct(){
    if(Session::has('user_logged')){
      $userinfo=Session::get('user_logged');
      $this->user_role_type=$userinfo['v_role'];}

	}
	
	public function landing(){
		if(Session::has('user_logged')){
      if($this->user_role_type == 1)
        return Redirect::to('/admins')->send();
      elseif($this->user_role_type == 2)
        Redirect::to('/mothers')->send();
		}
		
		$mothers = DB::table('mct_beneficiary')->count('b_id');
		$cc = DB::table('mct_call_champions')->count('cc_id');
		$calls = DB::table('mct_due_list')->count('due_id');
		
		$data['calls'] = 25000+$calls*10/2; // Each call champion has 5 calls on an average which lasts for 10 minutes each
		$data['mothers'] = 1042+$mothers;
		$data['cc'] = 48+$cc;
		
		return view('welcome',$data);
	}
	
	public function index(){
		if(Session::has('user_logged')){			       
    if($this->user_role_type == 1)
      return Redirect::to('/admins')->send();
    elseif($this->user_role_type == 2)
      Redirect::to('/mothers')->send();
		}

		$data['title']= "Login";
		return view('admin/login',$data);
	}
	
	/*
	 * User Login for Dashboard
	 */
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

 	public function login() {
		if(Session::has('user_logged')){
      if($this->user_role_type == 1)
        return Redirect::to('/admins')->send();
      elseif($this->user_role_type == 2)
        Redirect::to('/mothers')->send();
		}
		// Getting all post data
		$users=new User;
		$userdata = array(
				'email' => Input::get('txtUserName'),
				'password' => Input::get('txtPassword')
			  );
		// Applying validation rules.
		$rules = array(
			'email' => 'required|email',
			'password' => 'required|min:6',
			 );
		
		$validator = Validator::make($userdata, $rules);
		if ($validator->fails()){
			return Redirect::to('/login')->withErrors($validator);
		}else{
			$validlogin = $users->validate_login($userdata);
			if($validlogin){
				$user_details = Session::get('user_logged');
				$user_id = $user_details['user_id'];
        $user_role_type = $user_details['v_role'];
				$inputData['dt_last_login'] = date("Y-m-d H:i:s");
				$user_obj = new User;
				$user_obj = $user_obj->mod_user($inputData, $user_id);
				if($user_role_type == 1)
          return Redirect::to('/admins');
        elseif($user_role_type == 2)
          return Redirect::to('/mothers');
			}else{
				Session::flash('message', trans("routes.loginerror"));
				return Redirect::to('/login');
			}
		}
    }

  /*
  Admin dashboard for backend functions.
  @returns admin dashboard view
  */
  public function admin_dashboard(){
    // if user is a callchampion and trying to access this url redirect to /mothers
    if($this->user_role_type == 2)
          return Redirect::to('/mothers');

    return view('admin/admin_dashboard');
  }





  /*
  Callchampion tab in admin dashboard.
  @returns admin/callchampion view with some related data
  @calling_method invoked by admin_dashboard view
  @algorithm
    collects all required data for callchampion view in admin dashboard and return view with data
  */
  public function callChampions(){

    // if user is a callchampion and trying to access this url redirect to /mothers
    if($this->user_role_type == 2)
          return Redirect::to('/mothers');

    //all active callchampions who have already assigned to some mothers
    $data['all']=DB::table('mct_call_champions')
                        ->join('mct_user', 'user_id', '=', 'fk_user_id')
                        ->where('mct_call_champions.activation_status',2) 
                        ->whereRaw('cc_id in (select fk_cc_id from mct_due_list)')
                        ->get();

    //callchampions who just got onboard still to be approved
    $data['unapproved']=DB::table('mct_call_champions')
                        ->join('mct_user', 'user_id', '=', 'fk_user_id')
                        ->where('mct_call_champions.activation_status',0)
                        ->get();

    //list of all callchampions who can shadow unapproved callchampions
    $data['mentors']=CallChampion::join('mct_user', 'user_id', '=', 'fk_user_id')
                      ->where('mct_call_champions.activation_status',2)
                      ->get();

    
    //unapproved callchampions who are learning the the processs
    $data['mentees']=DB::table('mct_callchampion_shadow')
                        ->join('mct_call_champions', 'cc_id', '=', 'mentee')
                        ->join('mct_user', 'user_id', '=', 'fk_user_id')
                        ->where('mct_call_champions.activation_status',1)
                        ->get();
    //approved callchampions who have completed shadowing process but yet to assign any beneficiary
    $data['unassigned']=DB::table('mct_call_champions')
                        ->join('mct_user', 'user_id', '=', 'fk_user_id')
                        ->where('mct_call_champions.activation_status',2) 
                        ->whereRaw('cc_id not in (select fk_cc_id from mct_due_list)')
                        ->get();

    //list of mentors corressponding to every mentee who are shadowing them 
    $data['mentor']=array();
    foreach ($data['mentees'] as $value)
    {
      $data['mentor'][] =  DB::table('mct_callchampion_shadow')
                            ->join('mct_call_champions', 'cc_id', '=', 'mentor')
                            ->join('mct_user', 'user_id', '=', 'fk_user_id')
                            ->where('mentee',$value->cc_id)
                            ->get();

    }

    return view('admin/callchampions',$data);
  }
  /*
  Action Items tab in admin dashboard, that notifies admin about all the action items requested by champions
  @returns admin/action_items view with all the data like action item , field worker associated with that beneficiary, date, call_id,champion associated.
  @calling_method invoked by admin_dashboard view
  @algorithm
  1.) fetch data like action items and other data related with that.
  2.) sort entries w.r.t date.
  */

  public function action_items(){
    $data=DB::table('mct_callchampion_report')->get();//->where('status',0)->get();
     $x=0;
     $alread_resolved=0;
     foreach($data as $i)
     {
      //status=1 implies that action items are already resolved.
        if($i->status==1)
        {
          $alread_resolved++;
        }
        $due_id=DB::table('mct_due_list')->where('due_id',$i->fk_due_id)->first();
        $cc_id=DB::table('mct_due_list')->where('due_id',$i->fk_due_id)->first()->fk_cc_id;
        //$newdata[$x]['b_id']=$due_id->fk_b_id;
        $field_worker_id=DB::table('mct_beneficiary')->where('b_id',$due_id->fk_b_id)->first()->fk_f_id;
        $cc_user_id=DB::table('mct_call_champions')->where('cc_id',$cc_id)->first()->fk_user_id;

        $field_worker_user_id=DB::table('mct_field_workers')->where('f_id',$field_worker_id)->first()->fk_user_id;
        if($i->t_action_items!='')
        {
        $newdata[$x]['field_worker_name']=DB::table('mct_user')->where('user_id',$field_worker_user_id)->first()->v_name;
        $newdata[$x]['call_champion_name']=DB::table('mct_user')->where('user_id',$cc_user_id)->first()->v_name;
        $newdata[$x]['action_items']=$i->t_action_items;       
        $newdata[$x]['date_generated']=$due_id->dt_intervention_date;
        $newdata[$x]['call_id']=$i->fk_due_id;
        $newdata[$x]['report_id']=$i->report_id;
        $newdata[$x]['status']=$i->status;
        $x++;          
        }
        // $x represents total entries.
        //$already resolved represents resolved actions.
        Session::put('total_actions_left',$x-$alread_resolved);
     }
     if($x!=0)
     {
      //sorting w.r.t. date
      usort($newdata, function($a, $b)
      {
            $t1 = strtotime($a['date_generated']);
            $t2 = strtotime($b['date_generated']);
            return $t2 - $t1;
      });           
     }
     //if there are no unresolved action_items or database is empty
     else
     {
      $newdata[$x]['call_champion_name']='';
      $newdata[$x]['action_items']="NO ACTION ITEMS IN DATABASE";
        $newdata[$x]['field_worker_name']='';
        $newdata[$x]['date_generated']='';
        $newdata[$x]['call_id']='';
        $newdata[$x]['status']=1;
        $newdata[$x]['report_id']='';
     }

      for($var=0;$var<$x;$var++)
      {
        $newdata[$var]['date_generated']=Carbon::parse($newdata[$var]['date_generated'])->format('d/m/Y');
      }

    return view('admin/action_items',compact('newdata'));
}
//when any action is resolved in action_items view then we update its status to 1
public function update_status(Request $r,$id)
{
    DB::table('mct_callchampion_report')->where('report_id',$id)->update(['status'=>1]);
    return back();  
}
//
//when any action is unresolved in action_items view then we update its status to 0
public function unresolve_status(Request $r,$id)
{
    DB::table('mct_callchampion_report')->where('report_id',$id)->update(['status'=>0]);
    return back();  
}

  /*
  Assigns a mentor to unapproved callchampion and changes status from unapproved to shadowing
  @returns result for success or failure of updating status of unapproved callchampion
  @calling_method invoked by admin_dashboard/callchampion view
  @algorithm
    1. collects mentee and mentor cc_id 
    2. updates mentee activation status form 0(unapproved) to 1(shadowing) 
    3. inserts mentee and mentor to mct_callchampion_shadow table
  */
  public function assign_mentor(){

    // if user is a callchampion and trying to access this url redirect to /mothers
    if($this->user_role_type == 2)
          return Redirect::to('/mothers'); 

    //collects mentee and mentor cc_id from request inputs    
    $mentee = Input::get('mentee_id');
    $mentor = Input::get('mentor_id');

    //updates mentee activation status form 0(unapproved) to 1(shadowing)
    $result=DB::table('mct_call_champions')
                  ->where('mct_call_champions.cc_id',$mentee)
                  ->update(['mct_call_champions.activation_status' => '1' ]);

    //inserts mentee and mentor to mct_callchampion_shadow table
    DB::table('mct_callchampion_shadow')
      ->insert(['mentee' => $mentee,
                'mentor' => $mentor]
              );
    return response()->json($result);
  }


  /*
  updates callchampion status from shadowing to approved when mentor says shadowing completed
  @returns result for success or failure of updating status of callchampion
  @calling_method invoked by admin_dashboard/callchampion view
  @algorithm
    1. collects cc_id
    2. updates activation status from 1(shadowing) to 2(approved)
  */
  public function update_callchampion_status(){
    
    // if user is a callchampion and trying to access this url redirect to /mothers
    if($this->user_role_type == 2)
          return Redirect::to('/mothers'); 

    //collects cc_id    
    $cc_id = Input::get('cc_id');

    //updates activation status from 1(shadowing) to 2(approved)
    $result=DB::table('mct_call_champions')
                  ->where('mct_call_champions.cc_id',$cc_id)
                  ->update(['mct_call_champions.activation_status' => '2' ]);

    return response()->json($result);
  }
  
  // public function get_assign_mothers($cc_id = -1){
  //   if($this->user_role_type == 2)
  //         return Redirect::to('/mothers'); 
  //   if($cc_id == -1 )
  //     return Redirect::to('/');

  //   $data['unassigned']=DB::table('mct_beneficiary')
  //                 ->whereRaw('b_id not in (select fk_b_id from mct_due_list)')
  //                 ->get();

  //   return view('admin/assign_mothers',$data);
  // }

  // public function post_assign_mothers()
  // {
  //   if($this->user_role_type == 2)
  //         return Redirect::to('/mothers'); 
    
    
  //   if(!empty($_POST['check_list'])){
  //     $cid = 6;
  //     $bid = $_POST['check_list'];

  //     $obj = new BeneficiaryController;
  //     $a = $obj->upload_mother($bid,$cid);
  //   }

  //   $data['unassigned']=DB::table('mct_beneficiary')
  //                 ->whereRaw('b_id not in (select fk_b_id from mct_due_list)')
  //                 ->get();
  //   return view('admin/assign_mothers',$data);

  // }


  /*
  Allocate mothers to a callchampion
  @input a) cc_id b)Mothers count
  @calling_method invoked by admin_dashboard/callchampion view also can be invoked by any other function
  @algorithm
    1. collects cc_id and mothers count
    2. if cc_id is not given redirects back
    3. if count is not given calculates avg number of mothers assigned per callchampion
    4. select count no of beneficiaries which are not assigned to any other callchampion to assign to callchampion having given cc_id 
    5. if there is no such beneficiary found returns back.
    6. else calls batch_assignment_callchampion method in BeneficiaryController
  */
  public function assign_mothers($cc_id = -1, $count = -1)
  {
    // if user is a callchampion and trying to access this url redirect to /mothers
    if($this->user_role_type == 2)
          return Redirect::to('/mothers');

    //collects cc_id and mothers count
    if(Input::get('cc_id') !== null)
      $cc_id = Input::get('cc_id');

    if(Input::get('mothers_count') !== null)
      $count = Input::get('mothers_count');

    //if cc_id is not given redirects back
    if($cc_id == -1)
      return Redirect::back();
    
    //if count is not given calculates avg number of mothers assigned per callchampion
    if($count == -1)
    {
      $total_beneficiary = DB::table('mct_beneficiary')
                             ->count();

      $total_call_champions = DB::table('mct_call_champions')
                                ->where('activation_status','=',2)
                                ->count();

       $count = ceil($total_beneficiary / $total_call_champions);
    }

    //select count no of beneficiaries which are not assigned to any other callchampion to assign to callchampion having given cc_id 
    $bid_array = DB::table('mct_beneficiary')
                  ->whereRaw('b_id not in (select fk_b_id from mct_due_list)')
                  ->take($count)
                  ->get();

     if(!empty($bid_array)){
      $obj = new BeneficiaryController;
      //calls batch_assignment_callchampion method in BeneficiaryController
      $obj->batch_assignment_callchampion($bid_array,$cc_id);
     }

    //if there is no such beneficiary found returns back
    return Redirect::to('/callchampions'); 

  }


  /*
  Directely promotes a callchampion from unapproved to approved(in case prior experience no need for shadowing)
  @input a) cc_id
  @calling_method invoked by admin_dashboard/callchampion view also can be invoked by any other function
  @algorithm
    1. collects cc_id
    2. if cc_id is not given redirects back
    3. else updates status to 2(approved) and redirects back
  */
  public function promote_callchampion($cc_id = -1)
  {
    // if user is a callchampion and trying to access this url redirect to /mothers
    if($this->user_role_type == 2)
          return Redirect::to('/mothers');

    //if cc_id is not given redirects back
    if($cc_id == -1)
      return Redirect::back();

    //updating status from 0(unapproved) to 2(approved)
    $result=DB::table('mct_call_champions')
              ->where('mct_call_champions.cc_id',$cc_id)
              ->update(['mct_call_champions.activation_status' => '2' ]);

    if($result)
    {  
      Session::flash('message',trans("routes.successpromote"));
      return Redirect::back();
    } 
    else 
    {  
      Session::flash('message',trans("routes.errorpromote"));
      return Redirect::back();
    }

  }


  //change password view
  public function changepassword(){
  	if(!isset($this->userid)){
  		Redirect::to('/')->send();
  	}
  	
  	$data['title']="Change Password" . SITENAME;
  	return view('admin/changepassword',$data);
  }
  

  //change pass functionality
  public function dochangepassword(){
  	
  	$userinfo=Session::get('user_logged');
  	if(!isset($userinfo['b_id'])){
  		Redirect::to('/')->send();
  	}
  	//array for validation
  	$users=new Users;
  	$userdata = array(
  			'currpassword' => Input::get('txtCurrentPassword'),
  			'newpassword' => Input::get('txtNewPassword'),
  			'confpassword' => Input::get('txtConfirmPassword'),
  			
  	);
  	// Applying validation rules.
  	
  	$rules = array(
  			'currpassword' => 'required|min:6',
  			'newpassword' => 'required|min:6',
  			'confpassword' =>'required|min:6',
  	);
  	//check validation 
  	$validator = Validator::make($userdata, $rules);
  	
  	if ($validator->fails()){
  		// If validation falis redirect back to login.
  		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>Validation Error</div>');
  		return Redirect::to('/changepassword')->withInput(Input::except('password'))->withErrors($validator);
  	}else{
  		//fetch date of user
  		$userdata = $users->where('bi_id', '=', $userinfo['b_id'])->first();
  		//compare password
  		$checklogin=Hash::check(Input::get('txtCurrentPassword'), $userdata->v_password);
  		if($checklogin){
  			$hashed = Hash::make(Input::get('txtNewPassword'));
  			//change password 
  			$res=$users->where('bi_id', $userinfo['b_id'])->update(array('v_password' => $hashed)); 
  			//success message
  			Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.changepassmsg").'</div>');
  			return Redirect::to('/mothers');
  		}else {
  			Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.passwordnmtc").'</div>');
  			return Redirect::to('admin/changepassword');
  		}
  	}
 }

  //check mail exist or not
  public function checkEmail()
  {
  	$users=new Users;
  	$action=Input::get('action');
  	$userid=Input::get('hdUserId');
  	if($action=="update" && $userid!=""){
  		$userinfo=Session::get('user_logged');
  		$result= $users::where('v_status', 'Active')->where('v_email',Input::get('txtEmail'))->where('bi_id','!=',$userid)->get();
  	}elseif($action=="add"){
  		$result= $users::where('v_status', 'Active')->where('v_email', Input::get('txtEmail'))->get();
  	}
  	if(count($result)>0)
  	{
  		echo "false";
  	}
  	else
  	{
  		echo "true";
  	}
  }
  //check mail for login time
  public function checkEmailLogin()
  {
  	$users=new Users;
  	$action=Input::get('action');
  	$result= $users::where('v_status', 'Active')->where('v_email', Input::get('txtForgotEmailId'))->get();
  	if(count($result)>0)
  		echo "true";
  	else
  		echo "false";
  }
  
  
  //login out 
  public function logout() {
  	Session::flush();
  	return Redirect::to('/')->with('message', '');
  }
  
  
  //get user profile
  public function userprofile(){
  	
  	if(!isset($this->userid)){
  		Redirect::to('/')->send();
  	}
  	$data['languagedata']= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
  	//get profile for field worker
  	if($this->usertype==3){
  		$data['result']=DB::table('mct_field_workers')
  		->leftJoin('mct_address', 'mct_field_workers.i_address_id', '=', 'mct_address.bi_id')
  		->select('mct_field_workers.*', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
  		->where('mct_field_workers.bi_user_login_id',$this->userid)
  		->get();
  	}elseif($this->usertype==2){//get profile for call champion
  		$data['result']=DB::table('mct_call_champions')
  		->leftJoin('mct_address', 'mct_call_champions.i_address_id', '=', 'mct_address.bi_id')
  		->select('mct_call_champions.*', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
  		->where('mct_call_champions.bi_user_login_id',$this->userid)
  		->get();
  	}elseif($this->usertype==1){ //get profile for admin user
  		$data['result']=DB::table('mct_admin')
  		->leftJoin('mct_address', 'mct_admin.i_address_id', '=', 'mct_address.bi_id')
  		->select('mct_admin.*', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
  		->where('mct_admin.bi_user_login_id',$this->userid)
  		->get();
  	}else{
  		return Redirect::to('/mothers');
  	}	
  	$data['result']=$data['result'][0];
  	return view('admin/userprofile',$data);
  }
  
  
  //update user profile
  public function editprofile(){
  	$admin =new Admin;
  	$callchampions= new Callchampions;
  	$fieldworkers= new Fieldworkers;
  	
  	// Get input from form
  	$inputData['userid']	=	Input::get('hdnId');
  	$inputData['login_userid'] = Input::get('hdUserId');
  	$inputData['ddate']		=	str_replace("/", "-",Input::get('txtBirthDate'));
  	$inputData['bdate']		=	Input::get('txtBirthDate')!="" ?date("Y-m-d",strtotime($inputData['ddate'])):"";
  	$inputData['datetime'] 	= 	date("Y-m-d H:i:s");
  	$inputData['language']	=	implode(Input::get('txtLanguage'), ",");
  	$inputData['filename'] ="";
  	if(Input::file())
  	{
  		$image = Input::file('txtProfilePic');
  		$inputData['filename']  = time() . '.' .	$image->getClientOriginalExtension();
  		$path = 'external/profile_picture/' . 		$inputData['filename'];
   		Image::make($image->getRealPath())->resize(100, 100)->save($path);
  	}
  	$inputData['username']		= Input::get('txtUsername');
  	$inputData['phone_number'] 	= Input::get('txtPhoneNumber');
  	$inputData['email']			= Input::get('txtEmail');
  	$inputData['address'] 		= Input::get('txtAddress');
	$inputData['zipcode'] 		= Input::get('hdnZipcode');
  	$inputData['profession']	= Input::get('txtProfession');
  	$inputData['marital_status']= Input::get('txtMaritalStatus');

  	$result = false;  	
  	if($this->usertype==1){ 
  		// update user
  		$inputData['gender']  = Input::get('txtGenderStatus');
	 	$result = $admin->updateProfileTable($inputData);
  	}elseif($this->usertype==2){ 
  		// update call champion
  		$inputData['motherhood']  = Input::get('txtMotherhoodStatus');
  		$result = $callchampions->updateProfileTable($inputData);
  	}elseif($this->usertype==3){
  		// update field worker
  		$inputData['gender'] = Input::get('txtGenderStatus');
  		$result = $fieldworkers->updateProfileTable($inputData);
  	}else{
  		return Redirect::to('/mothers');
  	}	
  	
  	if($result){
	  	Session::flash('message', '<div class="alert alert-success" style="clear:both;">
	             <button data-dismiss="alert" class="close" type="button">&times;</button>'.trans("routes.profilelbl").' '.trans("routes.updatemessage").'</div>');
	  	return Redirect::to('/userprofile/');
	}else{
		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
	         <button data-dismiss="alert" class="close" type="button">&times;</button>'.trans("routes.profilelbl").' '.trans("routes.notupdatemessage").'</div>');
		return Redirect::to('/userprofile/');
	}
  }
  
  //forgot password methode
  public function forgotPassword(){
		die('Contact help@sevasetu.org');
  		$email=Input::get('txtForgotEmailId');
  		$users=new Users;
		$data['result']= $users::where('v_status', 'Active')->where('v_email', Input::get('txtForgotEmailId'))->get()->toArray();
  		if(count($data['result'])>0){
			//email activation starts
			$sent=Mail::send('emails.password',$data, function($message)
				{
				$message->to(Input::get('txtForgotEmailId'))->subject('Forgot Password in Mother Care Tool');
				});
			if($sent){
				Session::flash('sucmessage', trans("routes.changepassmailmsg"));
				return Redirect::to('/login/');
			}else{
				Session::flash('sucmessage', trans("routes.email_exist"));
				return Redirect::to('/login/');
			}	
		}else{
			Session::flash('message', trans("routes.email_exist"));
			return Redirect::to('/login/');
		}	
  }
  //update password view
  public function updatePassowrd($id){
  	$data['title']=$this->title;
  	$data['user_id']=$id;
  	return view('admin/forgotpassword',$data);
  }
  //change forgot password 
  public function changeforgotpassword(){
  	$userid=$this->decode(Input::get('hdnUserId'));
  	$users=new Users;
  	$userdata = array(
  			'newpassword' => Input::get('txtNewPassword'),
  			'confpassword' => Input::get('txtConfirmPassword'),
  				
  	);
  	// Applying validation rules.
  	 
  	$rules = array(
  			'newpassword' => 'required|min:6',
  			'confpassword' =>'required|min:6',
  	);
  	$validator = Validator::make($userdata, $rules);
  	 
  	if ($validator->fails()){
  		// If validation falis redirect back to login.
  		return Redirect::to('/changepassword')->withInput(Input::except('newpassword'))->withErrors($validator);
  	}else{
  		$userdata = $users->where('bi_id', '=', $userid)->first();
  		$hashed = Hash::make(Input::get('txtNewPassword'));
  			$res=$users->where('bi_id', $userdata->bi_id)->update(array('v_password' => $hashed));
  			Session::flash('message', trans("routes.changepassmsg"));
  			return Redirect::to('/');
  	}
  }
  
  //change password methode for super admin
  public function changeuserpassword($id){
  	$data['title']="Change Password" . SITENAME;
  	$data['user_id']=$id;
	$helper_obj = new Helpers;
  	$userid=$helper_obj->decode($id);
  	if($userid>0){
  	$users=new Users;
  	$userdata = $users->where('bi_id', '=', $userid)->first();
  	if($userdata->v_role==1)
  		$data['url']='adminusrs';
  	elseif($userdata->v_role==2)
  		$data['url']='callchampions';
  	else
  		$data['url']='fieldworkers';
  	return view('admin/changeuserspassword',$data);
  	}else{
  		return Redirect::back();
  	}
  }
  
  public function dochangeuserpassword(){
  	$userid=$this->decode(Input::get('hdnUserId'));
  	if($userid>0){
   	$users=new Users;
  	$userdata = array(
  			'newpassword' => Input::get('txtNewPassword'),
  			'confpassword' => Input::get('txtConfirmPassword'),
  
  	);
  	// Applying validation rules.
  
  	$rules = array(
  			'newpassword' => 'required|min:6',
  			'confpassword' =>'required|min:6',
  	);
  	$validator = Validator::make($userdata, $rules);
  
  	if ($validator->fails()){
  		// If validation falis redirect back to login.
  		return Redirect::to('/changepassword')->withInput(Input::except('newpassword'))->withErrors($validator);
  	}else{
  		$userdata = $users->where('bi_id', '=', $userid)->first();
  		$hashed = Hash::make(Input::get('txtNewPassword'));
  		$res=$users->where('bi_id', $userdata->bi_id)->update(array('v_password' => $hashed));
  		$data=array(
  			'v_email'=>$userdata->v_email,
  			'password'=>Input::get('txtNewPassword')	
  		);
  		$send=Mail::send('emails.activation',$data, function($message) use ($data)
  		{
  			$message->to($data['v_email'])->subject('Your New Login Detail');
  		});
  		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.changepassmsg").'</div>');
  		if($userdata->v_role==1){
  			return Redirect::to('/adminusrs');
  		}elseif($userdata->v_role==2){
  			return Redirect::to('/callchampions');
  		}else{
  			return Redirect::to('/fieldworkers');
  		}
  	}
  	
  	}else{
  		return Redirect::back();
  	}
  }
  
  ////////////////////////////////////////////////////////////////////////////////////////
  /////// SHOULD IDEALLY GO INTO A REGISTRATION CONTROLLER BUT UNABLE TO CREATE ONE!//////
  ////////////////////////////////////////////////////////////////////////////////////////
  
    public function getRegister()
    {
        return $this->showRegistrationForm();
    }
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if (property_exists($this, 'registerView')) {
            return view($this->registerView);
        }
        return view('auth.register');
    }
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        return $this->register($request);
    }
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request){
		if(Session::has('user_logged')){
			Redirect::to('/mothers')->send();
		}
		$reg = new Registrar();
        $validator = $reg->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
		// Add this info in the session and wait for SMS auth
		Session::put('name', $request->get('name'));
		Session::put('email', $request->get('email'));
		Session::put('phonenumber', $request->get('phonenumber'));
		Session::put('password', $request->get('password'));
		
		//Generate a token for SMS verification
		$token = mt_rand(1000000,9999999);
		Session::put('phone_auth_token', $token);
		
		//Send SMS
		include(storage_path().'/sms.php');
		$result = send_sms(1, array(0=>$request->get('phonenumber'), 1=>$token, 2=> $request->get('name')));
		
		//Redirect to view
		return view('auth.validate');
		
	}
	
	public function validate_phonenumber(Request $request){
		$reg = new Registrar();
		$validator = $reg->validate_sms_passkey($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
		
		$token_received = $request['passkey'];
		$token_original = Session::get('phone_auth_token');
		
//		if(true){
		if($token_original == $token_received){
			Session::forget('phone_auth_token');
			$name = Session::get('name');
			$email = Session::get('email');
			$pn = Session::get('phonenumber');
			$pass = Session::get('password');
			
			return $this->create_new_user(2, $name, $email, $pn, $pass);
			//by default, those registering via the form are call champions
		}
		else{
			Session::flash('message', trans("routes.loginerror"));
			return Redirect::back();
		}
	}
	
	public function create_new_user($role, $name, $email, $pn, $pass){
		// Create a new user
		$user = new User;
		$data_to_push = [
			'v_name' => $name,
			'v_email' => $email,
			'i_phone_number' => $pn,
			'v_password' => Hash::make($pass),
			'v_password_unenc' => $pass,
			'v_role' => 2,
			'dt_create_date' => date("Y-m-d H:i:s"),
			'dt_last_login' => date("Y-m-d H:i:s")
		];
		
		$usr_record = $user->mod_user($data_to_push);
		if($usr_record === false){
			// something wrong here. needs to be checked.
			die("ohmyuser");
		}
		// User successfully saved
		// Now create a record in the call champion table
		
		$call_champ_obj = new CallChampion;
		$cc_record = $call_champ_obj->add_champion($usr_record);
		if(!$cc_record){
			// something wrong here. needs to be checked.
			die("ohmycc");
		}
		// Send a confirmation mail
		$sent=Mail::send('emails.activation',$data_to_push, function($message) use($email){
		$message->to($email)->subject('Welcome to Seva Setu\'s Mother Care program');
		});
		
		$userdet=array(
			'role_id' => $cc_record,
			'v_name' => $name,
			'v_user_name' => $name,
			'v_role' => $role,
			'user_id'=>$usr_record 
		);
		
		$ret = $user->log_in_user($userdet);
		if($ret){
			
			return Redirect::to('/mothers/');
		}
		else{
			Session::flash('message', trans("routes.loginerror"));
			return Redirect::to('admin');
		}
	}
	
	
    /**
     * Get the guard to be used during registration.
     *
     * @return string|null
     */
    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : null;
    }

    public function faq()
    {
        return view('admin/faq');
    }

    public function faq_checklist()
    {
        $table_name = 'mct_checklist_master';
        $select['checklist_master'] = DB::table($table_name)
            ->select('*')
            ->get();
        return view('checklist/list',$select);
    }
}

