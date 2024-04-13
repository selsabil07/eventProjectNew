<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\ExhibitorController;
use App\Http\Controllers\EventManagerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {

    // Route::get('adminInfo/{id}', [adminController::class, 'adminInfo']);
    Route::get('approvedEventManagers', [EventManagerController::class, 'index']);
    Route::get('requestEventManagers', [EventManagerController::class, 'requests']);
    Route::get('approvedEventManagersCount', [EventManagerController::class, 'approvedEventManagersCount']);
    Route::get('managerRequestCount', [EventManagerController::class, 'requestCount']);
    Route::get('events', [EventController::class, 'index']);
    Route::get('search/{$eventTitle}', [EventController::class, 'search']);
    Route::get('EventCount', [EventController::class, 'EventCount']);
    Route::post('activate/{id}', [adminController::class, 'activate']);
    Route::post('deactivate/{id}', [adminController::class, 'deactivate']);
    Route::post('updateinfo', [adminController::class, 'updateinfo']);
    Route::post('approveEventManager/{id}', [EventManagerController::class, 'approveEventManager']);
    Route::delete('rejectEventManager/{id}', [EventManagerController::class, 'rejectEventManager']);
    Route::get('showAdmin', [AuthController::class, 'showAdmin']);
    Route::get('eventsOfUser/{id}', [EventManagerController::class, 'eventsOfUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('notifCount', [AuthController::class, 'notifCount']);
    Route::get('notifs', [AuthController::class, 'notifs']);
    Route::get('allevents', [EventController::class, 'allevents']);
    Route::get('sector', [EventManagerController::class, 'sector']);


});
Route::post('search', [EventManagerController::class, 'search']);



Route::group(['middleware' => ['auth:sanctum', 'role:eventManager']], function () {

    Route::get('showEventManager', [AuthController::class, 'show']);
    Route::get('eventManagerNotifs', [AuthController::class, 'eventManagerNotifs']);
    Route::get('eventManagerNotifCount', [AuthController::class, 'eventManagerNotifCount']);

    Route::post('eventCreate', [EventController::class, 'create']);

    Route::post('eventManager/update', [EventManagerController::class, 'update']);
    Route::post('event/update/{id}', [EventController::class, 'update']);
    Route::delete('event/delete/{id}', [EventController::class, 'destroy']);
    Route::post('logoutEventManager', [AuthController::class, 'logoutEventManager']);
    Route::get('EventCountOfTheCurrentUser', [EventController::class, 'EventCountOfTheCurrentUser']);
    Route::get('events', [EventController::class, 'eventsOfCurrentUser']);

    Route::get('approvedExibitors', [ExhibitorController::class, 'approvedExibitors']);
    Route::get('numberOfExhibitors', [ExhibitorController::class, 'numberOfExhibitors']);
    Route::get('requestCount', [ExhibitorController::class, 'requestCount']);
    Route::delete('destroy/{id}', [ExhibitorController::class, 'destroy']);
    Route::get('allRequests', [ExhibitorController::class, 'allRequests']);
    Route::post('approveExhibitor/{id}', [ExhibitorController::class, 'approveExhibitor']);
    Route::post('rejectExhibitor/{id}', [ExhibitorController::class, 'rejectExhibitor']);
    Route::get('exhibitor/requests/{id}', [ExhibitorController::class, 'exhibitorRequests']);
    Route::get('exhibitors/{id}', [ExhibitorController::class, 'Exhibitors']);
    Route::get('allExhibitors', [ExhibitorController::class, 'allExhibitors']);
});  
  Route::post('event/{id}/create', [SponsorController::class, 'create']);

    
   

Route::group(['middleware' => ['auth:sanctum', 'role:exhibitor']],function () {
    Route::post('update', [ExhibitorController::class, 'update']);
    Route::post('logoutExhibitor', [AuthController::class, 'logoutExhibitor']);
    Route::get('showExhibitor', [AuthController::class, 'showExhibitor']);
    Route::post('createProduct/{id}', [ProductController::class , 'create']);
    Route::get('showUserProducts', [ProductController::class, 'showUserProducts']);
    Route::get('show/{id}', [ProductController::class, 'show']);
    Route::get('myevent', [ExhibitorController::class, 'myevent']);
    Route::post('edit', [ProductController::class, 'edit']);
    Route::post('join/{id}', [AuthController::class, 'join']);

});    


Route::get('event/{id}', [EventController::class, 'show']);
Route::get('eventsOfUser/{id}', [EventController::class, 'eventsOfUser']);
Route::get('allevents', [EventController::class, 'allevents']);

Route::post('reset-password', [AuthController::class, 'reset'])->name('reset-password');
Route::post('forgot-password', [AuthController::class, 'forgot']);

Route::post('eventManager/register', [AuthController::class , 'eventManagerRegister']);
// Route::post('exhibitor/register', [AuthController::class , 'exhibitorRegister']);

Route::post('exhibitorRegister', [AuthController::class, 'exhibitorRegister']);


Route::post('login', [AuthController::class , 'login']);

Route::post('loginExhibitor', [AuthController::class , 'loginExhibitor']);

Route::get('allProducts/{id}', [ProductController::class , 'index']);

Route::get('products/{id}', [ProductController::class , 'products']);

Route::get('showUser', [AuthController::class , 'showUser']);

Route::get('exhibitor/{id}', [ExhibitorController::class , 'show']);

Route::post('subscribe', [VisitorController::class , 'create']);
