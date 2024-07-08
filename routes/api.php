<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ReservationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware('guest:sanctum')->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'apiLogin']);
    Route::post('/mobile/login', [AuthenticatedSessionController::class, 'apiMobileLogin']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'apiLogout']);
    Route::post('/mobile/logout', [AuthenticatedSessionController::class, 'apiMobileLogout']);

    Route::get('/tickets/roles', [TicketController::class, 'apiIndexRoles']);
    Route::get('/tickets/roles/{id}', [TicketController::class, 'apiShowRoles']);
    Route::put('/tickets/roles/update/{id}', [TicketController::class, 'apiUpdateRoles']);

    Route::get('/tickets/helper', [TicketController::class, 'apiIndexHelper']);
    Route::get('/tickets/helper/{id}', [TicketController::class, 'apiShowHelper']);
    Route::put('/tickets/helper/update/{id}', [TicketController::class, 'apiUpdateHelper']);

    Route::get('/tickets/personal-history', [TicketController::class, 'apiIndexPersonalHistory']);

    Route::get('/tickets/admin', [TicketController::class, 'apiIndexAdmin']);
    Route::get('/tickets/admin/{id}', [TicketController::class, 'apiShowAdmin']);
    Route::put('/tickets/admin/update/{id}', [TicketController::class, 'apiUpdateAdmin']);

    Route::get('/tickets/stats', [TicketController::class, 'apiStats']);
    Route::get('/tickets/{id}/chat', [TicketController::class, 'apiTicketChat']);
    Route::post('/tickets/chat/send', [TicketController::class, 'apiTicketChatSend']);

    Route::get('/mobile/reservations/user',[ReservationController::class,'MobileIndex']);


});



