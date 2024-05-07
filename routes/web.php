<?php

use App\Http\Controllers\Admin\InterventionController as AdminInterventionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FermetureController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AppartementController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Provider\InterventionController;
use App\Http\Controllers\Provider\ServiceController;
use App\Http\Controllers\Provider\ProviderController;
use App\Livewire\DynamicInput;

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

Route::get('/', [AppartementController::class, 'index'])->name('property.index');

Route::get('/dynamic-inputs', DynamicInput::class)->name('dynamic-inputs');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [AppartementController::class, 'userIndex'])->name('dashboard');


    Route::resource('property', AppartementController::class)->except(['index']);
    Route::resource('tag', TagController::class);
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
    Route::post('/reservation/{id}/cancel', [ReservationController::class, 'destroy'])->name('reservation.cancel');
    Route::resource('property/{id}/interventions', InterventionController::class);

});


Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::patch('/provider/{id}', [ProviderController::class, 'validateProvider'])->name('providers.validate');
    Route::resource('services', ServiceController::class)->middleware(['admin']);
    Route::delete('/services/{service}/parameter/{id}', [ServiceController::class, 'destroyParameter'])->name('services.destroyParameter');
    Route::resource('/intverventions', AdminInterventionController::class);
    Route::patch('/services/{service}/parameter/{id}', [ServiceController::class, 'updateParameter'])->name('services.updateParameter');


});

Route::get('/admin', function () {
    return view('admin.index');
})->middleware(['admin'])->name('admin');


Route::resource('providers', ProviderController::class)->middleware(['auth']);
Route::resource('notifcations', NotificationsController::class)->middleware(['auth']);


require __DIR__.'/auth.php';
