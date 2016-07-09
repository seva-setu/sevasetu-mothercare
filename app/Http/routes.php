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

//Route::get('/','WelcomeController@index');
Route::get('/','Admin\AdminController@index');
//Route::get('/','Auth\AuthController@getLogin');
Route::get('home','HomeController@index');
Route::get('book','BookingController@index');
Route::post('bookinsert','BookingController@insert');
Route::get('bookedit/{id}','BookingController@edit');
Route::get('bookdelete/{id}','BookingController@delete');
Route::post('bookupdate','BookingController@update');

/*Route::controllers(['auth' => 'Auth\AuthController',
					'book' => 'BookingController',
					'password' => 'Auth\PasswordController']);
*/

//Route:get('/auth/register','Auth\AuthController@getRegister');
Route::get('/auth/register','Admin\AdminController@getRegister');
Route::post('/auth/register','Admin\AdminController@postRegister');
//Route::get('/auth/register','Admin\RegisterController@getRegister');


Route::post('/language/chooser','LanguageController@chooser');

Route::get('admin/', 'Admin\AdminController@index');
Route::get('/admin/login', 'Admin\AdminController@index');
Route::post('/admin/login', 'Admin\AdminController@login');
Route::get('/admin/logout', 'Admin\AdminController@logout');
Route::get('/admin/productlists', 'Admin\AdminController@productlists');
Route::get('/admin/prod_edit/{id}','Admin\AdminController@prod_edit');
Route::get('/admin/prod_edit','Admin\AdminController@prod_edit');
Route::get('/admin/prod_delete/{id}','Admin\AdminController@prod_delete');
Route::post('/admin/prod_update','Admin\AdminController@prod_update');
Route::post('/admin/prod_insert', 'Admin\AdminController@prod_insert');
Route::get('/admin/changepassword', 'Admin\AdminController@changepassword');
Route::post('/admin/dochangepassword', 'Admin\AdminController@dochangepassword');
Route::post('/admin/checkEmail', 'Admin\AdminController@checkEmail');
Route::get('/admin/userprofile', 'Admin\AdminController@userprofile');
Route::post('/admin/editprofile', 'Admin\AdminController@editprofile');
Route::get('/admin/manageInterventionPoint', 'Admin\AdminController@manageInterventionPoint');
Route::post('/admin/updateInterventionPoint', 'Admin\AdminController@updateInterventionPoint');
Route::post('/admin/intervation_delete', 'Admin\AdminController@intervation_delete');
Route::get('/admin/addlocation', 'Admin\AdminController@addlocation');
Route::post('/admin/editlocation', 'Admin\AdminController@editlocation');
Route::post('/admin/forgotPassword', 'Admin\AdminController@forgotPassword');
Route::post('/admin/checkEmailLogin', 'Admin\AdminController@checkEmailLogin');
Route::get('/admin/updatePassowrd/{id}', 'Admin\AdminController@updatePassowrd');
Route::post('/admin/changeforgotpassword', 'Admin\AdminController@changeforgotpassword');
Route::get('/admin/changeuserpassword/{id}', 'Admin\AdminController@changeuserpassword');
Route::post('/admin/dochangeuserpassword', 'Admin\AdminController@dochangeuserpassword');
Route::get('/admin/searchdataaddress/{name}/{name1}/{name2}', 'Admin\AdminController@searchdataaddress');
Route::post('/admin/editaddress', 'Admin\AdminController@editaddress');




