<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Fieldworkers;
use App\Models\Users;
use Request;
use Mail;
use Auth;
use DB;
use Hash;
use Validator;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\UserInterface;
use Illuminate\Http\Response;
use Hashids\Hashids;
use Image;
use App\Http\Helpers;

class FieldworkerController extends Controller{
	protected $model;
	public $title="Admin Login";
	public function __construct(){
		$userinfo=Session::get('user_logged');
		if(!isset($userinfo['b_id'])){
			Redirect::to('/admin/')->send();
		}
		
		$this->helper = new Helpers();
		$this->helper->clearBen_Data();
	}
//main method of field worker	
public function  index(){
	$data['title']= "Field Worker" . SITENAME;
	$fieldworkers =new Fieldworkers;
	//get lists of languages
	$data['result']= $fieldworkers::where('e_status','!=', 'Deleted')->orderBy('bi_id', 'DESC')->paginate(15);//get field worker
	if (Request::ajax()) {
		return view('fieldworkers/manageajax',$data);
	}
	//print_r($data['result']); exit;
	return view('fieldworkers/manage',$data);
}

//add or edit method
public function edit($id=0){
	//get lists of languages
	$data['languagedata']= DB::table('mct_language')->where('e_status', 'Active')->orderBy('bi_id', 'ASC')->get();
	$data['title']= "Field Worker" . SITENAME;
	
	$id=$this->decode($id);
	if($id==0){
		//for insert mode if id not found
		$data['action']="add";
		$data['result']['v_name'] ="";
		$data['result']['v_phone_number']="";
		$data['result']['v_language']="";
		$data['result']['v_profile_pic']="";
		$data['result']['bi_user_login_id']=0;
		$data['result']['v_email']="";
		$data['result']['dt_birthdate']=0;
		$data['result']['v_address']="";
		$data['result']['v_pincode']="";
		$data['result']['v_taluka']="";
		$data['result']['v_district']="";
		$data['result']['v_state']="";
		$data['result']['v_country']="";
		$data['result']['i_address_id']=0;
		$data['result']['v_profession']="";
		$data['result']['e_marital_status']="No";
		$data['result']['e_gender']="";
		$data['result']['bi_calls_completed']="";
		$data['result']['bi_total_call_duration']="";
		$data['result']['bi_id']=0;
		$data['result']=(Object)$data['result'];
	}else{
		//get field worker for edit and id is found for that
		$data['result']=DB::table('mct_field_workers')
		->leftJoin('mct_address', 'mct_field_workers.i_address_id', '=', 'mct_address.bi_id')
		->select('mct_field_workers.*','mct_address.v_village', 'mct_address.v_village_pincode', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
		->where('mct_field_workers.bi_id',$id)
		->get();
		$data['result']=$data['result'][0];
		$data['action']="update";
	}
	return view('fieldworkers/form',$data);
}


public function add(){// add record
	$fieldworkers= new Fieldworkers;
	$users= new Users;
	$language=implode(Input::get('txtLanguage'), ",");
	$datetime = date("Y-m-d H:i:s");
	$ddate=str_replace("/", "-",Input::get('txtBirthDate'));
	$bdate=Input::get('txtBirthDate')!="" ?date("Y-m-d",strtotime($ddate)):"";
	$filename="";
	if(Input::file())
	{
	
		$image = Input::file('txtProfilePic');
		$filename  = time() . '.' . $image->getClientOriginalExtension();
	
		$path = 'external/profile_picture/' . $filename;
	
		Image::make($image->getRealPath())->resize(100, 100)->save($path);
	}
	$password=$this->random_password(6);
	$hashed = Hash::make($password);
	$logindata=array(
			'v_name' =>Input::get('txtUsername'),
			'v_email'=>Input::get('txtEmail'),
			'v_password'=>$hashed,
			'ti_is_verified'=>0,
			'v_role'=>3,
			'v_status'=>'Active',
			'dt_created_at'=>$datetime,
			'dt_updated_at'=>$datetime,
			'v_ip'=>$_SERVER['REMOTE_ADDR']);
			$userid=$users->insertGetId($logindata);
			$logindata['password']=$password;
			$sent=Mail::send('emails.activation',$logindata, function($message)
			{
			$message->to(Input::get('txtEmail'))->subject('Registration in Mother Care Tool');
			});

			$lastrow=$fieldworkers::orderBy('bi_id', 'DESC')->first();
			
			if(count($lastrow)>0)
				$code='FW'.($lastrow->bi_id+1);
			else
				$code='FW1';
			
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
	  			if($fieldworkers->insert($data)){
	  				Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.addmessage").'</div>');
					return Redirect::to('/admin/fieldworkers/');
				}else{
					Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.notaddmessage").'</div>');
					return Redirect::to('/admin/fieldworkers/');
				}
			}else{
					Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.notaddmessage").'</div>');
					return Redirect::to('/admin/fieldworkers/');
				}
	}
	public function update(){
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
      	$inputData['gender']		= Input::get('txtGenderStatus');
      	
      	//Admin update field worker's profile.
        $result = $fieldworkers->updateProfileTable($inputData);
		if($result){
      		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.updatemessage").'</div>');
      		return Redirect::to('/admin/fieldworkers/');
		}else{
			Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.notupdatemessage").'</div>');
			return Redirect::to('/admin/fieldworkers/');
      	}
      	
      	/*$datetime = date("Y-m-d H:i:s");
		$productid=Input::get('hdnId');
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
		$fieldworkers= $fieldworkers->find($productid);
      	$fieldworkers->v_name =Input::get('txtUsername');
		$fieldworkers->v_phone_number=Input::get('txtPhoneNumber');
		$fieldworkers->v_email=Input::get('txtEmail');
		$fieldworkers->dt_birthdate=$bdate;
		$fieldworkers->v_address=Input::get('txtAddress');
		$fieldworkers->i_address_id=Input::get('hdnZipcode');
		if($filename!="")
		$fieldworkers->v_profile_pic=$filename;
		$fieldworkers->v_language=$language;
		$fieldworkers->v_profession=Input::get('txtProfession');
      	$fieldworkers->e_marital_status=Input::get('txtMaritalStatus');
      	$fieldworkers->e_gender=Input::get('txtGenderStatus');
      	$fieldworkers->dt_modify_date=$datetime;
      	$fieldworkers->v_ip=$_SERVER['REMOTE_ADDR'];
      	$result=$fieldworkers->save();
      	if($result){
      		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.updatemessage").'</div>');
      		return Redirect::to('/admin/fieldworkers/');
		}else{
			Session::flash('message', '<div class="alert alert-error" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.notupdatemessage").'</div>');
			return Redirect::to('/admin/fieldworkers/');
      	}*/
    }
    
