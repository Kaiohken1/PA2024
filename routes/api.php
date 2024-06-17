<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'apiLogout']);

    Route::get('/tickets/attributor', [TicketController::class, 'apiIndexAttributor']);
    Route::get('/tickets/attributor/{id}', [TicketController::class, 'apiShowAttributor']);
    Route::put('/tickets/attributor/update/{id}', [TicketController::class, 'apiUpdateAttributor']);

    Route::get('/tickets/roles', [TicketController::class, 'apiIndexRoles']);
    Route::get('/tickets/roles/{id}', [TicketController::class, 'apiShowRoles']);
    Route::put('/tickets/roles/update/{id}', [TicketController::class, 'apiUpdateRoles']);

    Route::get('/tickets/helper', [TicketController::class, 'apiIndexHelper']);
    Route::get('/tickets/helper/{id}', [TicketController::class, 'apiShowHelper']);
    Route::put('/tickets/helper/update/{id}', [TicketController::class, 'apiUpdateHelper']);

    Route::get('/tickets/admin-history', [TicketController::class, 'apiIndexAdminHistory']);
    Route::get('/tickets/personal-history', [TicketController::class, 'apiIndexPersonalHistory']);

    Route::get('/tickets/stats', [TicketController::class, 'apiStats']);


});



