<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/	

Route::get('/test','Admin\BeneficiaryController@tester_method');
Route::get('/uploadmothermagic','Admin\BeneficiaryController@upload_mother');
Route::get('/','Admin\AdminController@landing');
///////////// Havent tested many of these routes ///////////
Route::get('/auth/register','Admin\AdminController@getRegister');
Route::post('/auth/register','Admin\AdminController@postRegister');
Route::post('/auth/validate', 'Admin\AdminController@validate_phonenumber');

Route::post('/language/chooser','LanguageController@chooser');
Route::get('/login', 'Admin\AdminController@index');
Route::post('/login', 'Admin\AdminController@login');
Route::get('/logout', 'Admin\AdminController@logout');
Route::get('/changepassword', 'Admin\AdminController@changepassword');
Route::post('/dochangepassword', 'Admin\AdminController@dochangepassword');
Route::post('/forgotPassword', 'Admin\AdminController@forgotPassword');
Route::post('/checkEmailLogin', 'Admin\AdminController@checkEmailLogin');
Route::get('/updatePassowrd/{id}', 'Admin\AdminController@updatePassowrd');
Route::post('/changeforgotpassword', 'Admin\AdminController@changeforgotpassword');
Route::get('/changeuserpassword/{id}', 'Admin\AdminController@changeuserpassword');
Route::post('/dochangeuserpassword', 'Admin\AdminController@dochangeuserpassword');
Route::get('/searchdataaddress/{name}/{name1}/{name2}', 'Admin\AdminController@searchdataaddress');
Route::post('/editaddress', 'Admin\AdminController@editaddress');
Route::get('/FAQ', 'Admin\AdminController@faq');
Route::get('/FAQ/checklist', 'Admin\AdminController@faq_checklist');

/////////// core application ///////////////////
Route::get('/mothers', 'Admin\CallchampionsController@list_mothers');
Route::get('/admins', 'Admin\AdminController@admin_dashboard');
Route::get('/mycalls', 'Admin\WeeklyCalllistController@list_all_calls');
Route::get('/mycalls/view/{id}', 'Admin\WeeklyCalllistController@list_specific_call_details');
Route::get('/checklist', 'Admin\WeeklyCalllistController@get_master_checklist');
Route::post('/mycalls/update/{id}', 'Admin\CallchampionsController@update_call');
Route::get('/callchampions', 'Admin\AdminController@callchampions');

/////////////////////////////////////
?>