    public function delete($id,$userid,$flag){
    $id=$this->decode($id);
    $userid=$this->decode($userid);
    $flag=$this->decode($flag);
    $fieldworkers= new Fieldworkers;
    $users= new Users;
    $msgdata['userdata']=$fieldworkers::where('bi_id',$id)->orderBy('bi_id', 'DESC')->get();
     
    $id= $fieldworkers->find($id);
    if($flag==0)
    	$id->e_status="Inactive";
    else
    	$id->e_status="Active";
    $result=$id->save();
	
    $uid= $users->find($userid);
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
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.$msg.'</div>');
    		$send=Mail::send('emails.notification',$msgdata, function($message) use ($msgdata)
    		{
    			$message->to($msgdata['userdata'][0]->v_email)->subject('User Account Notification');
    		});
    		
    		return Redirect::to('/admin/fieldworkers/');
    	}else{
    		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.notupdatemessage").'</div>');
    		return Redirect::to('/admin/fieldworkers/');
    	}
    }
    public function view($id=0){
    	$id=$this->decode($id);
    	$data['title']= "View Field Worker" . SITENAME;
    	
    	if($id!=0){
	    	$data['result']=DB::table('mct_field_workers')
			->leftJoin('mct_address', 'mct_field_workers.i_address_id', '=', 'mct_address.bi_id')
			->select('mct_field_workers.*', 'mct_address.v_taluka', 'mct_address.v_pincode', 'mct_address.v_taluka', 'mct_address.v_district', 'mct_address.v_state', 'mct_address.v_country')
			->where('mct_field_workers.bi_id',$id)
			->get();
	    	if(!empty($data['result'])){
				$data['result']=$data['result'][0];
	    	}else{
	    		return redirect('admin/fieldworkers');
	    	}
    	}else{
    		return redirect('admin/fieldworkers');
    	}
    	return view('fieldworkers/view',$data);
    }
    public function deleteSelected(){
    	$checkedRowArray = Input::get('chkCheckedBox');
    	if(count($checkedRowArray)>0){
	    	$fieldworkers= new Fieldworkers;
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
	    	$result = $fieldworkers->whereIn('bi_id', $idsArr)->update($data);
	    	
	    	$usercheckedRow = implode(",",$usrArr);
	    	$result = $users->whereIn('bi_id', $usrArr)->update($data1);
	    	if($result){
	    		Session::flash('message', '<div class="alert alert-success" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.deletemessage").'</div>');
	    		return Redirect::to('/admin/fieldworkers/');
	    	}else{
	    		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.notdeletemessage").'</div>');
	    		return Redirect::to('/admin/fieldworkers/');
	    	}
    	}else{
    		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.fieldworker").' '.trans("routes.notdeletemessage").'</div>');
    	}
    	return Redirect::to('/admin/fieldworkers/');
    
    }
    public function decode($id=0){
    	if($id){
    		$hashids = new Hashids();
    		$arr = $hashids->decode($id);
    		return (!empty($arr)) ? $id=$arr[0] : 0;
    	}else
    		return 0;
    }
    function random_password( $length = 8 ) {
    	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    	$password = substr( str_shuffle( $chars ), 0, $length );
    	return $password;
    }
    public function autocompletefieldworker()
    {
    	$arr=array();
    	$fieldworkers= new Fieldworkers;
    	$result=$fieldworkers::where('v_unique_code', 'LIKE', '%'.$_GET['chars'].'%')
    	->where('e_status','!=', 'Deleted')
		->orWhere('v_name', 'LIKE', '%'.$_GET['chars'].'%')->where('e_status','!=', 'Deleted')
    	->orWhere('v_phone_number', 'LIKE', '%'.$_GET['chars'].'%')->where('e_status','!=', 'Deleted')->take(10)->get();
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
    
    public function searchdatafieldworker($id="",$search=""){
    
    	$fieldworkers= new Fieldworkers;
    	if(isset($_GET['search']) && $_GET['search']!=""){
    		$data['result']=$fieldworkers::where('v_unique_code', 'LIKE', '%'.$_GET['search'].'%')
    		->where('e_status','!=', 'Deleted')
    		->orWhere('v_name', 'LIKE', '%'.$_GET['search'].'%')->where('e_status','!=', 'Deleted')
    		->orWhere('v_phone_number', 'LIKE', '%'.$_GET['search'].'%')->where('e_status','!=', 'Deleted')->paginate(15);
    		$data['searchTag']=$_GET['search'];
    	}else{
    		$data['searchTag']=$search;
    		$data['result']= $fieldworkers::where('e_status','!=', 'Deleted')->where('bi_id', $id)->paginate(15);
    	}
    	return view('fieldworkers/manage',$data);
    }
}