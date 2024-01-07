<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\eventManagerController;

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

    Route::get('approvedEventManagers', [EventManagerController::class, 'index']);

    Route::get('requestEventManagers', [EventManagerController::class, 'requests']);

    Route::get('approvedEventManagersCount', [EventManagerController::class, 'approvedEventManagers']);

    Route::get('requestCount', [EventManagerController::class, 'requestCount']);

    Route::get('events', [EventController::class, 'index']);

    Route::get('search', [EventController::class, 'seach']);

    Route::get('EventCount', [EventController::class, 'EventCount']);



    Route::post('approveEventManager/{$id}', [AuthController::class, 'approveEventManager']);
    Route::delete('rejectEventManager/{$id}', [AuthController::class, 'rejectEventManager']);

});

Route::group(['middleware' => ['auth:sanctum', 'role:eventManager']], function () {
    Route::get('showUser', [AuthController::class, 'showUser']);
    
    Route::get('eventsOfUser', [EventManagerController::class, 'eventsOfUser']);

});

Route::post('eventManager/register', [AuthController::class , 'eventManagerRegister']);

Route::post('exposant/register', [AuthController::class , 'register']);

Route::post('login', [AuthController::class , 'login']);

