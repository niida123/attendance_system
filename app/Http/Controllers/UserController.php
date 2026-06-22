<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display the users index page.
     */
    public function index()
    {
        $roles     = Role::orderBy('name')->get();
        $employees = Employee::orderBy('first_name')->get();

        return view('users.index', compact('roles', 'employees'));
    }

    /**
     * Return JSON data for DataTable.
     */
    public function getData()
    {
        // Eager-load the real Spatie relation ('roles'), not the old 'role' relation —
        // the User model's getRoleAttribute() accessor will use this loaded collection
        // automatically, so 'role' still appears correctly in the JSON output below.
        $users = User::with(['roles', 'employee'])
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
            'id'          => 'required|exists:roles,id', // role id from the select
            'employee_id' => 'nullable|exists:employees,employee_id',
            'status'      => 'required|in:Active,Inactive',
        ],
        // Custom error messages for password validation
        [
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.required'  => 'Password is required.',
        ]);

        $roleId = $validated['id'];
        unset($validated['id']); // not a user column — would be silently dropped anyway, but be explicit

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // This is the step that was missing — actually assign the role via Spatie
        $role = Role::findById($roleId);
        $user->syncRoles([$role->name]);

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
        $user = User::with(['roles', 'employee'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $user->id,
                'username'    => $user->username,
                'email'       => $user->email,
                'role_id'     => optional($user->roles->first())->id,
                'employee_id' => $user->employee_id,
                'status'      => $user->status,
            ],
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
            'id'          => 'required|exists:roles,id',
            'employee_id' => 'nullable|exists:employees,employee_id',
            'status'      => 'required|in:Active,Inactive',
        ],
        [
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $roleId = $validated['id'];
        unset($validated['id']);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        $role = Role::findById($roleId);
        $user->syncRoles([$role->name]);

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