//for call chamption 
Route::get('/admin/callchampions', 'Admin\CallchampionsController@index');
Route::get('/admin/callchampions/edit/{id}','Admin\CallchampionsController@edit');
Route::get('/admin/callchampions/edit','Admin\CallchampionsController@edit');
Route::get('/admin/callchampions/delete/{id}/{userid}/{flag}','Admin\CallchampionsController@delete');
Route::post('/admin/callchampions/update','Admin\CallchampionsController@update');
Route::post('/admin/callchampions/add', 'Admin\CallchampionsController@add');
Route::post('/admin/callchampions/deleteSelected', 'Admin\CallchampionsController@deleteSelected');
Route::get('/admin/dashboard', 'Admin\AdminController@dashboard');
Route::get('/admin/callchampions/view/{id}', 'Admin\CallchampionsController@view');
Route::get('/admin/callchampions/autocompletecallchampion', 'Admin\CallchampionsController@autocompletecallchampion');
Route::get('/admin/callchampions/searchdatacallchampion/{id}/{search}', 'Admin\CallchampionsController@searchdatacallchampion');
Route::get('/admin/callchampions/searchdatacallchampion', 'Admin\CallchampionsController@searchdatacallchampion');
//for Fields Worker
Route::get('/admin/fieldworkers', 'Admin\FieldworkerController@index');
Route::get('/admin/fieldworkers/edit/{id}','Admin\FieldworkerController@edit');
Route::get('/admin/fieldworkers/edit','Admin\FieldworkerController@edit');
Route::get('/admin/fieldworkers/delete/{id}/{userid}/{flag}','Admin\FieldworkerController@delete');
Route::post('/admin/fieldworkers/update','Admin\FieldworkerController@update');
Route::post('/admin/fieldworkers/add', 'Admin\FieldworkerController@add');
Route::get('/admin/fieldworkers/view/{id}', 'Admin\FieldworkerController@view');
Route::post('/admin/fieldworkers/deleteSelected', 'Admin\FieldworkerController@deleteSelected');
Route::get('/admin/fieldworkers/autocompletefieldworker', 'Admin\FieldworkerController@autocompletefieldworker');
Route::get('/admin/fieldworkers/searchdatafieldworker/{id}/{search}', 'Admin\FieldworkerController@searchdatafieldworker');
Route::get('/admin/fieldworkers/searchdatafieldworker', 'Admin\FieldworkerController@searchdatafieldworker');
//for Fields Admin
Route::get('/admin/adminusrs', 'Admin\AdminusrController@index');
Route::get('/admin/adminusrs/edit/{id}','Admin\AdminusrController@edit');
Route::get('/admin/adminusrs/edit','Admin\AdminusrController@edit');
Route::get('/admin/adminusrs/delete/{id}/{userid}/{flag}','Admin\AdminusrController@delete');
Route::post('/admin/adminusrs/update','Admin\AdminusrController@update');
Route::post('/admin/adminusrs/add', 'Admin\AdminusrController@add');
Route::post('/admin/adminusrs/deleteSelected', 'Admin\AdminusrController@deleteSelected');
Route::get('/admin/adminusrs/view/{id}', 'Admin\AdminusrController@view');
Route::get('/admin/adminusrs/autocompleteadmin', 'Admin\AdminusrController@autocompleteadmin');
Route::get('/admin/adminusrs/searchdataadmin/{id}/{search}', 'Admin\AdminusrController@searchdataadmin');
Route::get('/admin/adminusrs/searchdataadmin', 'Admin\AdminusrController@searchdataadmin');

// Add this route for checkout or submit form to pass the item into paypal
Route::get('payment','PaypalController@index');
Route::get('payment/postPayment','PaypalController@postPayment');
// this is after make the payment, PayPal redirect back to your site
Route::get('payment/status', 'PaypalController@getPaymentStatus');
Route::get('product', 'ProductController@index');

//for beneficiary
Route::get('/admin/beneficiary', 'Admin\BeneficiaryController@index');
Route::get('/admin/beneficiary/all/', 'Admin\BeneficiaryController@all');

