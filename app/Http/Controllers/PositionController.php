<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::select('department_id', 'department_name')->get();
        return view('positions.index', compact('departments'));
    }
        /**
        * Return JSON list for DataTable.
        * Route: GET /positions/data
        */
    public function getData()
    {
        $positions = Position::with('department')
                    ->select(
                        'position_id',
                        'position_name',
                        'department_id',
                        'description',
                        'status',
                        'created_at'
                    )->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $positions,
        ]);
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'position_name' => 'required|string|max:255|unique:positions,position_name',
            'department_id' => 'required|exists:departments,department_id',
            'description'   => 'nullable|string|max:1000',
            'status'        => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // Continue with the rest of the store logic
        $position = Position::create($request->only([
            'position_name',
            'department_id',
            'description',
            'status',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Position created successfully.',
            'data'    => $position
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $position = Position::with('department')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $position
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $position = Position::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'position_name' => 'required|string|max:255|unique:positions,position_name,' . $id.',position_id',
            'department_id' => 'required|exists:departments,department_id',
            'description'   => 'nullable|string|max:1000',
            'status'        => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // Continue with the rest of the store logic
        $position->update($request->only([
            'position_name',
            'department_id',
            'description',
            'status',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Position updated successfully.',
            'data'    => $position
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $position = Position::findOrFail($id);
        if ($position->employees()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete "' . $position->position_name . '": this position has linked employees.',
            ], 422);
        }
        $position->delete();
        return response()->json([
            'success' => true,
            'message' => 'Position deleted successfully.'
        ]);

    }
    public function byDepartment($department_id)
    {
        $positions = Position::where('department_id', $department_id)
                    ->where('status', 'Active')
                    ->orderBy('position_name')
                    ->get(['position_id', 'position_name']);

        return response()->json([
            'success' => true,
            'data'    => $positions,
        ]);
    }
}
