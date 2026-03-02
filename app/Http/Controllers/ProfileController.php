<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
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

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],
        ], [
            'profile_photo.required' => 'Por favor selecciona una imagen.',
            'profile_photo.image' => 'El archivo debe ser una imagen.',
            'profile_photo.mimes' => 'La imagen debe ser de tipo: jpeg, jpg, png o gif.',
            'profile_photo.max' => 'La imagen no debe superar los 2MB.',
        ]);

        $user = $request->user();

        // Eliminar foto anterior si existe
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Subir nueva foto
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        
        $user->profile_photo = $path;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'photo-updated');
    }

    /**
     * Delete the user's profile photo.
     */
    public function destroyPhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->profile_photo = null;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'photo-deleted');
    }
}
