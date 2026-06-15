<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Render the departments page.
     */
    public function index()
    {
        return view('departments.index');
    }

    /**
     * Return JSON list for DataTable.
     * Route: GET /departments/data
     */
    public function getData()
    {
        $departments = Department::select(
            'department_id',
            'department_name',
            'description',
            'status',
            'created_at'
        )->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $departments,
        ]);
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_name' => 'required|string|max:255|unique:departments,department_name',
            'description'     => 'nullable|string|max:1000',
            'status'          => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $department = Department::create($request->only([
            'department_name',
            'description',
            'status',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully.',
            'data'    => $department,
        ], 201);
    }

    /**
     * Return a single department for editing.
     */
    public function show($id)
    {
        $department = Department::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $department,
        ]);
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'department_name' => 'required|string|max:255|unique:departments,department_name,' . $id . ',department_id',
            'description'     => 'nullable|string|max:1000',
            'status'          => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $department->update($request->only([
            'department_name',
            'description',
            'status',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully.',
            'data'    => $department,
        ]);
    }

    /**
     * Remove the specified department.
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);

        if ($department->employees()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete: this department has linked employees.',
            ], 422);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully.',
        ]);
    }
}