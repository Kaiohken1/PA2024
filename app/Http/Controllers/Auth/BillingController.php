<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Request;

class BillingController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'adresse' => ['required', 'string', 'max:255'],
            'code_postal' => ['required', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update($validated);
        
        return Redirect::route('profile.edit')->with('status', 'billing-information-updated');
    }
}
