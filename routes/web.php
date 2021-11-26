<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test','driverController@test');
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 1111;
    // return what you want
});

Route::get('/home', 'DashboardController@index')->name('home');
Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');


// uncaps routes
Route::get('/uncaps',function ()
{
    return redirect('/uncaps/login');
});
Route::get('/uncaps/login','uncapController@prelogin');
Route::post('/uncaps/postlogin','uncapController@login')->name('uncaps.login');
Route::get('/uncaps/logout','uncapController@logout')->name('uncaps.logout');
Route::post('/uncaps/store','uncapController@store'); 
Route::get('/uncaps/dashboard','uncapController@index');

//end uncaps routes
//new excel route
Route::post('/tripswithbranch/filtered','BusinessController@tripwithbranchfilter')->name('filtered.excel');
Route::resource('driver', 'DriverController');
Route::resource('trip', 'TripController');
Route::get('/getlogout', 'UserController@getLogout')->name('user.getlogout');

Route::post('/postsignin', 'UserController@postSignin')->name('user.postsignin');
Route::get('/logtime','DriverController@logger');

Route::get('/business-index', 'BusinessController@index')->name('business.index');

Route::get('/profile', 'BusinessController@profile')->name('business.profile'); 
Route::get('/business-getstaff', 'BusinessController@getStaff')->name('business.getstaff'); 

Route::post('/business-poststaff', 'BusinessController@postStaff')->name('business.poststaff');

Route::get('/staff', 'BusinessController@staffRegister')->name('business.staffregister'); 
Route::post('/staff', 'BusinessController@postStaffRegister')->name('business.poststaffregister'); 

Route::get('/staff-requests', 'BusinessController@staffRequests')->name('business.staffrequests'); 
Route::get('/indstafftrips', 'BusinessController@indStaffTrips')->name('business.indstafftrips'); 

Route::post('/staff-triprequest', 'UserController@staffTripRequest')->name('user.stafftriprequest');
Route::get('/trip-alltrips', 'TripController@allTrips')->name('trip.alltrips'); 
Route::get('/trip-approve-triprequest/{id}', 'TripController@approveTripRequest')->name('trip.approvetriprequest'); 
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/getallcompletedtrips', 'DashboardController@getAllCompletedTrips')->name('dashboard.getallcompletedtrips');
Route::get('/getallusers', 'DashboardController@getAllUsers')->name('dashboard.getallusers');
Route::get('/dash-profile', 'DashboardController@profile')->name('dashboard.profile');

Route::get('/get-allbusiness-account', 'DashboardController@getAllBusinessAccount')->name('dashboard.getallbusinessaccount');
Route::get('/setLargeData/{id}', 'DashboardController@setLargeData')->name('setLargeData');
Route::get('/removeLargeData/{id}', 'DashboardController@removeLargeData')->name('removeLargeData');

Route::get('/get-business-trips/{company}/{businesscode}', 'DashboardController@getBusinessTrips')->name('dashboard.getbusinesstrips');


Route::get('/get-business-staffs/{company}/{businesscode}', 'DashboardController@getBusinessStaffs')->name('dashboard.getbusinessstaffs');

Route::get('/trip-decline-trip-request/{id}', 'TripController@declineTripRequest')->name('trip.declinetriprequest');

Route::get('/delete-staff/{id}', 'BusinessController@deleteStaff')->name('business.deletestaff'); 
Route::post('/trip-search', 'TripController@searchTrip')->name('trip.searchtrip');
Route::post('/update-driver/{id}', 'DriverController@updateDriver')->name('driver.updatedriver');
Route::any('/report-filter', 'DashboardController@searchTrip')->name('dashboard.searchtrip');

Route::get('/config-index', 'ConfigController@index')->name('config.index'); 

Route::post('/config-store', 'ConfigController@store')->name('config.store');
Route::post('/dash-staff-search', 'DashboardController@searchStaff')->name('dashboard.searchstaff');
Route::get('/branch-index', 'BranchController@index')->name('branch.index');
Route::post('/branch-store', 'BranchController@store')->name('branch.store');

Route::get('/branch-dashboard', 'BranchController@dashboard')->name('branch.dashboard');
Route::get('/branch-getstaff', 'BranchController@getStaff')->name('branch.getstaff');
Route::post('/branch-poststaff', 'BranchController@postStaff')->name('branch.poststaff');

Route::get('/branch-branchtrips', 'BranchController@branchTrips')->name('branch.branchtrips');
Route::get('/branch-branchtriprequest', 'BranchController@branchTripRequest')->name('branch.branchtriprequest');
Route::post('/branch-searchtrip', 'BranchController@searchTrip')->name('branch.searchtrip');

