<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Helpers;
use Request;
use App\Models\Checklist;
use Input;
use Validator;
use App\Models\App\Models;

class ChecklistController extends Controller{
	public $userid;
	public $usertype;
	protected $helper;
	protected $role_permissions;
	
	public function __construct(){
		$userinfo=Session::get('user_logged');
		//check for valid use
		if(!isset($userinfo['b_id'])){
			Redirect::to('/admin/')->send();
		}
		$this->usertype=$userinfo['v_role'];
		$this->userid=$userinfo['b_id'];
		
		$this->helper = new Helpers();
		$this->role_permissions = $this->helper->checkpermission(Session::get('user_logged')['v_role']);
		$this->helper->clearBen_Data();
	}

	public function index(){
		if($this->role_permissions['canaddchecklist']){
			$data['title']= "View Checklist" . SITENAME;
			/*
			 * Get Checklist data
			 */
			$checklist = new Checklist;
			$data['descr'] = $checklist->getChecklistMaster();
			$data['descrBaby'] = $checklist->getChecklistBaby();
			$data['categoriesMother'] = $checklist->getCategoriesMother();
			$data['categoriesBaby'] = $checklist->getCategoriesBaby();
			return view('admin.managechecklist',$data);
		}else{
			Redirect::to('/admin/')->send();
		}
	}
	
	public function add(){
		if($this->role_permissions['canaddchecklist']){
			$checklist = new Checklist;
			$data['title'] = 'Checklist Add' . SITENAME;
			$data['action'] = 'addcheckmaster';
			
			$data['categories'] = $checklist->getCategories();
			return view('admin.checklist',$data);
		}else{
			Redirect::to('/admin/')->send();
		}
	}
	
	public function edit($id){
		if($this->role_permissions['canaddchecklist']){
			$data['title'] = 'Checklist Edit' . SITENAME;
			$data['action'] = "update";
			
			$checklist = new Checklist;
			$data['categories'] = $checklist->getCategories();
			$data['checklistmaster'] = $checklist->getCategoryById($id);
			
			return view('admin.editchecklist',$data);
		}else{
			Redirect::to('/admin/')->send();
		}
	}
	
	/*
	 * Update Checklist Data
	 */
	public function update(){
		$checklist = new Checklist;
		Validator::extend('alpha_spaces', function ($attribute, $value, $parameters) {
			return $this->val_AlphaSpace(Input::get('description'));
		});
		
		
		
			$userdata = array(
					'Category' 	  		=> trim(ucfirst(Input::get('sltCategory'))),
					'Description' 		=> trim(Input::get('description')),
					'Recommended time' => trim(Input::get('recommended_time')),
					'Response' 			=> trim(Input::get('optionsMerge')),
					'Type' 				=> trim(Input::get('sltForType')),
					'Id'				=> trim(Input::get('hndChklistId'))
			);
				
			$rules = array(
					'Category' 		=> 'required|integer',
					'Description' 	=> 'required|max:100|alpha_spaces',
					'Response' 		=> 'required|max:255',
					'Type'			=> 'required|integer',
			);

				
			$validator = Validator::make($userdata, $rules);
		
			if ($validator->fails()){
				//If Validation failed then redirect back.
				return Redirect::back()->withErrors($validator)->withInput();
			}else{
				
				$res = $checklist->saveChecklistMaster($userdata,$userdata['Id']);
				if($res){
					//success
					Session::flash('success',trans('routes.updatemessage'));
					return Redirect::to('/admin/checklist/');
				}else{
					Session::flash('danger',trans('routes.notupdatemessage'));
					return Redirect::to('/admin/checklist/');
				}
			}
	}
	
	public function addCategory(){
		/* 
		$checklist = new Checklist;
		
		if(Request::ajax()){
			
			Validator::extend('alpha_spaces', function ($attribute, $value, $parameters) {
				return $this->val_AlphaSpace(Input::get('category_name'));
			});
			
			$userdata = array(
					'Category' => trim(ucfirst(Input::get('category_name'))),
			);
			
			$rules = array(
					'Category' => 'required|alpha_spaces|max:50|unique:mct_checklist_type,v_type',
			);
			
			$messages = array(
					'required' 	=> 'The :attribute cannot be empty.',
					'unique' 	=> 'Category already exists.',
					'alpha_dash' => 'Category must be alpha numeric.'
			);
			
			$validator = Validator::make($userdata, $rules,$messages);
			if ($validator->fails()){
				//If Validation failed then redirect back.
				return response()->json(array(
						'fail' => true,
						'errors' => $validator->getMessageBag()->toArray()
				));
			}else{
				$id= 0;
				$res = $checklist->save_category($userdata,$id);
				if($res>0){
					return response()->json(array('fail'=>false,'message'=>'Successfully saved.','insertid'=>$res));
				}
			}
			
		}	*/
	}
	
	
	public function addCheckMaster(){
		if($this->role_permissions['canaddchecklist']){
			$checklist = new Checklist;
			Validator::extend('alpha_spaces', function ($attribute, $value, $parameters) {
				return $this->val_AlphaSpace(Input::get('description'));
			});
	
					
			$userdata = array(
					'Category' 	  		=> trim(ucfirst(Input::get('sltCategory'))),
					'Description' 		=> trim(Input::get('description')),
					//'Recommended Type'  => trim(Input::get('rdDefined')),
					'Recommended time' => trim(Input::get('recommended_time')),
					'Response' 			=> trim(Input::get('optionsMerge')),
					'Type' 				=> trim(Input::get('sltForType'))
			);
				
			$rules = array(
					'Category' 		=> 'required|integer',
					'Description' 	=> 'required|max:100|alpha_spaces',
					'Response' 		=> 'required|max:255',
					'Type'		=> 'required|integer'
					
			);
			
			$validator = Validator::make($userdata, $rules);
			if ($validator->fails()){
				//If Validation failed then redirect back.
				return Redirect::to('/admin/checklist/add')->withErrors($validator)->withInput();
			}else{
				$id=0;
				$res = $checklist->saveChecklistMaster($userdata,$id);
				if($res){
					//success
					Session::flash('success',trans('routes.addmessage'));
					return Redirect::to('/admin/checklist/');
				}else{
					Session::flash('danger',trans('routes.notaddmessage'));
					return Redirect::to('/admin/checklist/');
				}
			}
		}else{
			return Redirect::to('/admin/');
		}
	}
	
	private function val_AlphaSpace($input){
		if(preg_match("/^[a-zA-Z0-9-'(),_ ]+$/", $input)){
			return true;
		}else{
			return false;
		}
	}

	
}