Route::get('/admin/beneficiary/edit/{id}','Admin\BeneficiaryController@edit');
Route::get('/admin/beneficiary/edit','Admin\BeneficiaryController@edit');
Route::post('/admin/beneficiary/add','Admin\BeneficiaryController@add');
Route::post('/admin/beneficiary/update','Admin\BeneficiaryController@update');
Route::get('/admin/beneficiary/view/{id}', 'Admin\BeneficiaryController@view');
Route::get('/admin/beneficiary/delete/{id}/{flag}','Admin\BeneficiaryController@delete');
Route::post('/admin/beneficiary/deleteSelected', 'Admin\BeneficiaryController@deleteSelected');
Route::get('/admin/beneficiary/autocompletebeneficiary', 'Admin\BeneficiaryController@autocompletebeneficiary');
Route::get('/admin/beneficiary/searchdatabeneficiary/{id}/{search}', 'Admin\BeneficiaryController@searchdatabeneficiary');
Route::get('/admin/beneficiary/searchdatabeneficiary', 'Admin\BeneficiaryController@searchdatabeneficiary');
Route::get('/admin/beneficiary/demoexcel', 'Admin\BeneficiaryController@demoexcel');
Route::post('/admin/beneficiary/importExcel', 'Admin\BeneficiaryController@importExcel');
Route::post('/admin/beneficiary/getcitylists', 'Admin\BeneficiaryController@getcitylists');
Route::post('/admin/beneficiary/gettalukalists', 'Admin\BeneficiaryController@gettalukalists');
Route::get('/admin/beneficiary/autocompleteaddress', 'Admin\BeneficiaryController@autocompleteaddress');
Route::get('/admin/beneficiary/autocompletebenaddress', 'Admin\BeneficiaryController@autocompletebenaddress');
Route::get('/admin/beneficiary/searchdataaddress/{name}/{name1}/{name2}', 'Admin\BeneficiaryController@searchdataaddress');
Route::get('/admin/beneficiary/downlaodsample', 'Admin\BeneficiaryController@downlaodsample');
Route::get('/admin/beneficiary/searchbenificiary', 'Admin\BeneficiaryController@searchbenificiary');
Route::post('/admin/beneficiary/checkZipcode', 'Admin\BeneficiaryController@checkZipcode');
Route::post('/admin/beneficiary/getAddressById', 'Admin\BeneficiaryController@getAddressById');
Route::post('/admin/beneficiary/searchbenificiarydata', 'Admin\BeneficiaryController@searchbenificiarydata');
Route::post('/admin/beneficiary/getVillageByZipcode', 'Admin\BeneficiaryController@getVillageByZipcode');
Route::post('/admin/beneficiary/getCallChamption', 'Admin\BeneficiaryController@getCallChamption');
Route::post('/admin/beneficiary/selCallChamption', 'Admin\BeneficiaryController@selCallChamption');
Route::post('/admin/beneficiary/searchCallchampion', 'Admin\BeneficiaryController@searchCallchampion');
Route::get('/admin/beneficiary/autoCallchampion', 'Admin\BeneficiaryController@autoCallchampion');
Route::post('/admin/beneficiary/getBeneficiaryReport', 'Admin\BeneficiaryController@getBeneficiaryReport');
Route::post('/admin/beneficiary/updateBeneficiaryReport', 'Admin\BeneficiaryController@updateBeneficiaryReport');
Route::post('/admin/beneficiary/getCallShummery', 'Admin\BeneficiaryController@getCallShummery');
Route::post('/admin/beneficiary/updateBeneficiaryCall', 'Admin\BeneficiaryController@updateBeneficiaryCall');
Route::get('/admin/beneficiary/sendmailforemegency/{id}/{id1}/{name}', 'Admin\BeneficiaryController@sendmailforemegency');
Route::get('/admin/beneficiary/filterbyfieldworker/{id}','Admin\BeneficiaryController@filterByFW');
Route::get('/admin/beneficiary/filterbycallchampion/{id}','Admin\BeneficiaryController@filterByCC');
Route::get('/admin/beneficiary/{name}','Admin\BeneficiaryController@filterByAssigned');

Route::get('/admin/beneficiary/filterbyfieldworker/{id?}', function($id = null){
	if (!$id) {	return Redirect::to('/admin/beneficiary/'); }
});

Route::get('/admin/beneficiary/filterbyfieldworker/{id?}', function($id = null){
	if(!$id) { return Redirect::to('/admin/beneficiary/'); }
});

