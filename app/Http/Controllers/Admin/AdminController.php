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
				return Redirect::to('admin/login');
			}
		}
    }


  public function admin_dashboard(){
    if($this->user_role_type == 2)
          return Redirect::to('/mothers');   
    return view('admin/admin_dashboard');
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
		$result = send_sms(1, array(0=>$request->get('phonenumber'), 1=>$token));
		
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
		
		//if(true){
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

