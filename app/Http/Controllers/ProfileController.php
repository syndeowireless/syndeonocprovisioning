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

    public function edit(Request $request): View
    {
        $user = $request->user();
        

        \Log::info('ProfileController@edit - User:', [
            'user' => $user,
            'authenticated' => Auth::check(),
            'session_id' => $request->session()->getId(),
            'user_id' => Auth::id(),
        ]);
        
        if (!$user) {
            \Log::error('ProfileController@edit - No authenticated user found');
            abort(401, 'User not authenticated');
        }
        
        return view('profile.edit', [
            'user' => $user,
        ]);
    }


    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');

            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return Redirect::route('profile.edit')->withErrors(['profile_picture' => 'Invalid file type. Only JPEG, PNG, JPG, and GIF files are allowed.']);
            }

            if ($file->getSize() > 5 * 1024 * 1024) {
                return Redirect::route('profile.edit')->withErrors(['profile_picture' => 'File size too large. Maximum size is 5MB.']);
            }

            try {
                $path = $file->store('profile-pictures', 'public');
                $user->profile_picture = $path;
            } catch (\Exception $e) {
                // Don't update profile_picture if upload fails
            }
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


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
