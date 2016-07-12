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
	public $role_type;
	protected $helper;
	protected $role_permissions;
	
	public function __construct(){
		$userinfo=Session::get('user_logged');
		if(isset($userinfo['user_id'])){
			$this->user_id=$userinfo['user_id'];
		}
		
		if(isset($userinfo['role_id'])){
			$this->role_id=$userinfo['role_id'];
		}
				
		if(isset($userinfo['v_role'])){
			$this->helper = new Helpers();
			
			$this->role_type=$userinfo['v_role'];
			$this->role_permissions = $this->helper->checkpermission(Session::get('user_logged')['v_role']);
			
			$this->helper->clearBen_Data();
		}
	}
	
	public function index(){
		if(isset($this->user_id)){
			Redirect::to('/admin/dashboard')->send();
		}
		$data['title']= "Login";
		return view('admin/login',$data);
	}
	
	/*
	 * Dashboard Page
	 */
	public function dashboard(){// defualt method
		//security concern. there should be a middleware checking for this
		if(!isset($this->user_id)){
			Redirect::to('/admin/')->send();
		}
		$data['title']= "Dashboard" . SITENAME;
		
		// Generate dashboard landing data as per the role
		// If its a call champion
		if($this->role_type == 2){
			$callchamp = new CallChampion;
			$dashboard_data = $callchamp->get_dashboard_data($this->role_id);
		}
		
		// If its a fieldworker
		elseif($this->role_type == 3){
			$fieldworker = new Fieldworkers;
			$dashboard_data = $fieldworker->get_dashboard_data($this->role_id);
		}
		//ideally should be hashed
		$encoded_data 	= $dashboard_data;
		Session::put('user', $encoded_data);
		return view('admin/dashboard',$dashboard_data);
	}
	
	
	/*
	 * User Login for Dashboard
	 */
 	public function login() {
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
    	return Redirect::to('/admin')->withErrors($validator);
    }else{
      	$validlogin = $users->validate_login($userdata);
       	if($validlogin){
    		return Redirect::to('/admin/dashboard/');
    	}else{
    		Session::flash('message', trans("routes.loginerror"));
    		return Redirect::to('admin');
    	}
     }
  }

  //change password view
  public function changepassword(){
  	if(!isset($this->userid)){
  		Redirect::to('/admin/')->send();
  	}
  	
  	$data['title']="Change Password" . SITENAME;
  	return view('admin/changepassword',$data);
  }
  
  //change pass functionality
  public function dochangepassword(){
  	
  	$userinfo=Session::get('user_logged');
  	if(!isset($userinfo['b_id'])){
  		Redirect::to('/admin/')->send();
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
  		return Redirect::to('/admin/changepassword')->withInput(Input::except('password'))->withErrors($validator);
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
  			return Redirect::to('/admin/dashboard');
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
  	Session::forget('user_logged');
  	return Redirect::to('admin')->with('message', '');
  }
  
  
  //get user profile
  public function userprofile(){
  	
  	if(!isset($this->userid)){
  		Redirect::to('/admin/')->send();
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
  		return Redirect::to('/admin/dashboard/');
  	}	
  	$data['result']=$data['result'][0];
  	return view('admin/userprofile',$data);
  }
  
  //load inversion poin view
  public function manageInterventionPoint(){
  	
  	if(!isset($this->userid)){
  		Redirect::to('/admin/')->send();
  	}
  	
  	if($this->role_permissions['manageintervention']){
  		$data['title']="Intervention Point" . SITENAME;
  		$data['result']=DB::table('mct_intervention_point')->where('e_status','Active')->orderBy('i_week', 'ASC')->get();	
  		return view('admin/interventionpoint',$data);
  	}else{
  		Redirect::to('/admin/')->send();
  	}
  }
  
  //update intervation view
  public function updateInterventionPoint(){
  	if(!isset($this->userid)){
  		Redirect::to('/admin/')->send();
  	}
  	$datetime = date("Y-m-d H:i:s");
  	$checkedRowArray = Input::get('txtInterventionPoint');
  	$idArray = Input::get('hdnInverationId');
  	$titleArray = Input::get('txtTitle');
  	$descArray = Input::get('txtDescritpion');
  	$i=0;
  	foreach($checkedRowArray as $value){
  		$data=array(
  				'i_week' => $value,
  				'v_name'=>trim($titleArray[$i]),
  				't_description'=>trim($descArray[$i]),
  				'dt_create_date'=>$datetime,
	  			'dt_modify_date'=>$datetime,
	  			'e_status'=>"Active",
	  			'v_ip'=>$_SERVER['REMOTE_ADDR']
  		);
  		$update=array(
  				'v_name'=>trim($titleArray[$i]),
  				't_description'=>trim($descArray[$i]),
  				'e_status'=>"Active",
  				'v_ip'=>$_SERVER['REMOTE_ADDR']
  		);
  		$result=DB::table('mct_intervention_point')->where('e_status','Active')->where('i_week',$value)->get();
  		if(!empty($result)){
			$id=$result[0]->i_week;
			$ids[]=$id;
			if($i < count($idArray))
				DB::table('mct_intervention_point')->where('bi_id', $idArray[$i])->update($update);
  		}else{
  			if(isset($idArray[$i])){
  				$res=DB::table('mct_intervention_point')->where('bi_id', $idArray[$i])->update($data);
  			}else{
  				DB::table('mct_intervention_point')->insert($data);
  			}
  		}
  	$i++;
  	}
  	Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.interventionpoint").' '.trans("routes.updatemessage").'</div>');
  	return Redirect::to('admin/manageInterventionPoint');
  }
  //delete intervation poin
  public function intervation_delete(){
  	if(!isset($this->userid)){
  		Redirect::to('/admin/')->send();
  	}
  	$res=DB::table('mct_intervention_point')->where('bi_id', Input::get('id'))->update(array('e_status' => 'Inactive'));
  	if($res)
  		return '<div class="alert alert-success" style="clear:both;"><button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.interventionpoint").' '.trans("routes.deletemessage").'</div>';
  	else
  		return '<div class="alert alert-success" style="clear:both;"><button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.interventionpoint").' '.trans("routes.notdeletemessage").'</div>';
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
  		return Redirect::to('/admin/dashboard/');
  	}	
  	
  	if($result){
	  	Session::flash('message', '<div class="alert alert-success" style="clear:both;">
	             <button data-dismiss="alert" class="close" type="button">&times;</button>'.trans("routes.profilelbl").' '.trans("routes.updatemessage").'</div>');
	  	return Redirect::to('/admin/userprofile/');
	}else{
		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
	         <button data-dismiss="alert" class="close" type="button">&times;</button>'.trans("routes.profilelbl").' '.trans("routes.notupdatemessage").'</div>');
		return Redirect::to('/admin/userprofile/');
	}
  }
  
  //load add address view
  public function addlocation(){
  	if($this->role_permissions['canaddlocation']){
		  	$data['title']=$this->title;
		  	$data['city']="";
		  	$data['state']="";
		  	$data['taluka']="";
		  	$data['result']=array();
		  	$data['title'] = "Location" . SITENAME;
		  	return view('admin/addlocation',$data);
  	}else{
  			Redirect::to('/admin/')->send();
  	}
  }
  //edit address 
  public function editlocation(){
  	if($this->role_permissions['canaddlocation']){
	  	$id=Input::get('hdnAddId');
	  	if($id!=""){ 
		  	$data=array(
		  			'v_pincode' =>Input::get('txtZipcode'),
		  			'v_taluka'=>Input::get('txtTaluka'),
		  			'v_district'=>Input::get('txtDistrict'),
		  			'v_state'=>Input::get('txtState'),
		  			'v_country'=>Input::get('txtCountry'),
		  	);
		  	//edit address query
		  	$result = DB::table('mct_address')->where('bi_id', $id)->update($data);
		  	if($result){
		  		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
		              <button data-dismiss="alert" class="close" type="button">&times;</button>'.trans("routes.location").' '.trans("routes.updatemessage").'</div>');
		  		return Redirect::to('/admin/addlocation/');
		  	}
		  	else{
		  		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
		              <button data-dismiss="alert" class="close" type="button">&times;</button>'.trans("routes.location").' '.trans("routes.updatemessage").'</div>');
		  		return Redirect::to('/admin/addlocation/');
		  	}
	  	}else{ 
		  	$data=array(
		  			'v_pincode' =>Input::get('txtZipcode'),
		  			'v_taluka'=>Input::get('txtTaluka'),
		  			'v_district'=>Input::get('txtDistrict'),
		  			'v_state'=>Input::get('txtState'),
		  			'v_country'=>Input::get('txtCountry'),
		  			);
		  	//print_r($product); exit;
		  	//insert address 
		  	$res=DB::table('mct_address')->insert($data);
		  	if($res){
		  		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
		              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.location").' '.trans("routes.addmessage").'</div>');
		  		return Redirect::to('/admin/addlocation/');
		  	}else{
		  		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
		              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.location").' '.trans("routes.notaddmessage").'</div>');
		  		return Redirect::to('/admin/addlocation/');
		  	}
	  	}
  	}	
  }
  //forgot password methode
  public function forgotPassword(){
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
				return Redirect::to('/admin/login/');
			}else{
				Session::flash('sucmessage', trans("routes.email_exist"));
				return Redirect::to('/admin/login/');
			}	
		}else{
			Session::flash('message', trans("routes.email_exist"));
			return Redirect::to('/admin/login/');
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
  		return Redirect::to('/admin/changepassword')->withInput(Input::except('newpassword'))->withErrors($validator);
  	}else{
  		$userdata = $users->where('bi_id', '=', $userid)->first();
  		$hashed = Hash::make(Input::get('txtNewPassword'));
  			$res=$users->where('bi_id', $userdata->bi_id)->update(array('v_password' => $hashed));
  			Session::flash('message', trans("routes.changepassmsg"));
  			return Redirect::to('/admin/');
  	}
  }
  public function decode($id=0){
  	if($id){
  		$hashids = new Hashids();
  		$arr = $hashids->decode($id);
  		return (!empty($arr)) ? $id=$arr[0] : 0; 
  	}else
  		return 0;
  }
  
  //change password methode for super admin
  public function changeuserpassword($id){
  	$data['title']="Change Password" . SITENAME;
  	$data['user_id']=$id;
  	$userid=$this->decode($id);
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
  		return Redirect::to('/admin/changepassword')->withInput(Input::except('newpassword'))->withErrors($validator);
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
  			return Redirect::to('/admin/adminusrs');
  		}elseif($userdata->v_role==2){
  			return Redirect::to('/admin/callchampions');
  		}else{
  			return Redirect::to('/admin/fieldworkers');
  		}
  	}
  	
  	}else{
  		return Redirect::back();
  	}
  }
  
  //search address by state or city or taluka 
  public function searchdataaddress($state="",$city="",$taluka=""){
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
  		$where=array("mct_address.v_district",$city);
  	}else{
  		$where=array("mct_address.v_state",$state);
  	}
  	$data['talukaarr']= DB::table('mct_address')->distinct()->select('v_taluka')->where('v_district', 'LIKE', '%'.$city.'%')->get();
  	$data['cityarr']= DB::table('mct_address')->distinct()->select('v_district')->where('v_state', 'LIKE', '%'.$state.'%')->get();
  	$data['city']=$city;
  	$data['state']=$state;
  	$data['taluka']=$taluka;
  	$data['result'] = DB::table('mct_address')->where("$where[0]","=",$where[1])->groupBy('v_pincode')->get();
  	//print_r($data); exit;
  	$data['title'] = "Location" . SITENAME;
  	return view('admin/addlocation',$data);
  
  }
  //edit address by ajax
  public function editaddress(){
  	$id=$this->decode(Input::get('id'));
  	$data['result'] = DB::table('mct_address')->where("bi_id",$id)->get();
  	$result =$data['result'][0];
  	echo json_encode($result);
  }
  
  ///////////////////////////////////////////////////////////////
  /////// SHOULD IDEALLY GO INTO A REGISTRATION CONTROLLER //////
  ///////////////////////////////////////////////////////////////
  
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
		$ROLE = 2; //by default, those registering via the form are call champions
		$reg = new Registrar();
        $validator = $reg->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
		
		// Create a new user		
		$user = new User;
		
		$data_to_push = [
			'v_name' => $request->get('name'),
			'v_email' => $request->get('email'),
			'password' => Hash::make($request->get('password')),
		];
		$usr_record = $user->mod_user($data_to_push, $ROLE);
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
		
		// Log the candidate in
		//// create an entry in the session and redirect user to panel
		$userdet=array(
			'role_id' => $cc_record,
			'v_name' => $request->get('name'),
			'v_user_name' => $request->get('name'),
			'v_role' => $ROLE,
			'user_id'=>$usr_record 
		);
		
		$ret = $user->log_in_user($userdet);
		if($ret)
			return Redirect::to('/admin/dashboard/');
		else{
			Session::flash('message', trans("routes.loginerror"));
			return Redirect::to('admin');
		}
		
		$validlogin = true;//$users->validate_login($userdata);
       	if(!$validlogin){
			Session::flash('message', trans("routes.loginerror"));
    		return Redirect::to('admin');
    	}
		
				
    	//return Redirect::to('/admin/dashboard/');
        //Auth::guard($this->getGuard())->login($this->create($request->all()));
        //return redirect($this->redirectPath());
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
}