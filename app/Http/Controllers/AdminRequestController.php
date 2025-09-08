<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminRequestController extends Controller
{
    public function index()
    {
        $requests = AdminRequest::orderByDesc('id')->paginate(20);
        return view('profile.admin-requests', compact('requests'));
    }

    public function accept(AdminRequest $adminRequest)
    {
        if ($adminRequest->status !== 'pending') {
            return back()->with('status', 'This request has already been processed.');
        }

        // Create user if not exists; otherwise upgrade existing user to admin
        $existing = User::where('email', $adminRequest->email)->first();
        if ($existing) {
            $existing->password = $adminRequest->password; // already hashed
            $existing->role = 'admin';
            $existing->save();
        } else {
            $user = User::create([
                'name' => $adminRequest->name,
                'email' => $adminRequest->email,
                'password' => $adminRequest->password, // already hashed when stored
                'role' => 'admin',
            ]);
        }

        $adminRequest->status = 'accepted';
        $adminRequest->accepted_by = Auth::user()->email ?? 'system';
        $adminRequest->accepted_at = now();
        $adminRequest->save();

        return back()->with('status', 'Admin request accepted and user created.');
    }

    public function reject(AdminRequest $adminRequest)
    {
        if ($adminRequest->status !== 'pending') {
            return back()->with('status', 'This request has already been processed.');
        }

        $adminRequest->status = 'rejected';
        $adminRequest->accepted_by = Auth::user()->email ?? 'system';
        $adminRequest->accepted_at = now();
        $adminRequest->save();

        return back()->with('status', 'Admin request rejected.');
    }
}


