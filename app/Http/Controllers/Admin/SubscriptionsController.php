<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = Subscription::All();

        return view('admin.subscriptions.index', ['subscriptions' => $subscriptions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subscriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required', 'string'],
            'monthly_price' => ['required', 'numeric'],
            'annual_price' => ['required', 'numeric'],
            'permanent_discount' => ['required', 'numeric'],
            'renewal_bonus' => ['required', 'numeric'],
            'logo' => ['required', 'image'],
        ]);

        $path = $request->file('logo')->store('subs-logos', 'public');
        $validateData['logo'] = $path;

        $subscription = new Subscription($validateData);
        $subscription->save();

        return redirect(route('subscriptions.index'))
        ->with('success', "Votre demande a bien été prise en compte, elle sera soumise à validation par un administrateur");
        }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        return view('admin.subscriptions.show', ['subscription' => $subscription]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.edit', ['subscription' => $subscription]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'monthly_price' => ['required', 'numeric'],
            'annual_price' => ['required', 'numeric'],
            'permanent_discount' => ['required', 'numeric'],
            'renewal_bonus' => ['required', 'numeric'],
            'logo' => ['image'],
        ]);

        if ($request->file('logo')) {
            Storage::disk('public')->delete($subscription->logo);
            $path = $request->file('avatar')->store('profil', 'public');

            $validatedData['logo'] = $path;

        }

        $subscription->update($validatedData);

        return redirect()->route('subscriptions.index')
        ->with('success', "Appartement créé avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        Storage::disk('public')->delete($subscription->logo);

        $subscription->delete();

        return redirect()->route('subscriptions.index')
            ->with('success', 'L\'abonnement a été supprimé avec succès');
    }
}
