<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Session;
use Cache;

class LanguageController extends Controller{
	public function chooser(){
		Cache::forget('ben_data');
		Session::set('locale',Input::get('locale'));
		return Redirect::back();
	}
}
?>