Route::get('/branch-approve-triprequest/{id}', 'BranchController@approveTripRequest')->name('branch.approvetriprequest'); 

Route::get('/branch-decline-trip-request/{id}', 'BranchController@declineTripRequest')->name('branch.declinetriprequest');

Route::get('/businessbranchdashboard/{business_code}', 'BusinessController@businessBranchDashboard')->name('business.businessbranchdashboard');
Route::get('/businessbranchtrips/{business_code}', 'BusinessController@businessBranchTrips')->name('business.businessbranchtrips');
Route::post('/searchbranchtrip/{business_code}', 'BusinessController@searchBranchTrip')->name('business.searchbranchtrip');
Route::get('/getbranchstaff/{business_code}', 'BusinessController@getBranchStaff')->name('business.getbranchstaff');

Route::get('/emailtest', 'UserController@emailTest')->name('user.emailtest');

Route::get('/branch-driver-request', 'BusinessController@getBranchDriverRequest')->name('business.getbranchdriverrequest'); 

Route::post('/auto-approve-business', 'BusinessController@autoApproveBusiness')->name('business.autoapprovebusiness');

Route::get('/approve-driver-request/{id}', 'BusinessController@approveDriverRequest')->name('business.approvedriverrequest'); 

Route::get('/decline-driver-request/{id}', 'BusinessController@declineDriverRequest')->name('business.declinedriverrequest');


Route::get('/branch-driver-request-branch', 'BranchController@getBranchDriverRequest')->name('branch.getbranchdriverrequest'); 

Route::get('/approve-driver-request-branch/{id}', 'BranchController@approveDriverRequest')->name('branch.approvedriverrequest'); 

Route::get('/decline-driver-request-branch/{id}', 'BranchController@declineDriverRequest')->name('branch.declinedriverrequest');

Route::get('/dashboard-server', 'DashboardController@getAllCompletedTripsServer')->name('dashboard.index.server');

Route::resource('driver', 'DriverController');
Route::get('/list-driver-with-amount-count', 'DriverController@listDriverWithAmountCount')->name('driver.listdriverwithamountcount');
Route::get('/list-upload', 'DriverController@CountDriver')->name('driver.indd');
Route::get('/list', 'DriverController@CountDriver')->name('dashboard.uploadlist');
Route::post('/files', 'DriverController@storee');

Route::get('/audit', 'AuditController@index')->name('audit.index');


Route::any('/tripswithbranch', 'BusinessController@tripsWithBranch')->name('business.tripswithbranch');
Route::post('/searchbranchbusinesstrip', 'BusinessController@searchBranchBusinessTrip')->name('business.searchbranchbusinesstrip');

Route::resource('trackertrip', 'TrackerTripController');

Route::get('/driver-editdriver/{id}', 'DriverController@editDriver')->name('driver.edit');
Route::post('/driver-edit/{id}', 'DriverController@postedit')->name('driver.postedit');

Route::get('/delete-driver/{id}', 'DriverController@deleteDriver')->name('driver.deletedriver');
Route::get('/export-trips/{type}', 'BusinessController@exportTrips')->name('business.exporttrips');

Route::get('/filter-driver-with-date', 'DriverController@filterDate')->name('driver.filterDate');

Route::get('/app-settings-index', 'AppSettingController@index')->name('appsetting.index');
Route::post('/app-settings-update', 'AppSettingController@update')->name('appsetting.update');

//business account edit route

Route::get('/branch-editbranch/{unique_code}', 'BranchController@editB')->name('business.branch.editB');
Route::post('/branch-edit/{unique_code}', 'BranchController@posteditB')->name('business.branch.posteditB');

//password reset 
Route::get('/password-reset', 'PasswordController@index')->name('password.index');
Route::post('/password-reset', 'PasswordController@store')->name('password.store');
//Region
Route::resource('region', 'RegionController');

//Platenumber
Route::get('/driver-platenumber', 'DriverPlatenumberController@index')->name('platenumber.index');
Route::get('/driver-platenumber/edit/{id}', 'DriverPlatenumberController@edit')->name('platenumber.edit');
Route::post('/driver-platenumber/update/{id}', 'DriverPlatenumberController@update')->name('platenumber.update');

//BMController
Route::get('/bm-dashboard', 'BMController@index')->name('bm.index');

Route::get('/bm-regiondrivers', 'BMController@regionDrivers')->name('bm.regiondrivers');
Route::get('/bm-allregion-drivers', 'BMController@allRegionDrivers')->name('bm.allregiondrivers');

//document upload
Route::get('/files/create','DocumentController@create');
Route::post('/files','DocumentController@store');
Route::post('/files','DocumentController@index');
Route::get('/file/download/{file}','DocumentController@download');


