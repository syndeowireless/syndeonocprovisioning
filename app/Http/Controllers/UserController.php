<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Create a new user
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|unique:users,email|max:255',
            'userRole' => 'required|in:admin,user',
            'userPassword' => 'required|string|min:8',
            'confirmPassword' => 'required|string|same:userPassword'
        ], [
            'userName.required' => 'User name is required.',
            'userEmail.required' => 'Email is required.',
            'userEmail.email' => 'Please enter a valid email address.',
            'userEmail.unique' => 'This email is already registered.',
            'userRole.required' => 'User role is required.',
            'userRole.in' => 'Please select a valid role.',
            'userPassword.required' => 'Password is required.',
            'userPassword.min' => 'Password must be at least 8 characters.',
            'confirmPassword.required' => 'Password confirmation is required.',
            'confirmPassword.same' => 'Password confirmation does not match.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->userName,
                'email' => $request->userEmail,
                'role' => $request->userRole,
                'password' => Hash::make($request->userPassword),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'created_at' => $user->created_at->format('M d, Y H:i'),
                    'updated_at' => $user->updated_at->format('M d, Y H:i')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a user
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|max:255|unique:users,email,' . $user->id,
            'newPassword' => 'nullable|string|min:8',
        ], [
            'userName.required' => 'User name is required.',
            'userEmail.required' => 'Email is required.',
            'userEmail.email' => 'Please enter a valid email address.',
            'userEmail.unique' => 'This email is already registered.',
            'newPassword.min' => 'Password must be at least 8 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update basic user information
            $user->name = $request->userName;
            $user->email = $request->userEmail;

            // Check if a new password was provided and is different from current password
            if ($request->filled('newPassword')) {
                $newPassword = $request->newPassword;
                
                // Check if the new password is different from the current password
                if (!Hash::check($newPassword, $user->password)) {
                    $user->password = Hash::make($newPassword);
                }
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'created_at' => $user->created_at->format('M d, Y H:i'),
                    'updated_at' => $user->updated_at->format('M d, Y H:i')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user. Please try again.'
            ], 500);
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'newPassword' => 'required|string|min:8',
            'confirmPassword' => 'required|string|same:newPassword'
        ], [
            'newPassword.required' => 'New password is required.',
            'newPassword.min' => 'Password must be at least 8 characters.',
            'confirmPassword.required' => 'Password confirmation is required.',
            'confirmPassword.same' => 'Password confirmation does not match.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $newPassword = $request->newPassword;
            
            // Check if the new password is different from the current password
            if (Hash::check($newPassword, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'New password must be different from the current password.'
                ], 422);
            }

            // Update the password
            $user->password = Hash::make($newPassword);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a user
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            // Prevent deletion of admin users
            if ($user->role === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete admin users'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user. Please try again.'
            ], 500);
        }
    }
}
