<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display the users index page.
     */
    public function index()
    {
        $roles     = Role::orderBy('role_name')->get();
        $employees = Employee::orderBy('first_name')->get();

        return view('users.index', compact('roles', 'employees'));
    }

    /**
     * Return JSON data for DataTable.
     */
    public function getData()
    {
        $users = User::with(['role', 'employee'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $users]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'    => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => ['required', 'confirmed', Password::min(8)],
            'role_id'     => 'required|exists:roles,role_id',
            'employee_id' => 'nullable|exists:employees,employee_id',
            'status'      => 'required|in:Active,Inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User "' . $user->username . '" created successfully.',
        ]);
    }

    /**
     * Return a single user for the edit modal.
     */
    public function show($id)
    {
        $user = User::with(['role', 'employee'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $user,
        ]);
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username'    => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'password'    => ['nullable', 'confirmed', Password::min(8)],
            'role_id'     => 'required|exists:roles,role_id',
            'employee_id' => 'nullable|exists:employees,employee_id',
            'status'      => 'required|in:Active,Inactive',
        ]);

        // Only update password if a new one was provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User "' . $user->username . '" updated successfully.',
        ]);
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting the currently authenticated user
        if (Auth::id() !== null && Auth::id() === $user->getKey()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User "' . $user->username . '" deleted successfully.',
        ]);
    }
}