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
// Route::get('/admins', 'Admin\CallchampionsController@list_admins');

Route::get('/mothers_actions/{id}', 'Admin\CallchampionsController@list_all_actions');

Route::get('/actions', 'Admin\AdminController@action_items');
Route::post('/actions/{id}', 'Admin\AdminController@update_status');
Route::post('/actions/{id}/unresolve', 'Admin\AdminController@unresolve_status');


Route::get('/download_action_items', 'Admin\AdminController@download_action_items');
Route::get('/data/upload', 'Admin\AdminController@upload_data');
//Route::get('/data/upload', 'Admin\CallchampionsController@upload_data');
Route::post('/data/upload','Admin\BeneficiaryController@importExcel');
Route::post('/data/final_upload','Admin\BeneficiaryController@Excel_data_upload');

Route::get('/download', 'Admin\BeneficiaryController@downloadExcel');



Route::get('/admins', function()
	{
		return redirect(url('data/upload'));
	});//'Admin\AdminController@admin_dashboard');

Route::get('/duedateresolve','Admin\AdminController@due_date_resolve');
Route::post('/duedateresolve/{id}/{ddate}/accept', 'Admin\AdminController@due_date_accept');
Route::post('/duedateresolve/{id}/reject', 'Admin\AdminController@due_date_reject');
Route::get('/mycalls', 'Admin\WeeklyCalllistController@list_all_calls');
Route::get('/mycalls/view/{id}', 'Admin\WeeklyCalllistController@list_specific_call_details');
Route::get('/checklist', 'Admin\WeeklyCalllistController@get_master_checklist');
Route::post('/mycalls/update/{id}', 'Admin\CallchampionsController@update_call');
Route::get('/callchampions', 'Admin\AdminController@callchampions');
Route::post('/mentor/assign', 'Admin\AdminController@assign_mentor');
Route::post('/callchampion/status/update', 'Admin\AdminController@update_callchampion_status');
Route::post('/assign/mothers', 'Admin\AdminController@assign_mothers');
Route::get('/callchampion/promote/{id}', 'Admin\AdminController@promote_callchampion');

// Route::post('/assign/mothers/', 'Admin\AdminController@post_assign_mothers');

/////////////////////////////////////

////////// For testing ///////////////////
Route::get('/admins/test','Admin\AdminDashboardController@actionitems_lastweek');
Route::get('/analysis','Admin\AdminDashboardController@get_data');
Route::get('/analysis/overall_stat','Admin\AdminDashboardController@overall_stat');
Route::get('/analysis/mother','Admin\AdminDashboardController@mother_info');
Route::get('/analysis/field_worker','Admin\AdminDashboardController@field_worker_info');
Route::get('/analysis/call_champion','Admin\AdminDashboardController@call_champion_info');
Route::get('/analysis/call_champion/{id}','Admin\AdminDashboardController@call_champion_analysis');


?>
