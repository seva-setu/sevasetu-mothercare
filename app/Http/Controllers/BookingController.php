<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Request;
use Illuminate\Support\Facades\Redirect;
class BookingController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "Booking" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	protected $model;
	public $title="Booking";
	public function __construct(){
		$this->middleware('auth');
	}
	
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index(){// defualt method
		$data['title']=$this->title;
		$booking= new Booking;
		$data['result']= $booking::where('v_status', '', "enable")->take(10)->get();
		return view('booking/manage',$data);
	}
	public function insert(){// add record 
		$booking= new Booking;
		$booking->v_first_name =Request::input('txtFirstname');
		$booking->v_last_name=Request::input('txtLastname');
		$booking->v_email=Request::input('txtEmail');
		$result=$booking->save();
		if($result){
			return Redirect::to('book');
		}
		else{
			return Redirect::to('book');
		}
	}
	public function edit($id){// edit record
		$booking= new Booking;
		$data['result']= $booking->find($id);
		$data['action']="/bookupdate";
		return view('booking.form',$data);	
	}	
	public function update(){
		$booking= new Booking;
		$booking=Request::input('hdnId');
		$id= $booking->find($booking);
		$booking->v_first_name =Request::input('txtFirstname');
		$booking->v_last_name=Request::input('txtLastname');
		$booking->v_email=Request::input('txtEmail');
		$result=$booking->save();
		if($result){
			return Redirect::to('book');
		}
		else{
			return Redirect::to('book');
		}
	}
	public function delete($id){
		$booking= new Booking;
		$id= $booking->find($id);
		$id->v_status="deleted";
		$result=$id->save();
		if($result){
			return Redirect::to('book');
		}
		else{
			return Redirect::to('book');
		}
	}
}
