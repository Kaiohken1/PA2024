<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserAvisController;
use App\Http\Controllers\FermetureController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\AppartementController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\AppartementAvisController;
use App\Http\Controllers\Provider\ServiceController;
use App\Http\Controllers\Provider\ProviderController;

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

Route::get('/test', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/', [AppartementController::class, 'index'])->name('property.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [AppartementController::class, 'userIndex'])->name('dashboard');


    Route::resource('property', AppartementController::class)->except(['index']);
    Route::resource('fermeture', FermetureController::class)->except(['index']);
    Route::get('/dashboard', [AppartementController::class, 'userIndex'])->name('dashboard');

    Route::delete('/property/image/{id}', [AppartementController::class, 'destroyImg'])->name('property.destroyImg');

    Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation');
    Route::get('/reservation/{id}/edit', [ReservationController::class, 'edit'])->name('reservation.edit');
    Route::get('reservation/create/{appartement_id}', [ReservationController::class, 'create'])->name('reservation.create');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservation.index');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
    Route::patch('/reservation/validate/{id}', [ReservationController::class, 'validate'])->name('reservation.validate');
    Route::patch('/reservation/refused/{id}', [ReservationController::class, 'refused'])->name('reservation.refused');
    Route::get('/reservation/{id}', [ReservationController::class, 'showAll'])->name('reservation.showAll');

    Route::prefix('property/{appartement}/edit')->group(function () {
        Route::get('/fermetures', [FermetureController::class, 'index'])->name('fermeture.index');
        Route::delete('/fermetures/{fermeture}', [FermetureController::class, 'destroy'])->name('fermeture.destroy');
        Route::patch('/fermetures/{fermeture}', [FermetureController::class, 'update'])->name('fermeture.update');
        Route::get('/fermetures/create', [FermetureController::class, 'create'])->name('fermeture.create');
        Route::post('/fermetures', [FermetureController::class, 'store'])->name('fermeture.store');
    });

    Route::resource('notifcations', NotificationsController::class);
    Route::resource('property/{id}/interventions', InterventionController::class);

    Route::prefix('property/{appartement}/')->group(function () {
        Route::resource('avis', AppartementAvisController::class)->except('create');
        Route::post('avis/{avis}/edit', [AppartementAvisController::class, 'edit'])->name('avis.edit');

    });

    Route::prefix('/reservation/{id}')->group(function () {
        Route::match(['get', 'post'], '/avis', [AppartementAvisController::class, 'create'])->name('avis.create');
        Route::post('/cancel', [ReservationController::class, 'destroy'])->name('reservation.cancel');
    });

});




Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::resource('tags', TagController::class);
    Route::patch('/provider/{id}', [UserController::class, 'validateProvider'])->name('providers.validate');
});

Route::prefix('users/{user}')->group(function () {
Route::get('/show', [UserController::class, 'show'])->name('users.show');
Route::get('/avis/create', [UserAvisController::class, 'create'])->name('users.avis.create');
Route::post('/avis/store', [UserAvisController::class, 'store'])->name('users.avis.store');
Route::get('/avis/{avis}/edit', [UserAvisController::class, 'edit'])->name('users.avis.edit');
Route::delete('/avis/{avis}', [UserAvisController::class, 'destroy'])->name('users.avis.destroy');
});


Route::get('/admin', function () {
    return view('admin.index');
})->middleware(['admin'])->name('admin');

Route::resource('services', ServiceController::class)->middleware(['admin']);

Route::resource('providers', ProviderController::class)->middleware(['auth']);
Route::resource('notifcations', NotificationsController::class)->middleware(['auth']);

Route::prefix('estimation')->group(function () {
    Route::get('/', [EstimationController::class, 'index'])->name('estimation.index');
    Route::post('/result', [EstimationController::class, 'result'])->name('estimation.result');
});


Route::get('/reservation/{id}/pay', [ReservationController::class, 'pay'])->name('reservation.pay');



require __DIR__.'/auth.php';
