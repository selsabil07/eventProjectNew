<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\EventController;
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
    Route::post('update', [adminController::class, 'update']);
    Route::post('approveEventManager/{id}', [EventManagerController::class, 'approveEventManager']);
    Route::delete('rejectEventManager/{id}', [AuthController::class, 'rejectEventManager']);
    Route::get('showAdmin', [AuthController::class, 'showAdmin']);
    Route::get('eventsOfUser/{id}', [EventManagerController::class, 'eventsOfUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('notifCount', [AuthController::class, 'notifCount']);
    Route::get('notifs', [AuthController::class, 'notifs']);
    Route::get('allevents', [EventController::class, 'allevents']);


});
Route::post('search', [EventManagerController::class, 'search']);

Route::group(['middleware' => ['auth:sanctum', 'role:eventManager']], function () {

    Route::get('showEventManager', [AuthController::class, 'showEventManager']);
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


    Route::post('approveExhibitor/{id}', [ExhibitorController::class, 'approveExhibitor']);

});
    Route::get('exhibitor/requests/{id}', [ExhibitorController::class, 'exhibitorRquests']);




Route::group(['middleware' => ['auth:sanctum', 'role:exhibitor']],function () {
    Route::post('update', [ExhibitorController::class, 'update']);
    Route::post('logoutExhibitor', [AuthController::class, 'logoutExhibitor']);
    Route::get('showExhibitor', [AuthController::class, 'showExhibitor']);

});    


Route::get('event/{id}', [EventController::class, 'show']);
Route::get('eventsOfUser/{id}', [EventController::class, 'eventsOfUser']);
Route::get('allevents', [EventController::class, 'allevents']);

Route::post('reset-password', [AuthController::class, 'reset'])->name('reset-password');
Route::post('forgot-password', [AuthController::class, 'forgot']);

Route::post('eventManager/register', [AuthController::class , 'eventManagerRegister']);
// Route::post('exhibitor/register', [AuthController::class , 'exhibitorRegister']);

Route::post('/event/{id}/register', [AuthController::class, 'exhibitorRegister']);


Route::post('login', [AuthController::class , 'login']);
