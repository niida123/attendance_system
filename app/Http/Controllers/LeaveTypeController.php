<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
{
    /**
     * Render the leave types page.
     */
    public function index()
    {
        return view('leave_types.index');
    }

    /**
     * Return JSON list for DataTable.
     * Route: GET /leave-types/data
     */
    public function getData()
    {
        $leaveTypes = LeaveType::select(
            'leave_type_id',
            'leave_name',
            'max_days_per_year',
            'description',
            'status',
            'created_at'
        )->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $leaveTypes,
        ]);
    }

    /**
     * Store a newly created leave type.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leave_name' => 'required|string|max:255|unique:leave_types,leave_name',
            'max_days_per_year' => 'required|integer|min:0',
            'description'     => 'nullable|string|max:1000',
            'status'          => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $leaveType = LeaveType::create($request->only([
            'leave_name',
            'max_days_per_year',
            'description',
            'status',
            'created_at',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Leave type created successfully.',
            'data'    => $leaveType,
        ], 201);
    }

    /**
     * Return a single leave type for editing.
     */
    public function show($id)
    {
        $leaveType = LeaveType::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $leaveType,
        ]);
    }

    /**
     * Update the specified leave type.
     */
    public function update(Request $request, $id)
    {
        $leaveType = LeaveType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'leave_name' => 'required|string|max:255|unique:leave_types,leave_name,' . $id . ',leave_type_id',
            'max_days_per_year' => 'required|integer|min:0',
            'description'     => 'nullable|string|max:1000',
            'status'          => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $leaveType->update($request->only([
            'leave_name',
            'max_days_per_year',
            'description',
            'status',
            'created_at',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Leave type updated successfully.',
            'data'    => $leaveType,
        ]);
    }

    /**
     * Remove the specified leave type.
     */
    public function destroy($id)
    {
        $leaveType = LeaveType::findOrFail($id);

        if ($leaveType->leaveRequests()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete: this leave type has linked leave requests.',
            ], 422);
        }

        $leaveType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Leave type deleted successfully.',
        ]);
    }
}
