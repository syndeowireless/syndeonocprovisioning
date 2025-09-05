<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of all users for management.
     */
    public function manageUsers(Request $request): View
    {
        // Get pagination parameters
        $perPage = $request->get('per_page', 20);
        $sortOrder = $request->get('sort', 'desc');
        
        // Validate per_page parameter
        $allowedPerPage = [10, 20, 30, 40];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 20;
        }
        
        // Validate sort parameter
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        
        // Fetch users with pagination
        $users = User::select([
            'id',
            'name', 
            'email',
            'role',
            'created_at',
            'updated_at'
        ])
        ->orderBy('created_at', $sortOrder)
        ->paginate($perPage);

        return view('profile.manage-users', compact('users', 'perPage', 'sortOrder'));
    }
}
