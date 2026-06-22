<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('roles.index');
    }

    // Return JSON list for DataTable.
     // Route: GET /roles/data
    public function getData()
    {
        $roles = Role::select(
            'id',
            'name',
            'description',
            'status',
            'created_at'
        )->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:1000',
            'status'      => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data'    => $role
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $role = Role::findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $role
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $role = Role::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255|unique:roles,name,' . $id . ',id',
            'description' => 'nullable|string|max:1000',
            'status'      => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $role->update($request->only([
            'name',
            'description',
            'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data'    => $role
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        if ($role->assignedUsers()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete: this role has linked users.',
            ], 422);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.',
        ]);
    }
}
