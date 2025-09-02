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
        $user = $request->user();
        
        // Debug information
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

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle optional profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');

            try {
                // 1) Cloudinary (if configured via CLOUDINARY_URL)
                if (!empty(env('CLOUDINARY_URL'))) {
                    // Using Cloudinary Laravel package
                    $uploaded = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload($file->getRealPath(), [
                        'folder' => 'profile-pictures',
                        'resource_type' => 'image',
                    ]);
                    $user->profile_picture = $uploaded->getSecurePath(); // store absolute https URL
                } else {
                    // 2) Fallback to public disk
                    $path = $file->store('profile-pictures', 'public');
                    $user->profile_picture = $path; // relative path for public
                }
            } catch (\Exception $e) {
                // Log the error and fallback to public storage
                \Log::error('Profile picture upload failed: ' . $e->getMessage());
                $path = $file->store('profile-pictures', 'public');
                $user->profile_picture = $path;
            }
        }

        $user->save();

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
