<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function show(Request $request)
    {
        $user = $request->user()->load(['friends.friend']);
        $friends = $user->friends()->with('friend')->limit(6)->get();

        return view('profile', compact('user', 'friends'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();
        $emailChanged = $user->email !== $validatedData['email'];

        if ($emailChanged) {
            $user->email_verified_at = null;
            $user->email = $validatedData['email'];
            $user->save();
            $user->sendEmailVerificationNotification();
            Auth::logout();
            return Redirect::route('login')->with('status', 'Email updated. Please verify your new email before logging in.');
        }

        $user->fill($validatedData);
        $user->save();
        return Redirect::route('profile')->with('status', 'Profile updated successfully');
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
     * Update the user's profile image.
     */
    public function updateImage(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user = $request->user();
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $user->photo_profil = $path;
        $user->save();
        return redirect()->route('profile')->with('status', 'Profile image updated successfully!');
    }
}
