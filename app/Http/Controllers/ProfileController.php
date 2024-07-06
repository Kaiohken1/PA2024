<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        
        if ($request->file('avatar') && $request->user()->avatar) {
                Storage::disk('public')->delete($request->user()->avatar);
        }
        
        $request->user()->fill($request->validated());
        $request->user()->number = str_replace('+', '', $request->user()->number);
    
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
    
        if ($request->file('avatar')) {
            $path = $request->file('avatar')->store('profil', 'public');
            $request->user()->avatar = $path;
        }

        $request->user()->save();
    
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
