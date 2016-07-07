<?php
namespace App\Http\Controllers\RegisterMe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Registrar;
use App\Models\User;
use App\Http\Helpers;
use Hash;

class RegisterController extends Controller{
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
	public $title = "Register new users";
	public function __construct(){
		$this->helper = new Helpers();
		$this->helper->clearBen_Data();
	} 
	 
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
    public function register(Request $request)
    {	
		$reg = new Registrar();
        $validator = $reg->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
		
		// Create a new user		
		$user = new User;
		print_r($request->all());
		die("asd");
		
		$data_to_push = [
			'v_name' => $request->get('name'),
			'v_email' => $request->get('email'),
			'password' => Hash::make($request->get('password')),
		];
		if($user->insert($data_to_push)){
			die("done");
		}
		else{
			die("not done");
		}
		
		// Send a confirmation mail
		
		//
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