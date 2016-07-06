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

class AssignCallController extends Controller{
	protected $model;
	public $title="Admin User";
	public function __construct(){
		$userinfo=Session::get('user_logged');
		if(!isset($userinfo['b_id'])){
			Redirect::to('/admin/')->send();
		}
	}
	
public function  index(){
	$data['title']=$this->title;
	$data['result']=array();
	$data['callchampionlists']=DB::table('mct_call_champions')->where('e_status', 'Active')->orderBy('bi_id', 'DESC')->get();
	return view('assigncallchampion/manage',$data);
}
public function getbeneficiary(){
	$ddate=str_replace("/", "-",Input::get('startDate'));
	$startdate=date("Y-m-d H:i:s",strtotime($ddate));
	$devdate=str_replace("/", "-",Input::get('endDate'));
	$enddate=date("Y-m-d H:i:s",strtotime($devdate)); 
	$diff=date_diff(date_create($startdate),date_create($enddate));
	$day=$diff->format("%a");
	$result=DB::table('mct_intervention_point')->where('e_status','Active')->get();
	$extr="";
	$i=0;
	foreach ($result as $k=>$v){
		$i++;
		$point1=($v->i_week*7);
		$point2=($v->i_week*7);
		$extr.=" ( DATEDIFF('".$startdate."',dt_due_date) >=".$point1." && DATEDIFF('".$enddate."',dt_due_date) <=".$point2." )";
		if($i < count($result))
			$extr.=" or ";
	}
	//echo 'select mct_beneficiary.*,DATEDIFF("'.$startdate.'",dt_due_date) as firstpoint,DATEDIFF("'.$enddate.'",dt_due_date) as secondpoint  from mct_beneficiary where mct_beneficiary.bi_calls_champion_id IS NULL and mct_beneficiary.e_status="Active" and ('.$extr.')';
	$data['result'] = DB::select('select mct_beneficiary.*,DATEDIFF("'.$startdate.'",dt_due_date) as firstpoint,DATEDIFF("'.$enddate.'",dt_due_date) as secondpoint from mct_beneficiary where mct_beneficiary.bi_calls_champion_id IS NULL and mct_beneficiary.e_status!="Deleted" and ('.$extr.')');
	//print_r($data['result']); exit;
	return view('assigncallchampion/ajax_beneficiary',$data);
}
public function assigncallchamption(){
	$checkedRowArray = Input::get('chkCheckedBox');
	$calluser=Input::get('callChamptionUser');
	$date=date('Y-m-d');
	$idsArr = array();
	$weekArr=array();
	if($calluser > 0){
		$data = array(
				'bi_calls_champion_id' =>$calluser 
		);
		foreach($checkedRowArray as $value){
			$value=explode("_", $value);
			$idsArr[] = $value[0];
			$weekArr[] = $value[1];
		}
		$checkedRow = implode(",",$idsArr);
		$result = DB::table('mct_beneficiary')->whereIn('bi_id', $idsArr)->update($data);//delete beficiary
		$benelist = DB::table('mct_beneficiary')->whereIn('bi_id', $idsArr)->get();
		$i=0;
		foreach ($benelist as $k=>$v){
			$data=array(
					'bi_beneficiary_id' =>$v->bi_id,
					'bi_field_worker_id'=>$v->bi_field_worker_id,
					'bi_calls_champion_id'=>$v->bi_calls_champion_id,
					'dt_created_at'=>$date,
					'dt_updated_at'=>$date,
					'v_ip'=>$_SERVER['REMOTE_ADDR']
					);
			if($idsArr[$i]==$v->bi_id){
				$data['i_week']=$weekArr[$i];
			}
			$data=DB::table('mct_callchampion_report')->insert($data);//insert excel data into database
			$i++;			
		}
		//send mail to call champion with assign beneficiary lists
		$emailarr=DB::table('mct_call_champions')->where('bi_id',$calluser)->where('e_status','Active')->get();
		$email=$emailarr[0]->v_email;
		Session::put('email', $email);
		$emaildata['result']=DB::table('mct_beneficiary')->where('e_status', 'Active')->where('bi_calls_champion_id', $calluser)->orderBy('bi_id', 'DESC')->paginate(15);
		$send=Mail::send('emails.assignbeneficiary',$emaildata, function($message)
		{
			$email = Session::get('email');
			$message->to($email)->subject('Program Coordinater Assign Beneficiary List');
		});
		Session::forget('email');
		if($result){
			Session::flash('message', '<div class="alert alert-success" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.assingtasksucc").'</div>');
			return Redirect::to('/admin/assigncallchampion/');
		}else{
			Session::flash('message', '<div class="alert alert-error" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.assingtasknotsucc").'</div>');
			return Redirect::to('/admin/assigncallchampion/');
		}
	}else{
		Session::flash('message', '<div class="alert alert-error" style="clear:both;">
	              <button data-dismiss="alert" class="close" type="button">×</button>'.trans("routes.valid_call").'</div>');
		return Redirect::to('/admin/assigncallchampion/');
	}
}
}
?>