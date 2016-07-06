<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Users;
use Request;
use Mail;
use Auth;
use Hash;
use DB;
use Validator;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\UserInterface;
use Illuminate\Http\Response;
use Hashids\Hashids;
use Image;
use App\Http\Helpers;


class AdminusrController extends Controller{
	protected $model;
	public $title="Admin User";
	public function __construct(){
	
		$this->helper = new Helpers();
		$this->helper->clearBen_Data();
		
		$userinfo=Session::get('user_logged');
		if(!isset($userinfo['b_id']) || $userinfo['v_role']!=0){
			Redirect::to('/admin/')->send();
		}
		
	
	}
	
public function  index(){
	$data['title']= "Program Coordinator" . SITENAME ;
	$admin =new Admin;
	//$data['result']= $product::where('v_status', 'Active')->take(5)->get();
	//$data["product_pages"] = $product->links();
	//$data['languagedata']= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
	$data['result']= $admin::where('e_status','!=', 'Deleted')->orderBy('bi_id', 'DESC')->paginate(15);
	if (Request::ajax()) {
		return view('mtcadmin/manageajax',$data);
	}
	//print_r($data['result']); exit;
	return view('mtcadmin/manage',$data);
}
public function edit($id=0){
	$id=$this->decode($id);
	$data['title']= "Program Coordinator" . SITENAME ;
	
	$data['languagedata']= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
	if($id==0){
		$data['action']="add";
		$data['result']['v_name'] ="";
		$data['result']['bi_user_login_id']=0;
		$data['result']['v_phone_number']="";
		$data['result']['v_language']="";
		$data['result']['v_email']="";
		$data['result']['dt_birthdate']=0;
		$data['result']['v_profile_pic']="";
		$data['result']['v_address']="";
		$data['result']['v_pincode']="";
		$data['result']['v_taluka']="";
		$data['result']['v_district']="";
		$data['result']['v_state']="";
		$data['result']['v_country']="";
		$data['result']['i_address_id']=0;
		$data['result']['v_profession']="";
		$data['result']['e_marital_status']="";
		$data['result']['e_gender']="";
		$data['result']['bi_calls_completed']="";
		$data['result']['bi_total_call_duration']="";
		$data['result']['bi_id']=0;
		$data['result']=(Object)$data['result'];
	}else{
		$data['result']=DB::table('mct_admin')
		->leftJoin('mct_address', 'mct_admin.i_address_id', '=', 'mct_address.bi_id')
		->select('mct_admin.*', 'mct_address.v_village', 'mct_address.v_village_pincode', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		->where('mct_admin.bi_id',$id)
		->get();
		$data['result']=$data['result'][0];
		$data['action']="update";
	}
	return view('mtcadmin/form',$data);
}
public function add(){// add record
	$admin =new Admin;
	$users= new Users;
	$language=implode(Input::get('txtLanguage'), ",");
	$datetime = date("Y-m-d H:i:s");
	$filename="";
	if(Input::file())
	{
	
		$image = Input::file('txtProfilePic');
		$filename  = time() . '.' . $image->getClientOriginalExtension();
	
		$path = 'external/profile_picture/' . $filename;
	
		Image::make($image->getRealPath())->resize(100, 100)->save($path);
	}
	$ddate=str_replace("/", "-",Input::get('txtBirthDate'));
	$bdate=Input::get('txtBirthDate')!="" ?date("Y-m-d",strtotime($ddate)):"";
	$password=$this->random_password(6);
	$hashed = Hash::make($password);
	$logindata=array(
			'v_name' =>Input::get('txtUsername'),
			'v_email'=>Input::get('txtEmail'),
			'v_password'=>$hashed,
			'ti_is_verified'=>0,
			'v_role'=>1,
			'v_status'=>'Active',
			'dt_created_at'=>$datetime,
			'dt_updated_at'=>$datetime,
			'v_ip'=>$_SERVER['REMOTE_ADDR']);
			$userid=$users->insertGetId($logindata);
			$logindata['password']=$password;
			$send=Mail::send('emails.activation',$logindata, function($message)
			{
			$message->to(Input::get('txtEmail'))->subject('Registration in Mother Care Tool');
			});
			
			$lastrow=$admin::orderBy('bi_id', 'DESC')->first();
			
			if(count($lastrow)>0)
				$code='PC'.($lastrow->bi_id+1);
			else
				$code='PC1';
			
			if($userid!=""){
			$data=array(
				'v_name' =>Input::get('txtUsername'),
				'bi_user_login_id' =>$userid,
				'v_phone_number'=>Input::get('txtPhoneNumber'),
				'v_language'=>Input::get('txtLanguage'),
				'v_email'=>Input::get('txtEmail'),
				'dt_birthdate'=>$bdate,
				'v_profession'=>Input::get('txtProfession'),
				'v_address'=>Input::get('txtAddress'),
				'i_address_id'=>Input::get('hdnZipcode'),
				'v_profile_pic'=>$filename,	
				'v_language'=>$language,	
				'e_marital_status'=>Input::get('txtMaritalStatus'),
				'v_unique_code'=>$code,
	  			'e_gender'=>Input::get('txtGenderStatus'),
	  			'dt_create_date'=>$datetime,
	  			'dt_modify_date'=>$datetime,
	  			'e_status'=>"Active",
	  			'v_ip'=>$_SERVER['REMOTE_ADDR']);
	  			//print_r($product); exit;
	  			if($admin->insert($data)){
	  				Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.adminuser").' '.trans("routes.addmessage").'</div>');
					return Redirect::to('/admin/adminusrs/');
				}else{
					Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.adminuser").' '.trans("routes.notaddmessage").'</div>');
					return Redirect::to('/admin/adminusrs/');
				}
			}else{
					Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.adminuser").' '.trans("routes.notaddmessage").'</div>');
					return Redirect::to('/admin/adminusrs/');
				}
	}
	public function update(){
      	$admin =new Admin;
      	
     	/*$datetime = date("Y-m-d H:i:s");
		$ddate=str_replace("/", "-",Input::get('txtBirthDate'));
		$bdate=Input::get('txtBirthDate')!="" ?date("Y-m-d",strtotime($ddate)):"";
		$language=implode(Input::get('txtLanguage'), ",");
		$filename="";
		if(Input::file())
		{
		
			$image = Input::file('txtProfilePic');
			$filename  = time() . '.' . $image->getClientOriginalExtension();
		
			$path = 'external/profile_picture/' . $filename;
		
			Image::make($image->getRealPath())->resize(100, 100)->save($path);
		}
		$productid=Input::get('hdnId');
		$admin= $admin->find($productid);
      	$admin->v_name =Input::get('txtUsername');
		$admin->v_phone_number=Input::get('txtPhoneNumber');
		$admin->v_language=Input::get('txtLanguage');
		$admin->v_email=Input::get('txtEmail');
		$admin->dt_birthdate=$bdate;
		$admin->v_address=Input::get('txtAddress');
		$admin->i_address_id=Input::get('hdnZipcode');
		if($filename!="")
			$admin->v_profile_pic=$filename;
		$admin->v_language=$language;
		$admin->v_profession=Input::get('txtProfession');
      	$admin->e_marital_status=Input::get('txtMaritalStatus');
      	$admin->e_gender=Input::get('txtGenderStatus');
      	$admin->dt_modify_date=$datetime;
      	$admin->v_ip=$_SERVER['REMOTE_ADDR'];
      	$result=$admin->save(); */
      	
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
		$inputData['gender']		= Input::get('txtGenderStatus');
		 
		
      	//Admin update field worker's profile.
      	$result = $admin->updateProfileTable($inputData);
      	
      	if($result){
      		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">&times;</button>'.trans("routes.adminuser").' '.trans("routes.updatemessage").'</div>');
      		return Redirect::to('/admin/adminusrs/');
		}else{
			Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">&times;</button>'.trans("routes.adminuser").' '.trans("routes.notupdatemessage").'</div>');
			return Redirect::to('/admin/adminusrs/');
      	}
    }
    public function view($id=0){
    	$id=$this->decode($id);
    	$data['title']="View Program Coordinator" . SITENAME;
    	if($id!=0){
    		$data['result']=DB::table('mct_admin')
			->leftJoin('mct_address', 'mct_admin.i_address_id', '=', 'mct_address.bi_id')
			->select('mct_admin.*', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
			->where('mct_admin.bi_id',$id)
			->get();
    		if(!empty($data['result'])){
				$data['result']=$data['result'][0];
    		}
    		return view('mtcadmin/view',$data);
    	}else{
    		return redirect('/admin/adminusrs');
    	}
    	
    }
  //active inactive program coordinaters  
    public function delete($id,$userid,$flag){
    	$id=$this->decode($id);
    	$userid=$this->decode($userid);
    	$flag=$this->decode($flag);
    	$admin =new Admin;
    	$users= new Users;
    	$msgdata['userdata']=$admin::where('bi_id',$id)->orderBy('bi_id', 'DESC')->get();
    	$id= $admin->find($id);
    	if($flag==0)
    		$id->e_status="Inactive";
    	else
    		$id->e_status="Active";
    	$result=$id->save();
    	
    	$uid= $users->find($userid);
    	$id= $admin->find($id);
    	if($flag==0)
    		$uid->v_status="Inactive";
    	else
    		$uid->v_status="Active";
    	$result=$uid->save();
    	if($flag==0){
    		$msg=trans("routes.deactivemsg");
    		$msgdata['msg']="Your user account is deactivate by admin";
    	}else{
    		$msg=trans("routes.activemsg");
    		$msgdata['msg']="Your user account is activate by admin";
    	}
    	if($result){
    		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.adminuser").' '.$msg.'</div>');
    		$send=Mail::send('emails.notification',$msgdata, function($message) use ($msgdata)
    		{
    			$message->to($msgdata['userdata'][0]->v_email)->subject('User Account Notification');
    		});
    		return Redirect::to('/admin/adminusrs/');
    	}else{
    		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.adminuser").' '.trans("routes.notupdatemessage").'</div>');
    		return Redirect::to('/admin/adminusrs/');
    	}
    }
    public function decode($id=0){
    	if($id){
    		$hashids = new Hashids();
    		$arr = $hashids->decode($id);
    		if(!empty($arr)){
    			return $id=$arr[0];
    		}else{
    			return 0;    			
    		}
    	}else
    		return 0;
    }
    public function deleteSelected(){
    	$checkedRowArray = Input::get('chkCheckedBox');
    	if(count($checkedRowArray)>0){
	    	$admin =new Admin;
	    	$users= new Users;
	  		$idsArr = array();
	  		$usrArr = array();
	  		$data = array(
	    			'e_status' => 'Deleted'
	    	);
	  		$data1 = array(
	  				'v_status' => 'Inactive'
	  		);
	    	foreach($checkedRowArray as $value){
	    		$value=explode("_", $value);
	  			$idsArr[] = $value[0];
	  			$usrArr[] = $value[1];
	    	}
	    	$checkedRow = implode(",",$idsArr);
	    	$result = $admin->whereIn('bi_id', $idsArr)->update($data);
	    	
	    	$usercheckedRow = implode(",",$usrArr);
	    	$result1 = $users->whereIn('bi_id', $usrArr)->update($data1);
	    	
	    	if($result){
	    		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.adminuser").' '.trans("routes.deletemessage").'</div>');
	    		return Redirect::to('/admin/adminusrs/');
	    	}else{
	    		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.adminuser").' '.trans("routes.notdeletemessage").'</div>');
	    		return Redirect::to('/admin/adminusrs/');
	    	}
    	}else{
    		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.adminuser").' '.trans("routes.notdeletemessage").'</div>');
    	}	
    	return Redirect::to('/admin/adminusrs/');
    
    }
    function random_password( $length = 8 ) {
    	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    	$password = substr( str_shuffle( $chars ), 0, $length );
    	return $password;
    }

public function autocompleteadmin()
{
	$arr=array();
	$admin =new Admin;
	$result=$admin::where('v_unique_code', 'LIKE', '%'.$_GET['chars'].'%')
	->where('e_status',"!=",'Deleted')
	->orWhere('v_name', 'LIKE', '%'.$_GET['chars'].'%')->where('e_status',"!=",'Deleted')
	->orWhere('v_phone_number', 'LIKE', '%'.$_GET['chars'].'%')->where('e_status',"!=",'Deleted')->take(10)->get();
		
	//print_r($result); exit;
	//echo $this->db->last_query();
	if(count($result)>0){
		foreach ($result as $val){
			// Store data in array
			//print_r($data);
			if(strpos(strtolower($val->v_unique_code),strtolower($_GET['chars'])) !== false)
			{
				$arr[]=array("id" => $val->bi_id, "data" => $val->v_unique_code);
			}else if(strpos(strtolower($val->v_name),strtolower($_GET['chars'])) !== false)
			{
				$arr[]=array("id" => $val->bi_id, "data" => $val->v_name);
			}else if(strpos(strtolower($val->v_phone_number),strtolower($_GET['chars'])) !== false)
			{
				$arr[]=array("id" => $val->bi_id, "data" => $val->v_phone_number);
			}
		}
	}

	// Encode it with JSON format
	echo json_encode($arr);
}
	public function searchdataadmin($id="",$search=""){
		$admin =new Admin;
		$data['title'] = "Search Program Coordinator". SITENAME;
		if(isset($_GET['search']) && $_GET['search']!=""){
			$data['result']=$admin::where('v_unique_code', 'LIKE', '%'.$_GET['search'].'%')
			->where('e_status',"!=",'Deleted')
			->orWhere('v_name', 'LIKE', '%'.$_GET['search'].'%')->where('e_status',"!=",'Deleted')
			->orWhere('v_phone_number', 'LIKE', '%'.$_GET['search'].'%')->where('e_status',"!=",'Deleted')->paginate(15);
			$data['searchTag']=$_GET['search'];
		}else{
			$data['searchTag']=$search;
			$data['result']= $admin::where('e_status',"!=",'Deleted')->where('bi_id', $id)->paginate(15);
		}
		return view('mtcadmin/manage',$data);
	}
}
?>