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
Route::get('/admin/login', 'Admin\AdminController@index');
Route::post('/admin/login', 'Admin\AdminController@login');
Route::get('/admin/logout', 'Admin\AdminController@logout');
Route::get('/admin/changepassword', 'Admin\AdminController@changepassword');
Route::post('/admin/dochangepassword', 'Admin\AdminController@dochangepassword');
Route::post('/admin/forgotPassword', 'Admin\AdminController@forgotPassword');
Route::post('/admin/checkEmailLogin', 'Admin\AdminController@checkEmailLogin');
Route::get('/admin/updatePassowrd/{id}', 'Admin\AdminController@updatePassowrd');
Route::post('/admin/changeforgotpassword', 'Admin\AdminController@changeforgotpassword');
Route::get('/admin/changeuserpassword/{id}', 'Admin\AdminController@changeuserpassword');
Route::post('/admin/dochangeuserpassword', 'Admin\AdminController@dochangeuserpassword');
Route::get('/admin/searchdataaddress/{name}/{name1}/{name2}', 'Admin\AdminController@searchdataaddress');
Route::post('/admin/editaddress', 'Admin\AdminController@editaddress');
Route::get('/admin/FAQ', 'Admin\AdminController@faq');

/////////// core application ///////////////////
Route::get('/admin/mothers', 'Admin\CallchampionsController@list_mothers');
Route::get('/admin/mycalls', 'Admin\WeeklyCalllistController@list_all_calls');
Route::get('/admin/mycalls/view/{id}', 'Admin\WeeklyCalllistController@list_specific_call_details');
Route::get('/admin/checklist', 'Admin\WeeklyCalllistController@get_master_checklist');
Route::post('/admin/mycalls/update/{id}', 'Admin\CallchampionsController@update_call');

/////////////////////////////////////
?>