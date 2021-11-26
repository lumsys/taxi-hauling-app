<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('API')->group(function () {
Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::post('forgotpassword', 'AuthController@forgotPassword');
Route::get('test/{lat}/{long}','TripDriverController@test');
Route::get('test2','TripDriverController@test2');
Route::get('allbusinessaccount','TripDriverController@allBusinessAccount');
Route::get('businesstripsummary/{unique_code}','TripDriverController@businessTripSummary');

});



Route::namespace('API')->middleware('auth:api')->group(function () {
Route::get('details', 'AuthController@details');
Route::get('logout', 'AuthController@logout');
Route::get('getdrivertrip','TripDriverController@getDriverTrip');
Route::get('gettripdetails/{id}','TripDriverController@getTripDetails'); 
Route::post('requestride','TripDriverController@requestRide'); 
Route::post('getlastassigntrip','TripDriverController@getLastAssignTrip'); 
Route::post('updatetripcompleted/{id}','TripDriverController@updateTripCompleted');
//get last assigned driver details to rider/staff
Route::post('getassigneddriver','TripDriverController@getAssignedDriver'); 

Route::get('getridertrip','TripDriverController@getRiderTrip');
//Driver side   
Route::post('updatetripstatus', 'AuthController@updateTripStatus');
Route::post('updatedriverstatus', 'AuthController@updateDriverStatus');
Route::post('exittripwithrider', 'AuthController@exitTripwithRider');
Route::post('getuserwithconfig/{id}', 'AuthController@getUserwithConfig');

Route::post('subsequenttrip/{riderid}', 'TripDriverController@subsequentTrip');

Route::post('canceltrip', 'TripDriverController@cancelTrip');

Route::get('listofdrivers','TripDriverController@listOfDrivers');

Route::post('driverrequeststate','TripDriverController@driverRequestState');

Route::post('driverdeclinetrip','TripDriverController@driverDeclineTrip');

Route::get('gettripstatus/{id}','TripDriverController@getTripStatus');
Route::get('appsetting','AppSettingController@index');

});

 