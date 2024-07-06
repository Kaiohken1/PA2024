<?php

use App\Livewire\Chat;
use Illuminate\Http\Request;
use App\Livewire\DynamicInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\RedirectAfterPayment;
use App\Http\Controllers\TagController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserAvisController;
use App\Http\Controllers\FermetureController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\AppartementController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\AppartementAvisController;
use App\Http\Controllers\Provider\ServiceController;
use App\Http\Controllers\Provider\ProviderController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\SubscriptionControllerClient;
use App\Http\Controllers\Admin\SubscriptionsController;
use App\Http\Controllers\InterventionEstimateController;
use App\Http\Controllers\Provider\InterventionController;
use App\Http\Controllers\InterventionEstimationController;
use App\Http\Controllers\Admin\ProviderController as AdminProviderController;
use App\Http\Controllers\Admin\InterventionController as AdminInterventionController;
// use App\Http\Controllers\StripeWebhookController;

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

Route::get('/test/{id}', function () {
    return view('test');
})->name('test');

Route::match(['get', 'post'], '/', [AppartementController::class, 'index'])->name('property.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [AppartementController::class, 'userIndex'])->name('dashboard');


    Route::resource('property', AppartementController::class)->except(['index']);
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
    
    Route::post('/estimate', [InterventionEstimationController::class, 'store'])->name('estimate.store');
    Route::patch('/estimate/{id}', [InterventionEstimationController::class, 'update'])->name('estimate.update');
    Route::delete('/estimate/{id}', [InterventionEstimationController::class, 'destroy'])->name('estimate.destroy');


    Route::get('/subscribe', [SubscriptionControllerClient::class, 'showSubscriptionForm'])->name('subscribe.form');
    Route::post('/subscribe', [SubscriptionControllerClient::class, 'subscribe'])->name('subscribe');
    Route::get('/subscribe/checkout/success', [SubscriptionControllerClient::class, 'success'])->name('checkout.success');
    Route::get('/subscribe/checkout/cancel', [SubscriptionControllerClient::class, 'cancel'])->name('checkout.cancel');
    // Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);

    // Route::resource('/subscribe',SubscriptionControllerClient ::class);


    Route::prefix('property/{appartement}/edit')->group(function () {
        Route::resource('fermeture', FermetureController::class);
        // Route::get('/fermetures', [FermetureController::class, 'index'])->name('fermeture.index');
        // Route::delete('/fermetures/{fermeture}', [FermetureController::class, 'destroy'])->name('fermeture.destroy');
        // Route::patch('/fermetures/{fermeture}', [FermetureController::class, 'update'])->name('fermeture.update');
        // Route::get('/fermetures/create', [FermetureController::class, 'create'])->name('fermeture.create');
        // Route::post('/fermetures', [FermetureController::class, 'store'])->name('fermeture.store');
    });

    Route::resource('notifcations', NotificationsController::class);
    Route::resource('property/{id}/interventions', InterventionController::class);
    Route::get('/contract/{providerId}', [ContractController::class, 'generateContract'])->name('contract.generate');
    Route::get('/providers/contract/{providerId}', [ContractController::class, 'generateIntervention'])->name('contract.generate-intervention');
    Route::get('/providers/fiche/{interventionId}', [ContractController::class, 'generateFiche'])->name('contract.fiche');
    Route::get('/interventions/invoice/{id}', [ContractController::class, 'generateInvoice'])->name('interventions.generate');

    Route::post('/providers/contract', [ContractController::class, 'store'])->name('contract.store-intervention');
    Route::get('/providers/dashboard', [ProviderController::class, 'home'])->name('provider.dashboard');
    Route::get('/providers/proposals', [ProviderController::class, 'proposals'])->name('providers.proposals');
    Route::get('/providers/proposals/{id}', [InterventionController::class, 'show'])->name('proposals.show');
    Route::get('/providers/calendar', [ProviderController::class, 'calendar'])->name('provider.calendar');
    Route::get('/providers/availability', [ProviderController::class, 'availability'])->name('provider.availability');
    Route::post('/providers/availability', [ProviderController::class, 'availabilityCreate'])->name('provider.availabilityCreate');
    Route::delete('/providers/availability/{id}', [ProviderController::class, 'availabilityDestroy'])->name('provider.availabilityDestroy');
    Route::get('/providers/interventions', [ProviderController::class, 'interventionsIndex'])->name('provider.interventionIndex');
    Route::get('/providers/interventions/{id}', [InterventionController::class, 'showProvider'])->name('interventions-provider.show');

    Route::get('/interventions/dashboard', [InterventionController::class, 'index'])->name('interventions.dashboard');
    Route::get('/interventions/{id}', [InterventionController::class, 'clientShow'])->name('interventions.clientShow');
    Route::post('/interventions/{id}/plan', [InterventionController::class, 'plan'])->name('interventions.plan');
    Route::post('/interventions/{id}/checkout', [InterventionController::class, 'checkout'])->name('interventions.checkout');
    Route::post('/interventions/{id}/refused', [InterventionController::class, 'refusal'])->name('interventions.refused');
    Route::get('interventions/{id}/redirect/{token}', [InterventionController::class, 'redirect'])->name('interventions.redirect');
    
    Route::get('/interventions/{intervention}/chat/{user}', Chat::class)->name('interventions.chat');

    Route::get('/messagerie', function() {
        if(Auth::user()->provider) {
            return view('provider.messagerie');
        } else {
            return view('interventions.messagerie');
        }
    })->name('interventions.messagerie');



    Route::get('/espace-client', function () {
        return view('espace-client');
    })->name('espace-client');
    Route::prefix('property/{appartement}/')->group(function () {
        Route::resource('avis', AppartementAvisController::class)->except('create', 'edit');
        Route::post('avis/{avis}/edit', [AppartementAvisController::class, 'edit'])->name('avis.edit');

    });

    Route::prefix('/reservation/{id}')->group(function () {
        Route::match(['get', 'post'], '/avis', [AppartementAvisController::class, 'create'])->name('avis.create');
        Route::post('/cancel', [ReservationController::class, 'destroy'])->name('reservation.cancel');
    });

    Route::resource('tickets', TicketController::class);

});




Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::resource('tags', TagController::class);
    Route::patch('/providers/{id}/validate', [AdminProviderController::class, 'validateProvider'])->name('admin.providers.validate');
    Route::resource('services', ServiceController::class)->middleware(['admin']);
    Route::delete('/services/{service}/parameter/{id}', [ServiceController::class, 'destroyParameter'])->name('services.destroyParameter');
    Route::delete('/services/{service}/document/{id}', [ServiceController::class, 'destroyDocument'])->name('services.destroyDocument');
    Route::resource('/interventions', AdminInterventionController::class)->names('admin.interventions');
    Route::patch('/services/{service}/parameter/{id}', [ServiceController::class, 'updateParameter'])->name('services.updateParameter');
    Route::patch('/services/{service}/document/{id}', [ServiceController::class, 'updateDocument'])->name('services.updateDocument');
    Route::resource('/documents', DocumentController::class);
    Route::patch('/services/{id}/statut', [ServiceController::class, 'updateActive'])->name('services.updateActive');
    Route::resource('/subscriptions', SubscriptionsController::class);
    Route::resource('/providers', AdminProviderController::class)->names('admin.providers');
    Route::patch('/interventions/{provider_id}/attribuate', [InterventionController::class, 'attribuate'])->name('admin.attribuate');

    Route::get('/intervention/{id}/providers', [AdminProviderController::class, 'availableProviders'])->name('providers.available');;

    Route::get('/providers/available', [AvailabilityController::class, 'availableProviders']);

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


Route::resource('providers', ProviderController::class)->middleware(['auth']);
Route::resource('notifcations', NotificationsController::class)->middleware(['auth']);

Route::prefix('estimation')->group(function () {
    Route::get('/', [EstimationController::class, 'index'])->name('estimation.index');
    Route::post('/result', [EstimationController::class, 'result'])->name('estimation.result');
});


Route::get('/reservation/{id}/pay', [ReservationController::class, 'pay'])->name('reservation.pay');








require __DIR__.'/auth.php';