Route::get('/admin/beneficiary/view/{id?}', function($id = null){
	if (!$id) { return Redirect::to('/admin/beneficiary/'); }
});

Route::get('/admin/beneficiary/{filterassigned}', function($filterassigned = null){
	if(!$filterassigned)  { return Redirect::to('/admin/beneficiary/'); }
});

	
	

//Check List 
Route::get('/admin/checklist/','Admin\ChecklistController@index');
Route::get('/admin/checklist/add/','Admin\ChecklistController@add');
Route::get('/admin/checklist/edit/{id}','Admin\ChecklistController@edit');
Route::post('/admin/checklist/addcategory','Admin\ChecklistController@addCategory');
Route::post('/admin/checklist/addcheckmaster','Admin\ChecklistController@addCheckMaster');
Route::post('/admin/checklist/update','Admin\ChecklistController@update');
Route::get('/admin/beneficiary/userchecklist/{id}','Admin\BeneficiaryController@checklistEdit');
Route::post('/admin/beneficiary/userchecklist','Admin\BeneficiaryController@userchecklist');
Route::post('/admin/beneficiary/getUserCheckById','Admin\BeneficiaryController@getUserCheckById');
Route::post('/admin/beneficiary/saveUserCheckById','Admin\BeneficiaryController@saveUserCheckById');

Route::get('/admin/checklist/edit/{id?}', function($id = null)
{
	if (!$id)
		return Redirect::to('/admin/checklist');
});


//for assign callchampion
Route::get('/admin/assigncallchampion', 'Admin\AssignCallController@index');
Route::post('/admin/assigncallchampion/getbeneficiary', 'Admin\AssignCallController@getbeneficiary');
Route::post('/admin/assigncallchampion/assigncallchamption', 'Admin\AssignCallController@assigncallchamption');



//for assign beneficiary
Route::get('/admin/assignbeneficiary/{id}', 'Admin\AssignBeneficiaryController@index');
Route::get('/admin/assignbeneficiary', 'Admin\AssignBeneficiaryController@index');
Route::get('/admin/assignbeneficiary/autocompletebeneficiary', 'Admin\AssignBeneficiaryController@autocompletebeneficiary');
Route::get('/admin/assignbeneficiary/searchdatabeneficiary/{id}/{search}', 'Admin\AssignBeneficiaryController@searchdatabeneficiary');
Route::get('/admin/assignbeneficiary/searchdatabeneficiary', 'Admin\AssignBeneficiaryController@searchdatabeneficiary');
Route::post('/admin/assignbeneficiary/fillbeneficiaryreport', 'Admin\AssignBeneficiaryController@fillbeneficiaryreport');
Route::get('/admin/assignbeneficiary/searchbenificiarydata/{date}', 'Admin\AssignBeneficiaryController@searchbenificiarydata');
Route::get('/admin/assignbeneficiary/edit/{id}', 'Admin\AssignBeneficiaryController@edit');
Route::get('/admin/assignbeneficiary/view/{id}', 'Admin\AssignBeneficiaryController@view');
Route::post('/admin/assignbeneficiary/update', 'Admin\AssignBeneficiaryController@update');
Route::get('/admin/assignbeneficiary/searchdataaddress/{name}/{name1}/{name2}/{name3}/{name4}', 'Admin\AssignBeneficiaryController@searchdataaddress');
Route::post('/admin/assignbeneficiary/downlaodreport', 'Admin\AssignBeneficiaryController@downlaodreport');

//Weekly Call List
Route::get('/admin/weeklycalllist', 'Admin\WeeklyCalllistController@index');
Route::get('/admin/weeklycalllist/searchbenificiarydata/{date}', 'Admin\WeeklyCalllistController@searchbenificiarydata');
Route::post('/admin/weeklycalllist/showmore','Admin\WeeklyCalllistController@showMoreCallList');
Route::post('/admin/weeklycalllist/downloadreport', 'Admin\WeeklyCalllistController@DownloadReport');

?>