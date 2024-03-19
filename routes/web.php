<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Presta\PrestataireController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/prestataire', [PrestataireController::class, 'create'])->name('prestataire');
    Route::post('/prestataire', [PrestataireController::class, 'store']);

});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Route::get('service', [ServiceController::class, 'index'])->name('admin.service.index');
    // Route::post('service', [ServiceController::class, 'store'])->name('admin.service.store');

});



Route::get('/admin', function () {
    $user = Auth::user();
    $user->readNotifications()->delete();
    
    $notifications = $user->notifications;
    
    return view('admin.index', compact('notifications'));
})->middleware(['auth', 'admin'])->name('admin');

require __DIR__.'/auth.php';
